<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plant;use App\PoHeader;use App\PoDetail; use App\Company;use App\User;use App\CreditDebitNote;use App\Notifications; 

use App\FAQs;
use App\PoFutureItems;
use App\DeliveryDelayInfo;
use DB;
use App\Exports\PoHeadersExport;
use Exporter;
use Maatwebsite\Excel\Facades\Excel;
use QrCode;
use Storage;
use App\GenBcodeDetails;
use Auth;
use Hash;use Response;
use PDF;
use Illuminate\Validation\Rule; 
class VendorController extends BaseController 
{
   
  public function all_notifications()
   {

         $now=date('Y-m-d');
         $nots=Notifications::whereDate('valied_upTo','>=',$now);
         $notifications=$nots->get();
         return view ('vendor_view.all_vendor_notification',compact('notifications'));
   } 
    public function delivery_schedule_frm()
    {

   
     $companies= Company::all();
    return view ('vendor_view.delevery_schedule',compact('companies'));
    }
    public function ins_data($vencode,$comp_code,$plant,$po)
    {
       $api_fun="get_po_data";
       $rfc_name="ZWMFM_BARCODE_PO_DETAILS";
       $data="function=".$api_fun."&rfc_name=".$rfc_name;
       $po_headers = DB::table('po_headers');
       if($vencode!='')
       {
        $po_headers =$po_headers->where('Vendor_Code',$vencode);
        $data.="&vencode=".$vencode;
       } 
       if($comp_code!='')
       {
        $po_headers = $po_headers->where('Company_Code',$comp_code);
        $data.="&comp_code=".$comp_code;
       }
       if($plant!='')
       {
        $po_headers = $po_headers->where('Plant_Code',$plant);
        $data.="&plant_code=".$plant;
       }
       if($po!='')
       {
        $po_headers=$po_headers->where('PO_Number',$po);
        $data.="&po_no=".$po;
       }
       $count_data =$po_headers->count();
       if($count_data>0)
       {
        $header_data=$po_headers->get();
        
        $i=0;
        $po_no= array();
       foreach ( $header_data as $header ) 
       {
       array_push($po_no,$header->PO_Number);
       }
        $po_details=PoDetail::whereIn('po_number',$po_no);
        $po_del=$po_details->delete();
        $del=$po_headers->delete();
       }
       
      $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?".$data;



        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
       if($resp==200)
        {
          $data=$res->getBody();
            $json = json_decode($data, true);
      
         if($json['errorcode']!=1)
           {

            if($json['Header']!=null)
            {
             foreach ($json['Header'] as $key ) 
             {
               $data=array('PO_Number' =>$key['EBELN'] ,'Company_Code' =>$key['BUKRS'], 'Plant_Code' =>$plant ,'PO_Category' => $key['BSTYP'],'Vendor_Code' =>$key['LIFNR'] ,'Vendor_Name' =>$key['NAME1'] ,'PO_Org' =>$key['EKORG'],'PO_Group' => $key['EKGRP'],'Currency' => $key['WAERS']);
               DB::table('po_headers')->insert($data);
            }
           foreach ( $json ['Header_Items'] as $key ) 
            {
              $data=array('Vendor_Code'=>Auth::user()->VenderCode,'po_number' =>$key['EBELN'] ,'PO_Item_Code' =>$key['EBELP'] ,'Material_Code' => $key['MATNR'],
              'Material_description' =>$key['MAKTX'] ,'PO_QTY' =>$key['MENGE'] ,'PO_outstanding_Qty' =>$key['MENGE'],'UOM' => $key['MEINS']);
             DB::table('po_details')->insert($data);
            }

            if($json['Future_Items']!=null)
             {
              foreach ( $json ['Future_Items'] as $key ) 
              {
               $data=array('po_number' =>$key['EBELN'] ,'PO_Item_Code' =>$key['EBELP'] ,'Material_Code' => $key['MATNR'],
              'Material_description' =>$key['MAKTX'] ,'PO_QTY' =>$key['MENGE'] ,'PO_outstanding_Qty' =>$key['MENGE'],'UOM' => $key['MEINS'],'delevery_date'=>$key['EINDT']);
               DB::table('po_future_items')->insert($data);
             }
           }
         }
       }
       else
       {
         return back()->with('error',$json['message']);
       }
     }
    }

    public function get_plant_code(Request $request)
    {
         $plants = Plant::where('CompanyCode',$request->Comp_code)->get();
         $count = Plant::where('CompanyCode',$request->Comp_code)->count();
         return response()->json([ 'count' =>$count ,'plants' =>  $plants]);
      
    }
    public function get_vend_code()
    {
  
      return view('admin_view.login2');
    }
    public function get_delivery_schedule(Request $request)
    {
      
      


      $validatedData = $request->validate([
            
             'c_code' => 'required',
             'p_code' => 'required'
             
              ], 
              [ 
           
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            
            ]);

      // $vend_code=str_pad( Auth::user()->VenderCode, 10, '0', STR_PAD_LEFT);
       $vend_code=Auth::user()->VenderCode;
       $this->ins_data($vend_code,$request->c_code,$request->p_code,$request->po_no);
       $companies= Company::all();
        $wh_clarr=array();
       $po_headers = DB::table('po_headers');
  
        $po_headers=$po_headers->where('Vendor_Code',$vend_code);
        array_push($wh_clarr,array('Vendor_Code','=',$vend_code));
    
     
      if($request->has('c_code')  &&  !empty($request->c_code))
      {
        
       $po_headers=$po_headers->where('Company_Code',$request->c_code);
       array_push($wh_clarr,array('Company_Code','=',$request->c_code));
      }
       if($request->has('p_code') &&  !empty($request->p_code))
       {
       $po_headers=$po_headers->where('Plant_Code',$request->p_code);
       array_push($wh_clarr,array('Plant_Code','=',$request->p_code));
       }
      if($request->has('po_no')  &&  !empty($request->po_no))
      {
       $po_headers=$po_headers->where('PO_Number',$request->po_no);
       array_push($wh_clarr,array('PO_Number','=',$request->po_no));
      }
     
        $po_headers=$po_headers->whereDate('PO_Date',date('Y-m-d'));
      
      

       $request->session()->put('header_wh',$wh_clarr);
       $po_headers=$po_headers->get();
       $po_no=array();
       $i=0;
       foreach ($po_headers as $head_data) {
        array_push($po_no,$head_data->PO_Number);
         
       }
  
   


       $ccode=$request->c_code;
       $pcode=$request->p_code;
       $pono=$request->po_no;
     $request->session()->put('header_wh_dtl',$po_no);
     $po_details=PoDetail::whereIn('po_number',$po_no);
     $po_future_items=PoFutureItems::whereIn('po_number',$po_no);
     $po_details=$po_details->paginate();
     return view ('vendor_view.delevery_schedule',compact('po_headers','po_details','companies','po_future_items','ccode','pcode','pono'));
    }
     public function export_po_header() 
    {

     $wh= session('header_wh');

    $query = DB::table('po_headers')->select('*')->where($wh)->whereDate('PO_Date',date('Y-m-d'));
     $excel = Exporter::make('Excel');
      $excel->loadQuery($query);
       return $excel->stream('po_headers.xlsx');
    
       
    }
    public function export_po_detail()
    {
      $po_no= session('header_wh_dtl');
        $query = DB::table('po_details')->select('po_number','PO_Item_Code','Material_Code','Material_description','PO_QTY','PO_outstanding_Qty','UOM')->whereIn('po_number',$po_no);
   
      $excel = Exporter::make('Excel');
      $excel->loadQuery($query);
       return $excel->stream('po_details.xlsx');
       }
       public function get_qr_frm(Request $request)
       {

           if($request->isMethod('post'))
           {
         
          $request->session()->put('PO_gen_bar_code',$request->gen_qr);
          $po_headers= PoHeader::where('PO_Number',$request->gen_qr)->first();
          $po_details=PoDetail::where('po_number',$request->gen_qr)->get();

          return view ('vendor_view.gen_barcode',compact('po_details','po_headers'));
          }
        else
        {
           
          $po_no=session('PO_gen_bar_code');
          $po_headers= PoHeader::where('PO_Number',$po_no)->first();
          $po_details=PoDetail::where('po_number',$po_no)->get();
          $invoice_no=session('invoice_no');
           $bar_code_details=GenBcodeDetails::where('Invoice_No', $invoice_no)->get();
          //return back()->with('bar_code_details',$bar_code_details);
        return view ('vendor_view.gen_barcode',compact('po_details','po_headers','bar_code_details'))->with('bar_code_details',$bar_code_details);
        }
     }
     public function genMultiPoQr(Request $request)
     {
       if($request->isMethod('post'))
           {
          $request->session()->put('PO_gen_bar_code',$request->scheduled_po);
          $po_details=PoDetail::whereIn('po_number',explode(",",$request->scheduled_po))->get();
         return view ('vendor_view.gen_multipo_barcode',compact('po_details'));
       }
        else
        {
          $po_no=explode(",",session('PO_gen_bar_code'));
        
          $po_details=PoDetail::whereIn('po_number',$po_no)->get();
         return view ('vendor_view.gen_multipo_barcode',compact('po_details'));
        }

     }

       function get_material_data($po_no_arr,$po_arr,$disp_arr,$pack_arr)
       {
        
        $item_data="";$j=0;
        for($i=0;$i<count($disp_arr);$i++)
        {
               $disp_qty= $disp_arr[$i];
               $pack_qty=$pack_arr[$i]; 
               $po_item=$po_arr[$i];
               $po_no=$po_no_arr[$i];
                if($disp_qty!='' && $disp_qty!=0 && $pack_qty!='' && $pack_qty!=0 )
               { 
                if($j==0)
                {
                  $item_data=$po_no."/".$po_item."/".$pack_qty."/".$disp_qty; 
                }
               else
               {
                $item_data=$item_data.",".$po_no."/".$po_item."/".$pack_qty."/".$disp_qty; 
               }
                $j++;
               } 
        }
        return $item_data;
       }
       public  function is_in_array($array, $key, $key_value)
       {
      $within_array =array();
      foreach( $array as $arr)
      {
       
        if($arr->PO_Number==$key_value)
        {
           $within_array=['po_plant'=>$arr->Plant_Code,'c_code'=>$arr->Company_Code];
        }
      }
      return $within_array;
     }
        public function genQrCode(Request $request)
       {
            $method = $request->method();
          $vend_code= Auth::user()->VenderCode;
          $po_no=session('PO_gen_bar_code');
          $wh= session('header_wh');

        $po_h = DB::table('po_headers')->select('*')->where($wh)->whereDate('PO_Date',date('Y-m-d'))->get();
         if($request->isMethod('post'))
          {
             $validatedData = $request->validate([
             
             'invoice_no' => [
                'required',
                Rule::unique('gen_bcode_details')->where('VENDOR_Code',Auth::user()->VenderCode)],
    
             'invoice_date' => 'required',
             'lr_no' => 'required',
             'transporter'=> 'required',
              ], 
              [ 
            'invoice_no.required' => 'The invoice number field can not be blank value.',
            'invoice_no.unique' => '( "'.$request->invoice_no.' ") Invoice number is already taken',
            'invoice_date.required' => 'The invoice date field can not be blank value.',
            'lr_no.required' => 'The LR No. field can not be blank value.',
            ]);

             $arr=explode("/", $request->invoice_date);
             $invdate=$arr[2]."-".$arr[0]."-".$arr[1];
             $now=date('Y-m-d');
            for($i=0;$i<count($request->diapatch_qty);$i++)
             {
               $disp_qty= $request->diapatch_qty[$i];
               $pack_qty=$request->pack_qty[$i];
               if($disp_qty!='' && $disp_qty!=0 && $pack_qty!='' && $pack_qty!=0 )
               {
                $j=0;
                
                if($j==0)
                {
                   $header_part =  $vend_code."/".$request->invoice_no . "/".$request->invoice_date. "/" .$request->lr_no."/".$request->transporter;


          $item_part=$this->get_material_data($request->PO_No,$request->po_item,$request->diapatch_qty,$request->pack_qty);
                   $inv_data=$header_part.".".$item_part;

                   $invoice_qr_file=$request->invoice_no.strtotime($request->invoice_date).".png";
                   $pngInvImage = QrCode::format('png')
                        ->size(150)->errorCorrection('H')
                          ->generate($inv_data);
                  Storage::disk('InvoiceQrCode')->put($invoice_qr_file,$pngInvImage); 
                }
                   $img_name=$request->img[$i];
                   $Mat_Code=$request->Mat_Code[$i];
                   $Mat_descrip=$request->Mat_descrp[$i];
                   $PO_item=$request->po_item[$i];
                   $Mat_UOMS=$request->Mat_UOM[$i];
                   $PO_QTY=$request->PO_QTY[$i];
                   $tot_bar_code= ceil($disp_qty/$pack_qty);
                
                  for($j=1;$j<=$tot_bar_code;$j++)
                  {
                   if($disp_qty%$pack_qty!=0)
                   {
                    if($j==$tot_bar_code)
                    {
                      $temp=($pack_qty*($j-1));
                      $pack_qty=$disp_qty-$temp;
                    }
                   }

                    $b_img=$img_name."_$j.png";
                     $pngImage = QrCode::format('png')
                        ->size(100)->errorCorrection('H')
                          ->generate($img_name."/$pack_qty/$j");
                     Storage::disk('QrCode')->put($b_img,$pngImage);
                     $b_detail = new GenBcodeDetails;
                     $b_detail->VENDOR_Code = $vend_code;
                       $request->PO_No[$i];
                     $data= $this->is_in_array($po_h, "PO_Number", $request->PO_No[$i]);
                  
                     $b_detail->Company_Code =  $data['c_code'];
                     $b_detail->Plant_Code = $data['po_plant'];
                     $b_detail->Invoice_No =  $request->invoice_no;
                     $b_detail->Invoice_Date =  $invdate;
                     $b_detail->Entry_Date= $now;
                     $b_detail->LR_No =  $request->lr_no;
                     $b_detail->Transpoter_Name = $request->transporter;
                     $b_detail->PO_Number =  $request->PO_No[$i];
                     $b_detail->PO_Item =  $PO_item;
                     $b_detail->Material =$Mat_Code;
                     $b_detail->Material_Desc = $Mat_descrip;
                     $b_detail->UOM = $Mat_UOMS;
                     $b_detail->Quantity =$PO_QTY;
                     $b_detail->Dispatch_Qty = $disp_qty;
                     $b_detail->Packing_Qty =  $pack_qty;
                     $b_detail->Invoice_Bcode_Img_Name ="InvoiceQrCode/".$invoice_qr_file;
                     $b_detail->Barcode_Img_Name = "QrCode/".$b_img;
                     $b_detail->save();
                 }
                 $j++;
                }
            }
                /*  $po_headers=array();
            if($request->multiple_po=='1')
            {

                  //$po_headers=DB::table('po_headers')::whereRaw('PO_Number in ?',explode(",",$po_no))->get();
              $po_details = DB::table('po_details')->whereIn('po_number',explode(",",$po_no))->get();
             //  return  $po_details=DB::table('po_details')::whereRaw('po_number IN (? )',$po_no)->get();

           
            }
            else
            {
              $po_headers= PoHeader::where('PO_Number',$po_no)->first();
              $po_details=PoDetail::whereIn('po_number',$po_no)->get();
            }
            */
            $request->session()->put('invoice_no',$request->invoice_no);
             $bar_code_details=GenBcodeDetails::where('Invoice_No', $request->invoice_no)->get();
             $inv=$request->invoice_no;
             $dt=$request->invoice_date;
             $lr=$request->lr_no;
             $trans=$request->transporter;
              $disp= $request->diapatch_qty;
              $pack=$request->pack_qty;
              $request->session()->put('dispatch',$disp);
               $request->session()->put('packed',$pack);
       
            $bar_code_details=array();
            $request->session()->put('invoice_no',$request->invoice_no);
              $bar_code_details=GenBcodeDetails::where('Invoice_No', $request->invoice_no)->get();
          return back()->with("success","Barcode generated Successfully !")->with('bar_code_details',$bar_code_details)->with('inv',$inv)->with('dt',$dt)->with('lr',$lr)->with('trans',$trans)->with('disp',$disp)->with('pack',$pack);

            
         }
         

      }

       public function genMultiPOQrCode(Request $request)
       {
            $method = $request->method();
          $vend_code= Auth::user()->VenderCode;
          $po_no=session('PO_gen_bar_code');
          $wh= session('header_wh');

        $po_h = DB::table('po_headers')->select('*')->where($wh)->whereDate('PO_Date',date('Y-m-d'))->get();
         if($request->isMethod('post'))
          {
             $validatedData = $request->validate([
             
             'invoice_no' => [
                'required',
                Rule::unique('gen_bcode_details')->where('VENDOR_Code',Auth::user()->VenderCode)],
    
             'invoice_date' => 'required',
             'lr_no' => 'required',
             'transporter'=> 'required',
              ], 
              [ 
            'invoice_no.required' => 'The invoice number field can not be blank value.',
            'invoice_no.unique' => '( "'.$request->invoice_no.' ") Invoice number is already taken',
            'invoice_date.required' => 'The invoice date field can not be blank value.',
            'lr_no.required' => 'The LR No. field can not be blank value.',
            ]);

             $arr=explode("/", $request->invoice_date);
             $invdate=$arr[2]."-".$arr[0]."-".$arr[1];
             $now=date('Y-m-d');
            for($i=0;$i<count($request->diapatch_qty);$i++)
             {
               $disp_qty= $request->diapatch_qty[$i];
               $pack_qty=$request->pack_qty[$i];
               if($disp_qty!='' && $disp_qty!=0 && $pack_qty!='' && $pack_qty!=0 )
               {
                $j=0;
                
                if($j==0)
                {
                   $header_part =  $vend_code."/".$request->invoice_no . "/".$request->invoice_date. "/" .$request->lr_no."/".$request->transporter;


          $item_part=$this->get_material_data($request->PO_No,$request->po_item,$request->diapatch_qty,$request->pack_qty);
                   $inv_data=$header_part.".".$item_part;
                   $invoice_qr_file=$request->invoice_no.strtotime($request->invoice_date).".png";
                   $pngInvImage = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                          ->generate($inv_data);
                  Storage::disk('InvoiceQrCode')->put($invoice_qr_file,$pngInvImage); 
                }
                   $img_name=$request->img[$i];
                   $Mat_Code=$request->Mat_Code[$i];
                   $Mat_descrip=$request->Mat_descrp[$i];
                   $PO_item=$request->po_item[$i];
                   $Mat_UOMS=$request->Mat_UOM[$i];
                   $PO_QTY=$request->PO_QTY[$i];
                   $tot_bar_code= ceil($disp_qty/$pack_qty);
                
                  for($j=1;$j<=$tot_bar_code;$j++)
                  {
                   if($disp_qty%$pack_qty!=0)
                   {
                    if($j==$tot_bar_code)
                    {
                      $temp=($pack_qty*($j-1));
                      $pack_qty=$disp_qty-$temp;
                    }
                   }

                    $b_img=$img_name."_$j.png";
                     $pngImage = QrCode::format('png')
                        ->size(100)->errorCorrection('H')
                          ->generate($img_name."/$pack_qty/$j");
                     Storage::disk('QrCode')->put($b_img,$pngImage);
                     $b_detail = new GenBcodeDetails;
                     $b_detail->VENDOR_Code = $vend_code;
                       $request->PO_No[$i];
                     $data= $this->is_in_array($po_h, "PO_Number", $request->PO_No[$i]);
                  
                     $b_detail->Company_Code =  $data['c_code'];
                     $b_detail->Plant_Code = $data['po_plant'];
                     $b_detail->Invoice_No =  $request->invoice_no;
                     $b_detail->Invoice_Date =  $invdate;
                     $b_detail->Entry_Date= $now;
                     $b_detail->LR_No =  $request->lr_no;
                     $b_detail->Transpoter_Name = $request->transporter;
                     $b_detail->PO_Number =  $request->PO_No[$i];
                     $b_detail->PO_Item =  $PO_item;
                     $b_detail->Material =$Mat_Code;
                     $b_detail->Material_Desc = $Mat_descrip;
                     $b_detail->UOM = $Mat_UOMS;
                     $b_detail->Quantity =$PO_QTY;
                     $b_detail->Dispatch_Qty = $disp_qty;
                     $b_detail->Packing_Qty =  $pack_qty;
                     $b_detail->Invoice_Bcode_Img_Name ="InvoiceQrCode/".$invoice_qr_file;
                     $b_detail->Barcode_Img_Name = "QrCode/".$b_img;
                     $b_detail->save();
                 }
                 $j++;
                }
            }
               
            $request->session()->put('invoice_no',$request->invoice_no);
             $bar_code_details=GenBcodeDetails::where('Invoice_No', $request->invoice_no)->get();
             $inv=$request->invoice_no;
             $dt=$request->invoice_date;
             $lr=$request->lr_no;
             $trans=$request->transporter;
              $disp= $request->diapatch_qty;
              $pack=$request->pack_qty;
              $request->session()->put('dispatch',$disp);
               $request->session()->put('packed',$pack);
       
            $bar_code_details=array();
            $request->session()->put('invoice_no',$request->invoice_no);
              $bar_code_details=GenBcodeDetails::where('Invoice_No', $request->invoice_no)->get();
          return back()->with("success","Barcode generated Successfully !")->with('bar_code_details',$bar_code_details)->with('inv',$inv)->with('dt',$dt)->with('lr',$lr)->with('trans',$trans)->with('disp',$disp)->with('pack',$pack);

            
         }
         

      }
      public function is_available_invoice(Request $request)
      {
          $bar_code = GenBcodeDetails::where('Invoice_No', '=', $request->invoice_no)->where('VENDOR_Code',Auth::user()->VenderCode)->count();
           if ($bar_code > 0) 
           {
            return response()->json([ 'success' => false ,'invoice' => $request->invoice_no]);
           }
           else
           {
            return response()->json([ 'success' => true ,'invoice' => $request->invoice_no]);
           }
     
      }
        
   public function get_compleate_query($query_with_out_data,$query_data)
   {
       $sql_arr=explode("?", $query_with_out_data);
       $sql_with_data="";
       for($i=0;$i<count($query_data);$i++)
       {
       $sql_with_data=$sql_with_data." ".$sql_arr[$i]."'".$query_data[$i]."' ";
       }
       $sql_with_data=$sql_with_data." ".$sql_arr[$i];
       return  $sql_with_data;
    }

    public function reprint_barCode()
    {
       return view ('vendor_view.print_reprint_barcode');  
    }
    public function get_generated_bar_code(Request $request)
    {

      $vendor_code=Auth::user()->VenderCode;
       $invoice_barc="";
      $invoice_po="";
      $invoice_date="";
      if($request->type_of_get_bcode==1)
      {
         $validatedData = $request->validate([
             'invoice_barcode' => 'required',
             
              ], 
              [ 
            'invoice_barcode.required' => 'The invoice number field can not be blank value.',
            ]);
      $invoice_barc=$request->invoice_barcode;  
      $gen_bcode_details=GenBcodeDetails::where('Invoice_No', $request->invoice_barcode);
      }
      else
      {
         $gen_bcode_details = DB::table('gen_bcode_details');
    
      if($request->has('invoice_po')  &&  !empty($request->invoice_po))
      {
        $invoice_po=$request->invoice_po; 
       $gen_bcode_details=$gen_bcode_details->where('PO_Number',$request->invoice_po);
      }
       if($request->has('invoice_date') &&  !empty($request->invoice_date))
       {

        $invoice_date=$request->invoice_date;
        $d_arr=explode("/", $request->invoice_date);
        $inv_date=$d_arr[2]."-".$d_arr[0]."-".$d_arr[1];
        $gen_bcode_details=$gen_bcode_details->whereDate('Invoice_Date', $inv_date);
       }
         
      }
    $type_serach=$request->type_of_get_bcode;
    $gen_bcode_details=$gen_bcode_details->where('VENDOR_Code', $vendor_code)->get();
  //return $gen_bcode_details;

    return view ('vendor_view.print_reprint_barcode',compact('gen_bcode_details','type_serach','invoice_barc','invoice_po','invoice_date'));  
    }

      public function getCreditDebitFrm(Request $request)
    {
       $plants=Plant::all();
       $companies=Company::all();
       return view ('vendor_view.credit_debit_note',compact('plants','companies'));  
    }
    public function show_Credit_Debit_Note(Request $request)
    {
          $validatedData = $request->validate([
             'c_code' => 'required',
             'p_code' => 'required',
             'start_date' => 'required',
             'end_date'    => 'required|date|after_or_equal:start_date'
            
              ], 
              [ 
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            'start_date.required' => 'The start date filed is required !',
            'end_date.required' => 'The end date filed is required !',
            ]);
        $arr1=explode("/",$request->start_date);
        $arr2=explode("/",$request->end_date);
        $start=$arr1[2].$arr1[0].$arr1[1];
        $end=$arr2[2].$arr2[0].$arr2[1];

 

        $data="&plant_code=".$request->p_code."&from_date=".$start."&to_date=".$end;
         $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=credit_debit_note&rfc_name=ZVP_DC_NOTE".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $credit_debit_notes=array();
        if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
            // return $json;
             if($json['creditDebitNotes']!=null)
             {
            foreach ($json['creditDebitNotes'] as $key ) 
             {
          
               $dt2=$key['BUDAT_MKPF'];
               $postingDate=substr($dt2, 0, 4)."-".substr($dt2, 4, 2)."-".substr($dt2, 6, 2);
               if($key['BUDAT']!="00000000")
                 {
                
                   $dt3=$key['BUDAT'];
                 $postingDate=substr($dt3, 0, 4)."-".substr($dt3, 4, 2)."-".substr($dt3, 6, 2);
                 }
                 else
                 {
                 $QcDate="";
                 }
             $credit_debit_notes[]=array('MatDoc'=>$key['MBLNR'],'MatDocYr'=>$key['MJAHR'],'LineItem'=>$key['ZEILE'],'PostingDate'=>$postingDate,'MaterialCode'=>$key['MATNR'],'MaterialDesc'=>$key['MAKTX'],'Plant'=>$key['WERKS'],'VendorCode'=>$key['LIFNR'],'VendorName'=>$key['NAME1'],'Quantity'=>$key['MENGE'],'QuantityType'=>$key['QTYTYP'],'Invoice_No'=>$key['BELNR'],'InvDocYr'=>$key['GJAHR'],'Qty'=>$key['FKIMG'],'DeliveryNote'=>$key['XBLNR_MKPF'],'Rejected_Short_Qty'=>$key['REJ_SRT_QTY'],'created_at'=>new \DateTime(),'updated_at'=>new \DateTime());
               }
             $msg_type="success";
             $msg="Data Fetching Successfully !";
           }
           else
           {
            $msg_type="error";
            $msg="No any Data";
           }
          }
          else
          {
             $msg_type="error";
             $msg=$json['message'];
          }

        }
        else
        {
          $msg_type="error";
          $msg="Couldn't established connection !";
        }
        CreditDebitNote::truncate();
        $d=CreditDebitNote::insert($credit_debit_notes);
         $ccode=$request->c_code;
         $pcode=$request->p_code;
         $stdt=$request->start_date;
         $enddt=$request->end_date;
          $credit_debit_notes=CreditDebitNote::all();
         //$companies=Company::all();
   /*      return view ('vendor_view.credit_debit_note',compact('companies','credit_debit_notes','stdt','enddt','ccode','pcode'));*/
          return redirect()->route('vendor.get_Credit_Debit_Frm')
                  ->with($msg_type,$msg)->with('ccode',$ccode)->with('pcode',$pcode)->with('stdt',$stdt)->with('enddt',$enddt)->with('credit_debit_notes',$credit_debit_notes);

    }
  public function get_recon_stmt_frm(Request $request)
    {
       $plants=Plant::all();
       $companies=Company::all();
       return view ('vendor_view.reconStatement',compact('plants','companies'));  
    }

   public function generateReconStmt(Request $request)
    {
            $validatedData = $request->validate([
             'c_code' => 'required',
             'p_code' => 'required',
             'start_date' => 'required',
             'end_date'    => 'required|date|after_or_equal:start_date'
            
              ], 
              [ 
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            'start_date.required' => 'The start date filed is required !',
            'end_date.required' => 'The end date filed is required !',
            ]);
           
             $vend_code= Auth::user()->VenderCode;
           //  $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
             $arr1=explode("/",$request->start_date);
             $arr2=explode("/",$request->end_date);
             $start=$arr1[2].$arr1[0].$arr1[1];
             $end=$arr2[2].$arr2[0].$arr2[1];
    $data="&vencode=".$vend_code."&comp_code=".$request->c_code."&plant_code=".$request->p_code."&from_date=".$start."&to_date=".$end;
     $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=generateReconStmt&rfc_name=ZWMFM_VENDOR_STMNT".$data;

     try {
       $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $msg="";
        if($resp==200)
         {
            $data=$res->getBody();
          
           $json = json_decode($data, true);
           if($json['errorcode']==0)
           {
             $msg=$json['Msg']['MESSAGE'];
              if($json['Msg']['TYPE']=='S')
              {
                $msg_type="success";
               //return back()->with('success',$msg);
              }
             else
              {
                $msg_type="error";
              // return back()->with('error',$msg);
              }
           }
          else
          {
            $msg_type="error";
              $msg=$json['message'];
             // return back()->with('error',$msg);
          }
        }
         else
         {
          $msg_type="error";
            $msg="Api not responding";
             //return back()->with('error',$msg);
         }
   
} catch( ConnectException $ex ) 
{
$msg_type="error";
 $msg=$ex->getMessage();
    
}
      

           $companies=Company::all();
           $ccode=$request->c_code;
           $pcode=$request->p_code;
           $stdt=$request->start_date;
           $enddt=$request->end_date;
            return redirect()->route('vendor.get_recon_frm')
                  ->with($msg_type,$msg)->with('ccode',$ccode)->with('pcode',$pcode)->with('stdt',$stdt)->with('enddt',$enddt);
      // return view ('vendor_view.reconStatement',compact('companies','ccode','pcode','stdt','enddt'))->with($msg_type,$msg);  
    }
    public function getInvoiceStatusFrm()
    {
      // $plants=Plant::all();
       $companies=Company::all();
       return view ('vendor_view.invoiceStatus',compact('companies'));  
    }
 public function  show_invoice_status(Request $request)
    {

     
   $validatedData = $request->validate([
             
             'c_code' => 'required',
             'p_code' => 'required',
             'start_date' => 'required',
             
             'end_date'      => 'required|date|after_or_equal:start_date',
              ], 
              [ 
            
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            'start_date.required' => 'The start date filed is required !',
            'end_date.required' => 'The end date filed is required !',
            
            ]);
     $vend_code= Auth::user()->VenderCode;
  //  $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
    $arr1=explode("/",$request->start_date);
    $arr2=explode("/",$request->end_date);
    $start=$arr1[2].$arr1[0].$arr1[1];
    $end=$arr2[2].$arr2[0].$arr2[1];
 if($request->download_type!="pdf" && $request->download_type!="excel" && $request->download_type!="csv")
 {

   if($request->get_delvery_sch=="Show")
   {
   
        $data="&vencode=".$vend_code."&plant_code=".$request->p_code."&from_date=".$start."&to_date=".$end;
        $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=invoice_status&rfc_name=ZWMFM_INV_DATA".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $invoice_status=array();
        if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
            // return $json;
             if($json['invoice_status']!=null)
             {

            foreach ($json['invoice_status'] as $key ) 
             {
               $dt1=$key['BLDAT'];
                $invDate=date('d-M-Y',strtotime(substr($dt1, 6, 2).".".substr($dt1, 4, 2).".".substr($dt1, 0, 4)));
               $dt2=$key['BUDAT_MKPF'];
               $grnDate=date('d-M-Y',strtotime(substr($dt2, 6, 2).".".substr($dt2, 4, 2).".".substr($dt2, 0, 4)));
               if($key['PAENDTERM']!="00000000")
                 {
                   $dt3=$key['BUDAT_MKPF'];
                 $QcDate=substr($dt3, 6, 2).".".substr($dt3, 4, 2).".".substr($dt3, 0, 4);
                 }
                 else
                 {
                 $QcDate="";
                 }
             $invoice_status[]=array('vendCode'=>$key['LIFNR'],'vendName'=>$key['NAME1'],'plant'=>$key['WERKS'],'invoiceNo'=>$key['XBLNR'],'invoiceDate'=>$invDate,'grnNo'=>$key['MBLNR'],'grnLineItem'=>$key['ZEILE'],'grnDate'=>$grnDate,'partCode'=>$key['MATNR'],'partDescription'=>$key['MAKTX'],'quantity'=>$key['MENGE'],'UOM'=>$key['MEINS'],'QcLotNum'=>$key['BUDAT'],'QcDate'=>$QcDate,'QcAcceptedQty'=>$key['LMENGE01'],'QcRejectedQty'=>$key['LMENGE04']);
             }
             $msg_type="success";
             $msg="Data Fetching Successfully !";
           }
           else
           {
            $msg_type="error";
            $msg="No any Data";
           }
          }
          else
          {
             $msg_type="error";
             $msg=$json['message'];
          }

        }

        else
        {
          $msg_type="error";
          $msg="Couldn't established connection !";
        }
         $ccode=$request->c_code;
         $pcode=$request->p_code;
         $stdt=$request->start_date;
         $enddt=$request->end_date;

         //$companies=Company::all();
        return redirect()->route('vendor.get_Invoice_Status_Frm')->with($msg_type,$msg)->with('ccode',$ccode)->with('pcode',$pcode)->with('stdt',$stdt)->with('enddt',$enddt)->with('invoice_status',$invoice_status);
        // return view ('vendor_view.invoiceStatus',compact('companies','invoice_status','stdt','enddt','ccode','pcode'));
       }
    if($request->get_delvery_sch=="Send Mail")
      {
        $data="&vencode=".$vend_code."&plant_code=".$request->p_code."&from_date=".$start."&to_date=".$end;
         $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=Send_invoice_mail&rfc_name=ZWMFM_INV_DATA".$data;
        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $msg="";
        if($resp==200)
         {
          $data=$res->getBody();
 
           $json = json_decode($data, true);
           if($json['errorcode']==0)
           {
             $msg=$json['Msg']['MESSAGE'];
             if($json['Msg']['TYPE']=='S')
             {
               
                return back()->with('success',$msg);
              }
            else
             {
              return back()->with('error',$msg);
             }
           }
          else
          {
              $msg=$json['message'];
             return back()->with('error',$msg);
          }

         }
         else
         {
            $msg="Api not responding";
             return back()->with('error',$msg);
         }
       }

        }
        else
        {
          $data="&vencode=".$vend_code."&plant_code=".$request->p_code."&from_date=".$start."&to_date=".$end;
         $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=invoice_status&rfc_name=ZWMFM_INV_DATA".$data;
        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $invoice_status=array();
        if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
            // return $json;
             if($json['invoice_status']!=null)
             {
            foreach ($json['invoice_status'] as $key ) 
             {
               $dt1=$key['BLDAT'];
               $invDate=substr($dt1, 6, 2).".".substr($dt1, 4, 2).".".substr($dt1, 0, 4);
               $dt2=$key['BUDAT_MKPF'];
               $grnDate=substr($dt2, 6, 2).".".substr($dt2, 4, 2).".".substr($dt2, 0, 4);
               if($key['PAENDTERM']!="00000000")
                 {
                   $dt3=$key['BUDAT_MKPF'];
                 $QcDate=substr($dt3, 6, 2).".".substr($dt3, 4, 2).".".substr($dt3, 0, 4);
                 }
                 else
                 {
                 $QcDate="";
                 }
             $invoice_status[]=array('vendCode'=>$key['LIFNR'],'vendName'=>$key['NAME1'],'plant'=>$key['WERKS'],'invoiceNo'=>$key['XBLNR'],'invoiceDate'=>$invDate,'grnNo'=>$key['MBLNR'],'grnLineItem'=>$key['ZEILE'],'grnDate'=>$grnDate,'partCode'=>$key['MATNR'],'partDescription'=>$key['MAKTX'],'quantity'=>$key['MENGE'],'UOM'=>$key['MEINS'],'QcLotNum'=>$key['BUDAT'],'QcDate'=>$QcDate,'QcAcceptedQty'=>$key['LMENGE01'],'QcRejectedQty'=>$key['LMENGE04']);
             }
           }
           
          }
         }

        }
     if($request->download_type=="pdf")
         {
          
         $invoice_status = ['invoice_status' => $invoice_status];
         $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_invoice_status', $invoice_status); 
          return $pdf->download('invoice_status.pdf');
        } 


     if($request->download_type=="excel")
      {
         Excel::loadView('vendor_view.pdfview.pdf_view_invoice_status', $invoice_status)->export('xls');

        
      }
if($request->download_type=="csv")
{
   $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=invoice_status.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );

    
    $columns = array('Vendor code', 'Vendor Name', 'Plant','Invoice no', 'Invoice Date', 'GRN No', 'GRN Line item', 'GRN Date', 'Part Code', 'Part Description', 'Quantity','UOM','QC Lot Num','QC Date','QC Accepted Qty','QC Rejected Qty');

    $callback = function() use ($invoice_status, $columns)
    {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach($invoice_status as $invoice) {
            fputcsv($file, array($invoice['vendCode'],$invoice['vendName'],$invoice['plant'], $invoice['invoiceNo'],$invoice['invoiceDate'], $invoice['grnNo'], $invoice['grnLineItem'], $invoice['grnDate'], $invoice['partCode'], $invoice['partDescription'],$invoice['quantity'],$invoice['UOM'],$invoice['QcLotNum'],$invoice['QcDate'],$invoice['QcAcceptedQty'],$invoice['QcRejectedQty']));
        }
        fclose($file);
    };
    return Response::stream($callback, 200, $headers);
}


    }
   public function getMaterialReturnFrm()
    {
       
       $companies=Company::all();
       return view ('vendor_view.MaterialReturn',compact('companies'));  
    }
     public function getMaterialReturnData(Request $request)
    {
       $validatedData = $request->validate([
            
             'c_code' => 'required',
             'p_code' => 'required',
             'start_date' => 'required',
             'end_date'      => 'required|date|after_or_equal:start_date',
             'year'=>'required'
              ], 
              [ 
            
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            'start_date.required' => 'The start date filed is required !',
            'end_date.required' => 'The end date filed is required !',
             'year.required' => 'The year filed is required !',
            ]);


    //$vend_code=str_pad(Auth::user()->VenderCode, 10, '0', STR_PAD_LEFT);
    $vend_code=Auth::user()->VenderCode;
    $arr1=explode("/",$request->start_date);
    $arr2=explode("/",$request->end_date);
    $start=$arr1[2].$arr1[0].$arr1[1];
    $end=$arr2[2].$arr2[0].$arr2[1];

    $data="&vencode=".$vend_code."&plant_code=".$request->p_code."&year=".$request->year."&from_date=".$start."&to_date=".$end;
      $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=material_return&rfc_name=ZWMFM_PUR_RETURN".$data;
  
        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $material_returns=array();

       if($resp==200)
        {

          $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
          return $json['Material_Return'] ;
            if($json['Material_Return']!=null)
            {
             foreach ($json['Material_Return'] as $key ) 
                {
               $dt1=$key['EXDAT'];
                $docDate=substr($dt1, 6, 2).".".substr($dt1, 4, 2).".".substr($dt1, 0, 4);
                $dt2=$key['BUDAT'];
                $postingDate=substr($dt2, 6, 2).".".substr($dt2, 4, 2).".".substr($dt2, 0, 4);
                $material_returns[]=array('VendorInvNo'=>$key['EXNUM'],'docDate'=>$docDate,'VendorCode'=>
                          $key['LIFNR'],'VendorName'=>$key['LIF_NM'],'HSNCode'=>$key['CHAPID'],'Material'=>
                          $key['MATNR'],'Description'=>$key['MAKTX'],'Quantity'=>$key['MENGE'],
                          'UnitPrice'=>$key['EXBAS'],'InvBasicAmt'=>$key['EXBED'],'AccountingDocNo'=>$key['FAWREF'],'PostingDate'=>$postingDate,'CGSTPer'=>$key['EXCPER'],'SGSTPer'=>$key['VATPER'],
                        'IGSTPer'=>$key['CSTPER'],'CGSTValue'=>$key['VAT'],'SGSTValue'=>$key['CST'],'IGSTValue'=>$key['GROSS_AMT'],'GRNNo'=>$key['RDOC'],'FiscalYear'=>$key['RYEAR'],'PONumber'=>
                        $key['RDOC1'],'LineItemNo'=>$key['RITEM1'],'Plant'=>$key['WERKS'],
                        'TaxCode'=>$key['MWSKZ']);
                   
                  }
                    $msg_type="success";
                   $msg="Fetch Data Successfully !";
            }
             else
             {
             
               $msg="No any data";
               return back()->with('error',$msg);
             }
             
          }
          else
          {
             $msg=$json['message'];
             return back()->with('error',$msg);
          }
        }
        else
         {
              $msg="Api not responding";
             return back()->with('error',$msg);
         }
       // return $material_returns;
         if($request->download_type!="pdf" && $request->download_type!="excel" && 
      $request->download_type!="csv")
        {
           $ccode=$request->c_code;
           $pcode=$request->p_code;
           $stdt=$request->start_date;
           $enddt=$request->end_date;
           $yr=$request->year;
        return redirect()->route('vendor.get_Material_Return_Frm')->with($msg_type,$msg)->with('ccde',$ccode)->with('pcode',$pcode)->with('stdt',$stdt)->with('enddt',$enddt)->with('yr',$yr)->with('material_returns',$material_returns); 
      }
      else
      {
          if($request->download_type=="pdf")
         {
          
          // return view ('vendor_view.pdfview.pdf_view_material_return',compact('material_returns')); 
         $material_returns = ['material_returns' => $material_returns];
         $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_material_return', $material_returns);
         $pdf->setPaper('A4','portrait'); 
          return $pdf->download('material_return.pdf');
         }
        if($request->download_type=="csv")
         {
            $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=material_returns.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0");
    
          $columns = array('Vendor Inv. No', 'Doc Date', 'Vendor Code','Vendor Name','HSN Code','Material','Description','Quantity','Unit Price','Inv. Basic Amt.','Accounting Doc. No.','Posting Date','CGST(%)','SGST (%)','IGST (%)','CGST Value','IGST Value','GRN No.','Fiscal Year','PO Number','Line Item No.','Plant','Tax Code');
          $callback = function() use ($material_returns, $columns)
          {
             $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach($material_returns as $material) 
            {
              fputcsv($file, array($material['VendorInvNo'],$material['docDate'],$material['VendorCode'], $material['VendorName'],$material['HSNCode'],$material['Material'],$material['Description'],$material['Description'],$material['Quantity'],$material['UnitPrice'],$material['InvBasicAmt'],$material['AccountingDocNo'],$material['PostingDate'],$material['PostingDate'],$material['CGSTPer'],$material['SGSTPer'],$material['IGSTPer'],$material['CGSTValue'],$material['SGSTValue'],$material['IGSTValue'],$material['GRNNo'],$material['FiscalYear'], $material['PONumber'],$material['LineItemNo'],$material['Plant'],$material['TaxCode']));
            }
          fclose($file);
        };
         return Response::stream($callback, 200, $headers);
      }

      }
        

    }

    public function getLRUpdateFrm()
    {
       return view ('vendor_view.LR_Update');  
    }
    public function getPaymentAdviceFrm()
    {
       $companies=Company::all();
     return view ('vendor_view.Payment_Advice',compact('companies'));  
    }
  public function payment_advice_details(Request $request)
    {
       $validatedData = $request->validate([
            
             'c_code' => 'required',
             'start_date' => 'required',
             'end_date'      => 'required|date|after_or_equal:start_date'
             ], 
              [ 
           
            'c_code.required' => 'The company Code field is required !',
            'start_date.required' => 'The start date filed is required !',
            'end_date.required' => 'The end date filed is required !'
            
            ]);  
     //   $vend_code=str_pad(Auth::user()->VenderCode, 10, '0', STR_PAD_LEFT);
         $vend_code=Auth::user()->VenderCode;
        $arr1=explode("/",$request->start_date);
        $arr2=explode("/",$request->end_date);
        $start=$arr1[2].$arr1[0].$arr1[1];
        $end=$arr2[2].$arr2[0].$arr2[1];
        $data="&vencode=".$vend_code."&comp_code=".$request->c_code."&from_date=".$start."&to_date=".$end;
           $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=payment_advice&rfc_name=ZWFM_FBL1N_OUTPUT".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $payment_advice=array();
         if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
           if($json['Payment_Advice']!=null)
            {
              foreach ($json['Payment_Advice'] as $key ) 
                {
                  $dt1=$key['AUGDT'];
                  $clr_dt=date('d-M-Y',strtotime(substr($dt1, 6, 2)."-".substr($dt1, 4, 2)."-".substr($dt1, 0, 4)));
                  $payment_advice[]=array('ClearingAugDate'=>$clr_dt,'Amount'=>$key['DMSHB'],'Currency'=>
                          $key['HWAER'],'DocumentNo'=>$key['BELNR']);
                }
                $msg_type="success";
               $msg="Fetch Data Successfully !";

           }
           else
           {
            $msg_type="error";
            $msg="No any data";
           }
          }
          else
          {
              $msg=$json['message'];
             return back()->with('error',$msg);
          }
        }
        else
         {
            $msg="Api not responding";
             return back()->with('error',$msg);
         }
    if($request->download_type!="pdf" && $request->download_type!="excel" && 
      $request->download_type!="csv")
        {
           
           $ccode=$request->c_code;
           $pcode=$request->p_code;
           $stdt=$request->start_date;
           $enddt=$request->end_date;
           $yr=$request->year;
           return redirect()->route('vendor.get_Payment_Advice_Frm')->with($msg_type,$msg)->with('ccode',$ccode)->with('pcode',$pcode)->with('stdt',$stdt)->with('enddt',$enddt)->with('payment_advice',$payment_advice); 
       }
        else
       {
        if($request->download_type=="pdf")
         {
          
         $payment_advice = ['payment_advice' => $payment_advice];
         $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_payment_advice', $payment_advice); 
          return $pdf->download('payment_advice.pdf');
         }
        if($request->download_type=="csv")
         {
            $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=payment_advice.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0");
          $columns = array('Clearing Aug Date', 'Amount', 'Currency','Document No');
          $callback = function() use ($payment_advice, $columns)
          {
             $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach($payment_advice as $advice) 
            {
              fputcsv($file, array($advice['ClearingAugDate'],$advice['Amount'],$advice['Currency'], $advice['DocumentNo']));
            }
          fclose($file);
        };
         return Response::stream($callback, 200, $headers);
      }
    }
}

    function GivePaymentAdvice(Request $request)
     {
         
       $validatedData = $request->validate([
             'vendorCode' => 'required',
             'company' => 'required',
             'from_date' => 'required',
             'documentno' => 'required'
             ], 
              [ 
            'vendorCode.required' => 'The Vendor Code parameter is required ,Please contact to admin !',
            'company.required' => 'The company Code parameter is required !',
            'from_date.required' => 'The from date parameter is required !',
            'documentno.required' => 'The document number parameter is required !'
            
            ]);  

        //$vend_code=str_pad($request->vendorCode, 10, '0', STR_PAD_LEFT);
        $vend_code=$request->vendorCode;
        $data="&vencode=".$vend_code."&comp_code=".$request->company."&from_date=".$request->from_date.
        "&document=".$request->documentno;
       $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=give_payment_advice&rfc_name=ZWFM_ZADVICE_EMAIL".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $msg="";
        if($resp==200)
         {
          $data=$res->getBody();
 
           $json = json_decode($data, true);
           if($json['errorcode']==0)
           {
             $msg=$json['Msg']['MESSAGE'];
             if($json['Msg']['TYPE']=='S')
             {
               
                return back()->with('success',$msg);
              }
            else
             {
              return back()->with('error',$msg);
             }
           }
          else
          {
              $msg=$json['message'];
             return back()->with('error',$msg);
          }

         }
         else
         {
            $msg="Api not responding";
             return back()->with('error',$msg);
         }

     }

    public function getDeliveryDelayFrm()
     {
       $plants=Plant::all();
       $companies=Company::all();
       
     return view ('vendor_view.Delivery_Delay_Info',compact('companies','plants'));  
    }
    public function getPo(Request $request)
    {
       $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=getPoNumber&rfc_name=ZWMFM_PO_DETAIL&vencode=0900300302&comp_code=AM10&plant_code=1005";
       $vend_code=Auth::user()->VenderCode;
        //$vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
      
        $data="&vencode=".$vend_code."&comp_code=".$request->c_code."&plant_code=".$request->p_code;
          $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=getPoNumber&rfc_name=ZWMFM_PO_DETAIL".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $all_po=array();

         if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
           if($json['all_po']!=null)
            {
              foreach ($json['all_po'] as $key ) 
                {
                  $all_po[]=array('po_no'=>$key['EBELN']);
                }
           }
          }
          else
          {
              $msg=$json['message'];
             return back()->with('error',$msg);
          }
        }
        else
         {
            $msg="Api not responding";
             return back()->with('error',$msg);
         }
        $len= count($all_po);
   return response()->json(['count'=>$len,'po_numbers' =>  $all_po]);
     
    }
    public function getMatDescp(Request $request)
    {

       $vend_code=Auth::user()->VenderCode;
       $po=$request->po_no;
       // $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
      
        $data="&vencode=".$vend_code."&comp_code=".$request->c_code."&plant_code=".$request->p_code;
          $url="http://192.168.1.64:8085/SAP-TO-PHP/rfc.php?function=getMaterialDscrp&rfc_name=ZWMFM_PO_DETAIL".$data;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);
        $resp=$res->getStatusCode(); // 200
        $mat_descrp=array();

         if($resp==200)
         {
           $data=$res->getBody();
          $json = json_decode($data, true);
          if($json['errorcode']==0)
          {
           if($json['all_po_item']!=null)
            {
              foreach ($json['all_po_item'] as $key ) 
                {
                  if($po==$key['EBELN'])
                  {
                    $mat_descrp[]=array('material_no'=>$key['MATNR'],'material_descrp'=>$key['MAKTX']);
                  }
                  
                }
           }
          }
          else
          {
              $msg=$json['message'];
             return back()->with('error',$msg);
          }
        }
        else
         {
            $msg="Api not responding";
             return back()->with('error',$msg);
         }
        $len= count($mat_descrp);
   return response()->json(['count'=>$len,'description' =>  $mat_descrp]);
     
    }
    public function postDeliveryDelayFrm(Request $request)
    {
       $this->validate($request, [
        'c_code'=> 'required',
        'p_code'     => 'required',
        'b_type' => 'required',
        'suplier_name'=> 'required',
        'start_date'     => 'required',
        'end_date'      => 'required|date|after_or_equal:start_date',
        'pur_order_no'=> 'required',
        'suplier_cont_name'     => 'required',
        'mat_code_descrp' => 'required',
        'qty_at_sup'=> 'required',
        'reason'     => 'required',
        ]
        , 
              [ 
            'c_code.required' => 'The Company Code filed is required !',
            'p_code.required' => 'The Plant Code filed is required !',
            'b_type.required' => 'The Breakdown type filed is required !',
            'suplier_name.required' => 'The Suplier name filed is required !',
            'start_date.required' => 'The From date filed is required !',
            'end_date.required' => 'The To date filed is required !',
            'pur_order_no.required' => 'The purchase order number filed is required !',
            'suplier_cont_name.required' => 'The suplier contact person name filed is required !',
            'mat_code_descrp.required' => 'The Material code description filed is required !',
            'qty_at_sup.required' => 'The Aval. qty at supplier end. filed is required !',
            'reason.required' => 'The Reason field is required !',
            ]

      );
       //08/03/2019
       $d_arr1=explode("/", $request->start_date);
       $d_arr2=explode("/", $request->end_date);
       $date1= $d_arr1[2]."-".$d_arr1[0]."-".$d_arr1[1];
       $date2= $d_arr2[2]."-".$d_arr2[0]."-".$d_arr2[1];

        $ven_code=Auth::user()->VenderCode;
        $delay = new DeliveryDelayInfo();
        $delay->Company_Code = $request->c_code;
        $delay->Plant_Code = $request->p_code;
        $delay->Vendor_Code = $ven_code;
        $delay->Supplier_Name = $request->suplier_name;
        $delay->From_Date = $date1;
        $delay->To_Date =$date2;
        $delay->PO_Number = $request->pur_order_no;
        $delay->Supplier_Cont_Person = $request->suplier_cont_name;
        $delay->Material_Code_Desc = $request->mat_code_descrp;
        $delay->Avl_Qty = $request->qty_at_sup;
        $delay->Reason = $request->reason;
        $delay->Entry_Date = date('Y-m-d');
        $delay->save();
/*$data=array('Company_Code' =>$request->c_code ,'Plant_Code' =>$request->p_code, 'Vendor_Code' =>$ven_code ,'Supplier_Name' => $request->suplier_name,'From_Date' =>$request->start_date ,'To_Date' =>$request->end_date ,'PO_Number' =>$request->pur_order_no,'Supplier_Cont_Person' => $request->suplier_cont_name,'Material_Code_Desc' => $request->mat_code_descrp,'Avl_Qty' => $request->qty_at_sup,'Reason' => $request->reason,'Entry_Date'=>data('Y-m-d'));
               DB::table('delivery_delay_infos')->insert($data);*/
return back() ->with('success','Delivery Delay Information Added Successfully');
    }
    public function getChangePassFrm()
    {
     return view ('vendor_view.change_pass');  
    }
   public function updatePassword(Request $request)
   {
      $this->validate($request, [
        'old_password'=> 'required',
        'new_password'     => 'required|min:4',
        'confirm_password' => 'required|same:new_password'
      ]);

   $vendor_code=Auth::user()->VenderCode;
   $old_pass=Hash::make($request->old_password);
   $credential=array('VenderCode' => $vendor_code, 'password' => $request->old_password);
 if( Auth::attempt($credential))
 {
    $pass=Hash::make($request->new_password);
          DB::table('users')
            ->where('VenderCode',$vendor_code)
            ->update(['WebPassword' =>  $pass]);
             return redirect()->route('vendor.get_Change_Pass_Frm')
                        ->with('success','Your password has been changed');
   }
  else
   {
return back() ->with('error','The specified password does not match the database password');
  }
   
   }
     public function v_policy()
    {
       $last = DB::table('v_policies')->where('status','=',1)->orderBy('id', 'DESC')->first();
       if(!empty($last))
       {
         $pathToFile="vendorPolicy/". $last->policy;
         return view('vendor_view.vendor_policy')->with("vendor_policy", $pathToFile);
       } 
       else
       {
        $pathToFile="vendorPolicy/Default.pdf";
         return view('vendor_view.vendor_policy')->with("vendor_policy", $pathToFile);
       }
    }
public function askQueryFrm(Request $request)
{
   $vendor_code=Auth::user()->VenderCode;
    $faqs=FAQs::where("vendorCode",$vendor_code)->get();

   return view ('vendor_view.faq',compact('faqs'));  
}
public function saveVendorQuery(Request $request)
{
  $this->validate($request, [
        'topic'=> 'required',
        'subject'     => 'required',
        'message' => 'required'
      ]);
$ven_code=Auth::user()->VenderCode;
        $faq = new FAQs();
        $faq->topic = $request->topic;
        $faq->subject = $request->subject;
        $faq->message = $request->message;
        $faq->vendorCode = $ven_code;
        $faq->queryDate = date('Y-m-d');
        $faq->save();
 return redirect()->route('vendor.get_ask_Query_Frm')
                        ->with('success','Query has been added Successfully !');
}
}
