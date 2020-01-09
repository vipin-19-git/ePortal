<?php
namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use App\User;
use Redirect;
use DB;
use App\VendorRegistration;
use Clockwork;
use Session;
use PDF;
use App\FAQs; 
use View; 
use App\Notifications;  
use App\VPolicy;
use PHPMailer;
use File;
/* reference the Dompdf namespace */
use Dompdf\Dompdf;
use Exporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Company;use App\DeliveryDelayInfo;use App\Countries;use App\States;
use App\Cities;use App\vendorContacts;use App\vendorReferances;use App\MailStatus;
use App\vendorCertifications;use App\vendorCapcityDtls;
use App\GenBcodeDetails;use Importer; use App\Business_types;
use App\PoHeader;use App\PoDetail;use App\PoFutureItems;use Response;
use App\ForgetPassword;
class AdminController extends BaseController 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view('vendor_view.login');
    }
    public function index2()
    {
        return view('admin_view.login');
    }
    public function forgotpass()
    {
       return view('admin_view.forgotpass');

    }
    public function sendresetlink(Request $request)
    {
      if($request->is_update_Process==2)
      {

     $this->validate($request, [
         'UserName'=> 'required'
         ],
       [
      'UserName.required'=>"UserName field is required",
     
      ]);
        $user=User::where('VenderCode',$request->UserName)->first();
         $token=Str::random(32);
          $VenderCode=$user->VenderCode;
           $tokens=Str::random(32);
           $token=base64_encode($tokens);
           $VenderCode=base64_encode($VenderCode);
          
          $html = View::make('admin_view.resetpassword')->render();
          $link=url("resetpassword/$VenderCode/$token");
         
          $message=str_replace("##LINK", $link,$html);
          $subject="Forgot password !";
           $mail = new PHPMailer\PHPMailer\PHPMailer();
       try {
             
        $mail->isSMTP();   
        $mail->Host = "smtp.rediffmailpro.com"; 
        $mail->SMTPAuth = true; 

        $mail->Username = "vipin.kumar@mawaimail.com";                
        $mail->Password = "vipin#";   
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587; 

        $mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )); 
          
           $mail->setFrom("noreply@mawaimail.com", "Supplier ePortal");
           $mail->Subject =  $subject;
           $mail->MsgHTML($message);
           $mail->addAddress($user->Email);
          
           
           if(!$mail->send())
           {
            return back()->with('error',"Mail has not been sent Please try agian !");
           }
         else
         {
         
      $forget=new ForgetPassword();
      $forget->vendorCode=$user->VenderCode;
      $forget->email=$user->Email;
      $forget->token=$tokens;
     
      $forget->save();
     return back()->with('success',"Link has been sent to your mail id ($user->Email) !");

         }

  

         } catch (phpmailerException $e) {
          return back()->with('error',"Mail has not been sent Please try agian !");
          } catch (Exception $e) {
            return back()->with('error',"Mail has not been sent Please try agian !");
          }
     
      }
   else
   {

  $this->validate($request, [
         'new_password'     => 'required',
         'confirm_password' => 'required|same:new_password'
    ],
    [
   
    'new_password.required'=>'The Password field is required.',
    'confirm_password.required'=>'The Confirm Password field is required.',
     ]);
      $vendor_code=$request->VenderCode;
      $pass=Hash::make($request->new_password);
      $user=User::where('VenderCode',$vendor_code)->first();
      $is_admin=$user->IsHoAdmin;
      $user->WebPassword=$pass;

      $user->save();
    return back()->with('is_admin',$is_admin)
                  ->with('success',"Your password has been changed . Please login !");
      
   }

    }

    public function resetPassword(Request $request)
    {
     
       $access_token=base64_decode($request->token);
       $vendor_code=base64_decode($request->vendorcode);
        $forget=ForgetPassword::where('vendorCode',$vendor_code)->where('token',$access_token);
        $count=$forget->count();
       if($count<1)
       { 
         $user=User::where('VenderCode', $vendor_code)->first();
        if($user->IsHoAdmin==1)
        {
             return redirect()->route('admin_login')->withErrors("Link has been expired. Please click below for forget password again !");
        }
        else
        {
         return redirect()->route('login')->withErrors("Link has been expired. Please click below for forget password again !");
        }
     
       }
       
       else
       {
         $forget->delete();
         return redirect()->route('forGetPassword')->with('VenderCode',$vendor_code);
       }

    }
    public function admin_login(Request $request)
   {
        $user=$request->get ('UserName');
        $password= $request->get('WebPassword');
        $credential=array('VenderCode' => $user, 'password' => $password);
     
       if (Auth::attempt($credential))
        {
            $user=User::where('VenderCode',$user)->first();
          
            if($user->is_admin())
            {

             return redirect()->route("admin.dash");
            }   
            else
            {
       
              return back()->withErrors('Invalid Login Credential !');
            }
      
        }
         
       else
       {
      return back()->withErrors('Invalid Login Credential !');
      
       }
   }
   
   public function vendor_login(Request $request)
   {
        $user=$request->get ('UserName');
        $password= $request->get('WebPassword');
        $credential=array('VenderCode' => $user, 'password' => $password);
      if(Auth::attempt($credential))
        {
            $user=User::where('VenderCode',$user)->first();
            if(!$user->is_admin())
            {
              return redirect()->route("vendor.home");  
            }   
            else
            {
       
              return back()->withErrors('Invalid Login Credential !');
            }
      
        }
         
       else
       {
      return back()->withErrors('Invalid Login Credential !');
      
       }
   }
   
    public function showPendingRegReq()
    {
         $vendors= VendorRegistration::with('BusinessType','City','State','Country')
               ->where('vendor_status_by_admin','Pending')->get();
       return view('admin_view.dash_pending_reg', compact('vendors'));
    }
    public function showHoldRegReq()
    {
         $vendors= VendorRegistration::with('BusinessType','City','State','Country')
               ->where('vendor_status_by_admin','Hold')->get();
       return view('admin_view.dash_hold_reg', compact('vendors'));
    }
   public function showPendingQuery()
    {
      
     $faqs= DB::table('faqs')
       ->join('users', 'faqs.vendorCode', '=', 'users.VenderCode')
        ->select('faqs.*','users.UserName')->where('status','open')->get();
         
         return view('admin_view.dash_pending_query', compact('faqs'));
    }
    public function showActiveNotification()
    {
       $now=date('Y-m-d');
      $notifications=Notifications::whereDate('valied_upTo','>=',$now)->get();
       return view('admin_view.dash_active_notification', compact('notifications'));
    }
   public function admin_dash()
   {
    /* $vendor_faqs = DB::table('faqs')
                ->join('users','faqs.vendorCode', '=', 'users.VenderCode')
                 ->select('users.UserName', DB::raw('count(*) as total'))
                 ->where('faqs.status','open')
                 ->groupBy('vendorCode')
                 ->get();
                return $vendor_faqs;*/
      $now=date('Y-m-d');
      $nots=Notifications::whereDate('valied_upTo','>=',$now);
      $no_of_notification=$nots->count();
      $no_new_req= VendorRegistration::where('vendor_status_by_admin','Pending')->count();
      $no_hold_req= VendorRegistration::where('vendor_status_by_admin','Hold')->count();
     return view('admin_view.dashboard', compact('no_of_notification','no_new_req','no_hold_req'));
   }
   public function showVendorQuery(Request $request)
   {
    

      $faqs=DB::table("faqs")->join('users','faqs.vendorCode','=','users.VenderCode')->select('users.UserName','faqs.*')
         ->paginate(10);
      return view('admin_view.show_vendorQuery', compact('faqs')); 

    
   }
      public function showVendorWiseQuery(Request $request)
   {
    

      $faqs=DB::table("faqs")->join('users','faqs.vendorCode','=','users.VenderCode')->select('users.UserName','faqs.*')
          ->where('faqs.vendorCode',$request->Vendor)->where('faqs.status','open')
         ->paginate(10);
      return view('admin_view.vendorWiseFaq', compact('faqs')); 

    
   }
   public function get_queryData(Request $request)
   {
    $faqs=DB::table("faqs")->join('users','faqs.vendorCode','=','users.VenderCode')->select('users.UserName','faqs.*')
         ->where('faqs.id', $request->faq_id)->first();
     return Response::json($faqs, 200, [], JSON_NUMERIC_CHECK);
   }
   public function vendorQueryAns(Request $request)
   {

    if(isset($request->filter_by_status))
    {
      if($request->filter_by_status!='')
      {
       $reqs=$request->all();
       $st=$request->filter_by_status;
        $faqs=DB::table("faqs")->join('users','faqs.vendorCode','=','users.VenderCode')->where('status',$st)->paginate(10);
        return view('admin_view.show_vendorQuery', compact('faqs','reqs')); 
      }
      else
      {
         $faqs=DB::table("faqs")->join('users','faqs.vendorCode','=','users.VenderCode')->paginate(10);
         return view('admin_view.show_vendorQuery', compact('faqs')); 
      }
    }
    else
    {


        $this->validate($request, [
        'action'=> 'required',
        'remarks'     => 'required',
        ]);
    
        $status=ucfirst(strtolower($request->action));
        $faq =FAQs::find($request->query_id);
        $faq->status = $request->action;
        $faq->query_remarks = $request->remarks;
        $faq->statusChDate = date('Y-m-d');
        $faq->save();
        return redirect()->route('admin.show_vendor_query')
                  ->with('success',"Query has been $status Successfully !");
      }
   }

  public function newNotificationForm(Request $request)
   {
     $notifications=Notifications::paginate(10);
     return view('admin_view.vendor_notification',compact('notifications')); 
   }
   public function newVendorPolicy(Request $request)
   {
      $policies=VPolicy::paginate(10);
     return view('admin_view.add_vendor_policy',compact('policies')); 
   }

   public function add_new_policy(Request $request)
   {
    
     $this->validate($request, [
         'vpolicy'=> 'required|mimes:pdf',
         'policy_status'=> 'required',
         'valied_date'        => 'required|after:entry_date',
      ],
    [
      'vpolicy.required'=>"Upload policy field is required",
      
      'vpolicy.mimes'=>"Vendor Policy must be a file of ",
      'policy_status.required'=>"Policy status field is required",
       'valied_date.required'=>"Valied up-to date field is required",
       'valied_date.after'=>"Valied up-to date must be a date after current date",
       
   ]
    );

    $file=$request->vpolicy;
    $fileExtension = $file->getClientOriginalExtension();
    $name="VPolicy_".strtotime("now").".".$fileExtension;
    $file->move(public_path().'/vendorPolicy/', $name);  
     $valied_date=date('Y-m-d',strtotime($request->valied_date));
    $policy=new VPolicy();
    $policy->policy=$name;
    $policy->status=$request->policy_status;
    $policy->valied_upTo=$valied_date;
    $policy->entry_date=$request->entry_date;
    $policy->save();
     return redirect()->route('admin.add_vendorPolicy_frm')
                  ->with('success',"Policy has been added successfully !");
   }
   public function updateVpolicyStatus(Request $request)
   {
       $vp=VPolicy::find($request->pid);
       $vp->status=$request->status;
       $vp->save();
       return redirect()->route('admin.add_vendorPolicy_frm')
                  ->with('success',"Status changed successfully !");
   }
   public function downloadVendorPolicy(Request $request)
   {
       $path="vendorPolicy/".$request->get_policy;
       $download_file_name=$request->get_policy;
       return Response::download($path, $download_file_name);
   }
   public function addNewNotifications(Request $request)
   {

    $this->validate($request, [
        'notification'=> 'required',
        'valied_date'     => 'required',
       
      ]);

       $valied_date=date('Y-m-d',strtotime($request->valied_date));
         $not =new Notifications();
        $not->notification = $request->notification;
        $not->valied_upTo = $valied_date;
        $not->entry_date = date('Y-m-d');
        $not->save();
   return redirect()->route('admin.add_notification')
                        ->with('success',"Notifications added successfully!");
   }
   public function show_New_Vend_Reg_Request(Request $request)
   {
     
       $vendors= VendorRegistration::with('BusinessType','City','State','Country')->paginate(10);
      return view('admin_view.newVendorRequest',compact('vendors'));
   }
    public function downloadVendCertificate(Request $request)
    {
    
          $path="vendor_docs/".$request->get_certifcate;
          $download_file_name=$request->get_certifcate;
        //  $headers = array('Content-Type: application/xlsx');
         return Response::download($path, $download_file_name);
       
      }
   public function changeNewVendorStatus(Request $request)
   {
     $this->validate($request, [
        'status'=> 'required',
        'date'     => 'required',
       'remarks'     => 'required',
       
      ]);
       $vendor=VendorRegistration::find($request->vendor_id);
       $vendor->vendor_status_by_admin=$request->status;
       $vendor->status_date=$request->date;
       $vendor->status_remarks=$request->remarks;
        $vendor->save();
        return redirect()->route('admin.show_New_Vendor_Request')
                        ->with('success',"Vendor status change successfully!");
   }
   public function getVendorRegData(Request $request)
   {
        $vendors=VendorRegistration::with('BusinessType','City','State','Country')->where('id',$request->vend_id)->first();
       $vcode=$vendors->vendor_code;
       $vendor_contacts=vendorContacts::where('vendorcode',$vcode)->get();
       $vendor_ref=vendorReferances::where('vendorcode',$vcode)->get();
       $vendor_cert=vendorCertifications::where('vendorcode',$vcode)->get();
       $vendor_cap=vendorCapcityDtls::where('vendorcode',$vcode)->first();
       $data=array('basic_info'=>$vendors,'contacts'=>$vendor_contacts,'reference'=>$vendor_ref,'certification'=>$vendor_cert,'capcityDtl'=>$vendor_cap);
        return Response::json($data, 200, [], JSON_NUMERIC_CHECK);
   }
  public function home()
   {
     
     $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');

     $fin_year_start=$y."-04";
     $fin_year_end=($y+1)."-03";
      $vend_code= Auth::user()->VenderCode;
   //  $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);

     $total_PO=GenBcodeDetails::whereRaw("DATE_FORMAT(Invoice_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->where('VENDOR_Code',$vend_code)->distinct('PO_Number')->count('PO_Number');
   $total_invoice=GenBcodeDetails::whereRaw("DATE_FORMAT(Invoice_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->
     where('VENDOR_Code',$vend_code)->distinct('Invoice_No')->count('Invoice_No');
       
    $sub = GenBcodeDetails::whereRaw("DATE_FORMAT(Invoice_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->
       where('VENDOR_Code',$vend_code)->groupBy('Material')->groupBy('po_number'); 
     $total_po_items= DB::table( DB::raw("({$sub->toSql()}) as sub"))
      ->mergeBindings($sub->getQuery()) // you need to get underlying Query Builder
       ->count();
     $total_delay=DeliveryDelayInfo::where('Vendor_Code',Auth::user()->VenderCode)->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->count();
     $sch_items=array();
     $header_data=PoHeader::all();
     $is_login = false;
if (!Auth::user()->is_login) {
   Auth::user()->is_login = 1; // Flip the flag to true
    Auth::user()->save();
   $this->get_new_scheduled_data();
}
     $header_data=DB::table("po_headers")
                 ->select('po_headers.PO_Number','po_headers.Company_Code','po_headers.Plant_Code','po_details.Material_description','po_details.PO_QTY')->orderBy('po_headers.id','DESC')
                 ->where('po_headers.Vendor_Code',$vend_code)
               ->join('po_details','po_headers.po_number','=','po_details.po_number')
                ;
 $header_data = $header_data->paginate(5);
 

   return view('vendor_view.home',compact('total_PO','total_invoice','total_po_items','total_delay'))->with('sch_items',$header_data);

   }

   public function dash_recent_schedule(Request $request)
   {
     $output="";

     $this->get_new_scheduled_data();
      $sch_items=DB::table("po_headers")
                 ->select('po_headers.PO_Number','po_headers.Company_Code','po_headers.Plant_Code','po_details.Material_description','po_details.PO_QTY')
               ->join('po_details','po_headers.po_number','=','po_details.po_number')
                ->paginate(5);
        
       return view('vendor_view.presult', compact('sch_items'));

   }
   public function dash_search_schedule(Request $request)
   {
    $search_data=$request->serch_text;
       $header_data=DB::table("po_headers")
                 ->select('po_headers.PO_Number','po_headers.Company_Code','po_headers.Plant_Code','po_details.Material_description','po_details.PO_QTY')
               ->join('po_details','po_headers.po_number','=','po_details.po_number');
      if($request->search_by==1) 
      {
        
       $data=$header_data->where('po_headers.PO_Number','LIKE',$search_data. '%')->paginate(5);
      }  
      else if($request->search_by==2)  
      {
      $data=$header_data->where('po_headers.Company_Code','LIKE',$search_data. '%')->paginate(5);
      } 
      else if($request->search_by==3)  
      {
        $data=$header_data->where('po_headers.Plant_Code','LIKE',$search_data. '%')->paginate(5);
      }  
      else
      {
        $data=$header_data->where('po_details.Material_description','LIKE',strtoupper($search_data). '%')->paginate(5);
      } 
      $sch_items= $data;
  return view('vendor_view.presult', compact('sch_items'));
    //return Response::json($data, 200, [], JSON_NUMERIC_CHECK);

   }
   public function get_new_scheduled_data()
   {
      $vend_code= Auth::user()->VenderCode;
   //  $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
     $api_fun="get_po_data";
     $rfc_name="ZWMFM_BARCODE_PO_DETAILS";
     $companies= DB::table('companies')
     ->select('companies.CompanyCode','companies.CompanyName','plants.PlantCode','plants.PlantName')
     ->join('plants','plants.CompanyCode','=','companies.CompanyCode')
      ->get();
      foreach ($companies as $comp) 
      {
         $po_headers = DB::table('po_headers')->where('Vendor_Code',$vend_code)->where('Company_Code',$comp->CompanyCode)->where('Plant_Code',$comp->PlantCode);
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
        $data="function=".$api_fun."&rfc_name=".$rfc_name."&vencode=".$vend_code;
        $data.="&comp_code=". $comp->CompanyCode."&plant_code=". $comp->PlantCode;
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
             if($json['Header_Items']!=null)
              {
               foreach ($json['Header'] as $key ) 
               {
               $data=array('PO_Number' =>$key['EBELN'] ,'Company_Code' =>$key['BUKRS'], 'Plant_Code' =>$comp->PlantCode ,'PO_Category' => $key['BSTYP'],'Vendor_Code' =>$key['LIFNR'] ,'Vendor_Name' =>$key['NAME1'] ,'PO_Org' =>$key['EKORG'],'PO_Group' => $key['EKGRP'],'Currency' => $key['WAERS']);
               DB::table('po_headers')->insert($data);
              }
              if($json['Header_Items']!=null)
              {
                foreach ( $json ['Header_Items'] as $key ) 
               {
                $data=array('po_number' =>$key['EBELN'] ,'PO_Item_Code' =>$key['EBELP'] ,'Material_Code' => $key['MATNR'],
               'Material_description' =>$key['MAKTX'] ,'PO_QTY' =>$key['MENGE'] ,'PO_outstanding_Qty' =>$key['MENGE'],'UOM' => $key['MEINS']);
               DB::table('po_details')->insert($data);
              }
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
    }
  }
}
   public function chart_data()
   {
      $y=Date('Y');
      $vend_code= Auth::user()->VenderCode;
     // $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
      $chart_data=array();
      $m= Date('m');
      if($m<4)
      {
       $n=12+ $m;
      }
     else
      {
        $n= $m;
      }
    for($i=4;$i<=$n;$i++)
     {
       if($i>12)
       {
       $j=$i%12; 
       }
      else
       {
        $j=$i;
       }
       $d = DB::table("gen_bcode_details")
      ->whereYear('Entry_Date', '=', $y)->whereMonth('Entry_Date', '=', $j)->where('VENDOR_Code',$vend_code)
      ->select(DB::raw("SUM(Quantity) as Quantity,SUM(Dispatch_Qty) as Dispatch_Qty"))
      ->first();
    if($d->Dispatch_Qty!=null &&  $d->Quantity!=null)
     {
      $monthName =$this->getMonth($j);
      $chart_data[]=array('y' => $monthName,'a'=>  $d->Quantity,'b'=>  $d->Dispatch_Qty);
     }
   else
    {
      $monthName =$this->getMonth($j);
      $chart_data[]=array('y' => $monthName,'a'=> "0",'b'=> "0");
    }
  }
    return Response::json($chart_data, 200, [], JSON_NUMERIC_CHECK);
  
  }
   public function chart_item_data(Request $request)
   {
      $chart_data=array();
      $vend_code= Auth::user()->VenderCode;
      //$vend_code=str_pad($vend_code, 10, '0', STR_PAD_LEFT);
      $data = DB::table("gen_bcode_details")
      ->whereYear('Entry_Date', '=', $request->year)->whereMonth('Entry_Date', '=', $request->mon)
     ->where('VENDOR_Code',$vend_code)
      ->select('Material','Material_Desc', DB::raw("SUM(Quantity) as Quantity,SUM(Dispatch_Qty) as Dispatch_Qty"))
      ->groupBy('Material')
      ->get();
      foreach ($data as $key) 
      {
      $chart_data[]=array('y' => $key->Material,'a'=> $key->Quantity,'b'=> $key->Dispatch_Qty);
      }
  return Response::json($chart_data, 200, [], JSON_NUMERIC_CHECK);
 }
 public function dash_itemwise_graph(Request $request)
 {
      $chart_data=array();
      $vend_code= Auth::user()->VenderCode;
     // $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
      $data = DB::table("gen_bcode_details")
      ->whereYear('Entry_Date', '=', $request->year)->whereMonth('Entry_Date', '=', $request->mon)
     ->where('VENDOR_Code',$vend_code)
      ->select('Material','Material_Desc', DB::raw("SUM(Quantity) as Quantity,SUM(Dispatch_Qty) as Dispatch_Qty"))
      ->groupBy('Material')
      ->get();
      foreach ($data as $key) 
      {
      $chart_data[]=array('y' => $key->Material,'a'=> $key->Quantity,'b'=> $key->Dispatch_Qty);
      }
     $chart_data = json_encode($chart_data,JSON_NUMERIC_CHECK);
$mon=$this->getMonth($request->mon);
$year=$request->year;
return view('vendor_view.dash_item_wise_graph',compact('chart_data','mon','year'));
 }
 public function chart_item_dtl(Request $request)
 {
     $vend_code= Auth::user()->VenderCode;
    // $vend_code=str_pad($vend_code, 10, '0', STR_PAD_LEFT);
     $data = DB::table("gen_bcode_details")
     ->whereYear('Entry_Date', '=', $request->yr)->whereMonth('Entry_Date', '=', $request->mnth)
     ->where('VENDOR_Code',$vend_code)
     ->where('Material',$request->mat)
      ->select('Invoice_No','Company_Code','Plant_Code','PO_Number','Material_Desc','Quantity','Dispatch_Qty')
      ->groupBy('Invoice_No')
      ->get();

       $info = DB::table("gen_bcode_details")
      ->whereYear('Entry_Date', '=', $request->yr)->whereMonth('Entry_Date', '=', $request->mnth)
      ->where('VENDOR_Code',$vend_code)
     ->where('Material',$request->mat)
      ->select('Material_Desc')
      ->first();
      $mname=$this->getMonth($request->mnth);
      $item_inf=Array("mat_decrp"=>$info->Material_Desc,"month"=>$mname,"year"=>$request->yr,'mnth'=>$request->mnth,'mat'=>$request->mat);
      return view('vendor_view.dash_bar_item_details',compact('data','item_inf'));
 }
 public function dash_delivery_delay_info(Request $request)
 {
     $y=Date('Y');
   $ven_code= Auth::user()->VenderCode;
 /*  $delivery_delay=DeliveryDelayInfo::where('Vendor_Code',$ven_code)->whereYear('Entry_Date','=',$y)->get()*/;
     $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
     $fin_year_start=$y."-04";
     $fin_year_end=($y+1)."-03";
     $delivery_delay=DeliveryDelayInfo::where('Vendor_Code',Auth::user()->VenderCode)->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->paginate(10);
   return view('vendor_view.dash_delivery_delay_info',compact('delivery_delay','y'));

 }
 public function dash_po_item_info(Request $request)
 {
   
    $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
     $fin_year_start=$y."-04";
     $fin_year_end=($y+1)."-03";
   $vend_code= Auth::user()->VenderCode;
  $dash_po_items = GenBcodeDetails::whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->
     where('VENDOR_Code',$vend_code)->groupBy('Material')->groupBy('po_number')->paginate(10); 
    
    return view('vendor_view.dash_po_items',compact('dash_po_items','y'));
 }
 public function dash_generated_invoice(Request $request)
 {
      $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
      $fin_year_start=$y."-04";
      $fin_year_end=($y+1)."-03";
     $vend_code= Auth::user()->VenderCode;
     $invoices=GenBcodeDetails::whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->
     where('VENDOR_Code',$vend_code)->groupBy('Invoice_No')->paginate(10);
      return view('vendor_view.dash_generated_invoice',compact('invoices','y'));
 }
 
 public function dash_dispactched_po(Request $request)
 {
     $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
      $fin_year_start=$y."-04";
      $fin_year_end=($y+1)."-03";
      $vend_code= Auth::user()->VenderCode;
      $po_details=GenBcodeDetails::whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->where('VENDOR_Code',$vend_code)->groupBy('Material')
      ->paginate(10);
     return view('vendor_view.dash_dispatched_po',compact('po_details','y'));
 }
   public function getMonth($n)
   {
    $name="";
    switch ($n) {
        case 1:$name="Jan";
        break;
        case 2:$name="Feb";
        break;
        case 3:$name="Mar";
        break;
        case 4:$name="Apr";
        break;
        case 5:$name="May";
        break;
        case 6:$name="Jun";
        break;
        case 7:$name="Jul";
        break;
        case 8:$name="Aug";
        break;
        case 9:$name="Sept";
        break;
        case 10:$name="Oct";
        break;
        case 11:$name="Nov";
        break;
        case 12:$name="Dec";
        break;
       default:
       $name="Undefined";
        break;
    }
    return $name;
   }

    public function delivery_Schedule_Frm(Request $request)
    {
    
     $companies= Company::all();
     $usrs=User::where('IsHoAdmin','!=','1')->get();
    return view ('admin_view.delevery_schedule',compact('companies','usrs'));
     
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

public function delivery_Schedule(Request $request)
    {
      
      $validatedData = $request->validate([
             'v_code'=> 'required',
             'c_code' => 'required',
             'p_code' => 'required'
             
              ], 
              [ 
             'v_code.required' => 'The Vendor Code field is required !',
            'c_code.required' => 'The company Code field is required !',
            'p_code.required' => 'The plant code filed is required !',
            
            ]);
    
      // $vend_code=str_pad( $request->v_code, 10, '0', STR_PAD_LEFT);
        $vend_code=$request->v_code;
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
    if($request->has('entry_dt') && !empty($request->entry_dt))
      {
        $po_headers=$po_headers->whereDate('PO_Date',$request->entry_dt);
      
      }

       $request->session()->put('header_wh',$wh_clarr);
       $hdrs=$po_headers;
       $po_headers=$po_headers->get();
       $po_no=array();
       $i=0;
       foreach ($po_headers as $head_data) {
        array_push($po_no,$head_data->PO_Number);
         
       }
        
        $filled_data= $request->all();
      $usrs=User::all();
     $request->session()->put('header_wh_dtl',$po_no);
     $po_details=PoDetail::whereIn('po_number',$po_no);
     $pdtl = DB::table('po_details')->whereIn('po_number',$po_no);
     //$pdtl= $po_details;
     $po_future_items=PoFutureItems::whereIn('po_number',$po_no);
     $po_details=$po_details->get();
      if(isset($request->item_export_type) || isset($request->header_export_type))
       {
         if(isset($request->header_export_type))
         {
          if($request->header_export_type=="excel")
         {
           $excel = Exporter::make('Excel');
           $excel->loadQuery($hdrs);
           return $excel->stream('poHeader.xlsx');
          }
        else if($request->header_export_type=="csv")
        {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($hdrs);
         return $excel->stream('poHeader.csv');
         }
        else
         {
          $po_headers=$hdrs->get();
          $data = ['po_headers' => $po_headers];
          $pdf = PDF::loadView('admin_view.pdfview.po_header', $data); 
          return $pdf->download('poHeader.pdf');
          }

         }
         if(isset($request->item_export_type))
         {
            if($request->item_export_type=="excel")
           {
             $excel = Exporter::make('Excel');
             $excel->loadQuery($pdtl);
             return $excel->stream('poItemInfo.xlsx');
           }
         else if($request->item_export_type=="csv")
          {
            $excel = Exporter::make('Csv');
            $excel->loadQuery($pdtl);
            return $excel->stream('poItemInfo.csv');
         }
        else
         {
            $po_details=$pdtl->get();
            $po_details = ['po_details' => $po_details];
            $pdf = PDF::loadView('admin_view.pdfview.po_item', $po_details); 
             return $pdf->download('poItemInfo.pdf');
          }

         }
       }
      else
       {
        
          $msg_type="success";
             $msg="Data Fetching Successfully !";
         return redirect()->route('admin.delivery_schedule')
                  ->with($msg_type,$msg)->with('ccode',$request->c_code)->with('pcode',$request->p_code)->with('v_code',$request->v_code)->with('po_no',$request->po_no)->with('po_headers',$po_headers)->with('po_details',$po_details);

       
    /*     return view ('admin_view.delevery_schedule',compact('po_headers','po_details','companies','usrs',
         'po_future_items','filled_data'));*/
       }
   }

public function get_upload_Vendor_Frm()
{
   
return view('admin_view.upload_vendor');
}
 public function rules_vendor_code($vend_code)
    {

      $c=User::where('VenderCode',$vend_code)->count();
     
      if($c>0)
      {
        return false;
      }
      else
      {
        return true;
      }
      
    }
    public function downloadVendRegFormat(Request $request)
    {
       if($request->submit=="get_format")
        {
          $pathToFile="vendor_reg_formt.xlsx";
          $headers = array('Content-Type: application/xlsx');
         return Response::download($pathToFile, 'vendorRegFormat.xlsx', $headers);
        }
        else
        {
          $request->validate([
            'import_file' => 'required|mimes:xlsx,xls'
        ]);
       $file=$request->import_file;
       $fileExtension = $file->getClientOriginalExtension();
       $name="vendor_list_".strtotime("now").".".$fileExtension;
       $file->move(public_path().'/UploadVendor/', $name);  
       $path='UploadVendor/'.$name;

    
     $excel = Importer::make('Excel');
     $excel->load($path);
     $data = $excel->getCollection();
     $insert_data =array(); 
     $i=0;
     if($data->count() > 0)
     {
      foreach($data as $row )
      {
       

         $i++;
         if($i==1)
         continue;
       if(!$this->rules_vendor_code($row[0]))
       {
        return back()->with('error',"Vendor Code ($row[0]) already registered ! ");

       }
      
         $insert_data[] = array(
         'VenderCode'               => $row[0],
         'UserName'                 => $row[1],
         'WebPassword'               => Hash::make($row[2]),
         'Email'                     => $row[3],
         'IsHoAdmin'                 => $row[4],
         'IsActive'                  => $row[5],
         'Vend_Contact_P_Name'       => $row[6],
         'Vend_Contact_P_PhoneNo'    => $row[7],
         'Vend_Contact_P_EmailID'    => $row[8],
         'MSite_Contact_P_Name'      => $row[9],
         'MSite_Contact_P_PhoneNo'   => $row[10],
         'Msite_Contact_P_EmailID'   => $row[11]);
       

      }


      if(!empty($insert_data))
      {
       DB::table('users')->insert($insert_data);
      }
     }
     File::delete($path);
     return back()->with('success', 'Excel Data Imported successfully.');
    }
 }
 
 public function getChangePassFrm()
    {
      
     return view ('admin_view.change_pass');  
    }
   public function updatePassword(Request $request)
   {
   $this->validate($request, [
        'old_password'=> 'required',
        'new_password'     => 'required|min:4',
        'confirm_password' => 'required|same:new_password'
    ],
    [
    'old_password.required'=>'The Current Password field is required.',
    'new_password.required'=>'The New Password field is required.',
    'confirm_password.required'=>'The Confirm Password field is required.',
    ]);

   $vendor_code=Auth::user()->VenderCode;
   //$old_pass=Hash::make($request->old_password);
   $credential=array('VenderCode' => $vendor_code, 'password' => $request->old_password);
 if( Auth::attempt($credential))
 {
   $pass=Hash::make($request->new_password);
        DB::table('users')
            ->where('VenderCode',$vendor_code)
            ->update(['WebPassword' =>  $pass]);
             return redirect()->route('admin.get_Change_Pass_Frm')
                        ->with('success','Your password has been changed');
   }
  else
   {
return back() ->with('error','The specified password does not match the database password');
  }
   
   }
public function get_Add_Vendor_Frm()
{
   
 $users=USER::where('IsHoAdmin',0)->paginate(10);

return view('admin_view.add_vendor',compact('users'));
}
public function is_available_vendor(Request $request)
      {
          $usr = User::where('VenderCode', '=', $request->v_code)->count();
           if ($usr > 0) 
           {
            return response()->json([ 'success' => true ,'VenderCode' => $request->v_code]);
           }
           else
           {
            return response()->json([ 'success' => false ,'invoice' => $request->v_code]);
           }
     
      }
      public function change_vendor_status(Request $request)
      {
         $data=array(
                 'IsActive'=> $request->vnd_status,
                
             );
          $usr = User::where('VenderCode', '=', $request->v_code)->update($data);
           if ($usr) 
           {
            return response()->json([ 'success' => true ,'user_status' => $request->vnd_status]);
           }
           else
           {
            return response()->json([ 'success' => false ,'user_status' => $request->vnd_status]);
           }
     
      }
public function createVendor(Request $request)
{
$this->validate($request, 
           [
              'v_code'             =>        'required|unique:users,VenderCode',
              'v_name'             =>        'required',
              'V_password'         =>        'required|min:6',
              'v_confirm_password' =>        'required|same:V_password',
              'v_email'            =>         'required',
              'user_type'          =>        'required',
              'user_status'        =>        'required',
              'vcp_name'           =>        'required',
              'vcp_phone'          =>        'required',
              'vcp_email'          =>        'required',
              'ccp_name'           =>        'required',
              'ccp_phone'          =>        'required',
              'ccp_email'          =>        'required',
              'v_capcity'          =>        'required',
          ],
          [
           'v_code.required'              =>"* Vendor Code field is required",
           'v_code.required'              =>"* Vendor Code must be unique",
           'v_name.required'              =>"* Name  field is required",
           'V_password.required'          =>"* Password field is required",
           'V_password.min'               =>"* The password must be at least 6 characters.",
           'v_confirm_password.required'  =>"* Re-password field is required",
           'v_confirm_password.same'      =>"* Password and Re-password must be matched",
           'v_email.required'             =>"* Email field is required",
           'user_type.required'           =>"* User Type field is required",
           'user_status.required'         =>"* User Status field is required",
           'vcp_name.required'            =>"* Vendor Contact Person name field is required",
           'vcp_phone.required'           =>"* Vendor Contact person phone field is required",
           'vcp_email.required'           =>"* Vendor Contact person email field is required",
           'ccp_name.required'            =>"* Company Contact Person Name field is required",
           'ccp_phone.required'           =>"* Company Contact Person Phone field is required",
           'ccp_email.required'           =>"* Company Contact Person Email field is required",
           'v_capcity.required'           =>"* Vendor Capacity field is required",
         ]);

  $data=array(
                 'VenderCode'=> $request->v_code,
                 'UserName'=>  $request->v_name,
                 'WebPassword' => Hash::make($request->V_password),
                 'Email'=> $request->v_email,
                 'IsHoAdmin'=>  $request->user_type,
                 'IsActive'=> $request->user_status,
                  'IsUserCreated' =>1,
                 'Vend_Contact_P_Name' =>$request->vcp_name,
                 'Vend_Contact_P_PhoneNo'=> $request->vcp_phone,
                 'Vend_Contact_P_EmailID' => $request->vcp_email,
                 'MSite_Contact_P_Name' =>$request->ccp_name,
                 'MSite_Contact_P_PhoneNo' => $request->ccp_phone,
                 'Msite_Contact_P_EmailID' => $request->ccp_email,
                  'capcity' => $request->v_capcity
                
             );


  if(trim($request->edit_vend_id)==1)
  {
    $usr=DB::table('users')->insert($data);
    $msg='Vendor Creted Successfully.';
 }
else
{
   $usr=User::where('VenderCode',$request->edit_vend_code)->update($data);
   $msg='Vendor Updated Successfully.';

}
 
return redirect()->route('admin.add_vendor')->with('success',$msg);
}

public function editVendor(Request $request)
{
  $user_edit=User::where('id',$request->editid)->first();
   $users=USER::where('IsHoAdmin',0)->paginate(10);
  return view('admin_view.add_vendor',compact('users','user_edit'));
}
public function updateVendor(Request $request)
{
  $this->validate($request, 
           [
              'v_code'             =>        'required|unique:users,VenderCode,'.$request->edit_vend_id,
              'v_name'             =>        'required',
              'V_password'         =>        'required|min:6',
              'v_confirm_password' =>        'required|same:V_password',
              'v_email'            =>        'required',
              'user_type'          =>        'required',
              'user_status'        =>        'required',
              'vcp_name'           =>        'required',
              'vcp_phone'          =>        'required',
              'vcp_email'          =>        'required',
              'ccp_name'           =>        'required',
              'ccp_phone'          =>        'required',
              'ccp_email'          =>        'required',
              'v_capcity'          =>        'required',
          ],
          [
           'v_code.required'              =>"* Vendor Code field is required",
           'v_code.required'              =>"* Vendor Code must be unique",
           'v_name.required'              =>"* Name  field is required",
           'V_password.required'          =>"* Password field is required",
           'V_password.min'               =>"* The password must be at least 6 characters.",
           'v_confirm_password.required'  =>"* Re-password field is required",
           'v_confirm_password.same'      =>"* Password and Re-password must be matched",
           'v_email.required'             =>"* Email field is required",
           'user_type.required'           =>"* User Type field is required",
           'user_status.required'         =>"* User Status field is required",
           'vcp_name.required'            =>"* Vendor Contact Person name field is required",
           'vcp_phone.required'           =>"* Vendor Contact person phone field is required",
           'vcp_email.required'           =>"* Vendor Contact person email field is required",
           'ccp_name.required'            =>"* Minda Site Contact Person Name field is required",
           'ccp_phone.required'           =>"* Minda Site Contact Person Phone field is required",
           'ccp_email.required'           =>"* Minda Site Contact Person Email field is required",
            'v_capcity.required'           =>"* Vendor Capacity field is required",
         ]);

  $data=array(
                 'VenderCode'=> $request->v_code,
                 'UserName'=>  $request->v_name,
                 'WebPassword' => Hash::make($request->V_password),
                 'Email'=> $request->v_email,
                 'IsHoAdmin'=>  $request->user_type,
                 'IsActive'=> $request->user_status,
                  'IsUserCreated' =>1,
                 'Vend_Contact_P_Name' =>$request->vcp_name,
                 'Vend_Contact_P_PhoneNo'=> $request->vcp_phone,
                 'Vend_Contact_P_EmailID' => $request->vcp_email,
                 'MSite_Contact_P_Name' =>$request->ccp_name,
                 'MSite_Contact_P_PhoneNo' => $request->ccp_phone,
                 'Msite_Contact_P_EmailID' => $request->ccp_email
                
             );
  $users=USER::where('IsHoAdmin',0)->paginate(10);
   $usr=User::where('id',$request->edit_vend_id)->update($data);
   $msg='Vendor Updated Successfully.';
   
   return redirect()->route('admin.add_vendor')->with('success',$msg);
}
public function del_Vendor(Request $request)
{
  $del=User::where("id",$request->delvendid)->delete();
  return back()->with('success','vendor deleted Successfully.');
}
public function get_Del_Inv_Frm()
{
//->get(['Invoice_No','Invoice_Date','VENDOR_Code']);
   
  $bar_code_details=DB::table('gen_bcode_details')->groupBy('Invoice_No')->groupBy('VENDOR_Code')->paginate(10);
  return view('admin_view.delete_invoice',compact('bar_code_details'));
}


public function delInvoice(Request $request)
{
 if($request->submit_v=="del_inv")
   {
    $details=GenBcodeDetails::where('Invoice_No',$request->invoice_no)->delete();
     return back()->with('success',"Invoice no. ($request->invoice_no) deleted successfully");
   }
   else
   {
    if($request->find_invoice!='')
    {
        $bar_code_details=DB::table('gen_bcode_details')->where('Invoice_No',$request->find_invoice)->groupBy('Invoice_No')->groupBy('VENDOR_Code')->paginate(10);
    }
    else
    {
      $bar_code_details=DB::table('gen_bcode_details')->groupBy('Invoice_No')->groupBy('VENDOR_Code')->paginate(10);
       
    }
   $invoice=$request->find_invoice;
return view('admin_view.delete_invoice',compact('bar_code_details','invoice'));
    }
 
   }                        

 public function getEmailWorkFlow()
    {
           $users=User::all();
          $mail_status= MailStatus::where('mail_type','B')->paginate(10);
       return view('admin_view.email_work_flow',compact('users','mail_status'));
   }
   public function sendEmailWorkFlow(Request $request)
   {
      $this->validate($request, [
        'vendr'=> 'required',
        'attachment'     => 'required',
       'subject'=> 'required',
        'editor1'     => 'required',
      ],
      [
      'vendr.required' => 'The Vendor Code filed is required !',
      'attachment.required' => ' Attachment filed is required !',
        'subject.required' => 'Subject filed is required !',
      'editor1.required' => ' Message filed is required !',
      ]
    );    
      $m_status=0;
      $vendors=$request->vendr;
      $subject=$request->subject;
      $message=$request->editor1;
      $file=$request->attachment;
      $fileExtension = $file->getClientOriginalExtension();
      $name="mail_attachment_".strtotime("now").".".$fileExtension;
      $file->move(public_path().'/vendorPolicy/', $name);  
      $attach='vendorPolicy/'.$name;
      for($i=0;$i<count($vendors);$i++)
       {
         $vcode=trim($vendors[$i]);
         $user=User::where('VenderCode',$vcode)->first();
         $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    try {
             
        $mail->isSMTP();   
        $mail->Host = "smtp.rediffmailpro.com"; 
        $mail->SMTPAuth = true; 
        $mail->Username = "vipin.kumar@mawaimail.com";                
        $mail->Password = "vipin#";   
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587; 
        $mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )); 
          
           $mail->setFrom("noreply@mawaimail.com", "Supplier ePortal");
           $mail->Subject =  $subject;
           $mail->MsgHTML($message);
           $mail->addAddress($user->Email);
           $mail->addAttachment($attach);  
           
           if(!$mail->send())
           {
            $m_status=2;
           $mail_status = new MailStatus;
    $mail_status->vendorCode = $vcode;
    $mail_status->mail_id = $user->Email;
    $mail_status->mail_type = 'B';
    $mail_status->status = 'F';
    $mail_status->mail_status_descrp = "Mail not sending";
     $mail_status->save();
           }
         else
         {
           $m_status=1;
            $mail_status = new MailStatus;
    $mail_status->vendorCode = $vcode;
    $mail_status->mail_id = $user->Email;
    $mail_status->mail_type = 'B';
     $mail_status->status = 'S';
    $mail_status->mail_status_descrp = "Broadcast mail has been sent successfully!";
     $mail_status->save();

         }

  

         } catch (phpmailerException $e) {
          $m_status=2;
    $mail_status = new MailStatus;
    $mail_status->vendorCode = $vcode;
    $mail_status->mail_id = $user->Email;
    $mail_status->mail_type = 'B';
    $mail_status->status = 'F';
    $mail_status->mail_status_descrp = $e;
     $mail_status->save();
          dd($e);
          } catch (Exception $e) {
            $m_status=2;
             $mail_status = new MailStatus;
    $mail_status->vendorCode = $vcode;
    $mail_status->mail_id = $user->Email;
    $mail_status->mail_type = 'B';
    $mail_status->status = 'F';
    $mail_status->mail_status_descrp = $e;
     $mail_status->save();
          dd($e);
          }
   
     }
  File::delete($attach);
  if($m_status==1)
  {
     return redirect()->route('admin.getWorkFlow')
                        ->with('success',"Mail has been sent successfully!");
  }
  else
  {
    return redirect()->route('admin.getWorkFlow')
                        ->with('error',"Mail not sending !");
  }
  
   }

}
