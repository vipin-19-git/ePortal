<?php

namespace App\Http\Controllers;

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
use App\Notifications;
use Exporter;
use Maatwebsite\Excel\Facades\Excel;
use App\Company;use App\DeliveryDelayInfo;
use App\GenBcodeDetails;use Importer;
use App\PoHeader;use App\PoDetail;use App\PoFutureItems;use Response;use App\CreditDebitNote;
class ExportController extends Controller
{
    //
    public function exportDispatPo(Request $request)
 {
    $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
      $fin_year_start=$y."-04";
      $fin_year_end=($y+1)."-03";
      $vend_code= Auth::user()->VenderCode;
    $po_details=DB::table('gen_bcode_details')->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->where('VENDOR_Code',$vend_code)->groupBy('Material')
       ->select('PO_Number','PO_Item','Material','Material_Desc','UOM','Quantity','Dispatch_Qty','Packing_Qty');
      if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
      $excel->loadQuery($po_details);
       return $excel->stream('dispatched_po.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($po_details);
       return $excel->stream('dispatched_po.csv');
      }
      else
      {
          $po_details=$po_details->get();
          $data = ['po_details' => $po_details,'y'=>$y];
          $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_dispatched_po', $data); 
          return $pdf->download('dispatched_po.pdf');
        }
    
 }
 public function exportInvGen(Request $request)
 {

 	  $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
      $fin_year_start=$y."-04";
      $fin_year_end=($y+1)."-03";
     $vend_code= Auth::user()->VenderCode;
     $invoices=DB::table('gen_bcode_details')->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->where('VENDOR_Code',$vend_code)->groupBy('Invoice_No')
     ->select('Invoice_No','Invoice_Date','PO_Number','LR_No','Transpoter_Name');


      if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
       $excel->loadQuery($invoices);
       return $excel->stream('generate_invoice.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($invoices);
       return $excel->stream('generate_invoice.csv');
      }
      else
      {
          $invoices=$invoices->get();
          $data = ['invoices' => $invoices];
          $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_inv_gen', $data); 
          return $pdf->download('generate_invoice.pdf');
        }
 }

 public function exportPoItems(Request $request)
 {
     $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
      $fin_year_start=$y."-04";
      $fin_year_end=($y+1)."-03";
      $vend_code= Auth::user()->VenderCode;
      $dash_po_items=DB::table('gen_bcode_details')->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->
      where('VENDOR_Code',$vend_code)->groupBy('Material')->groupBy('po_number')
     ->select('PO_Number','Invoice_No','Invoice_Date','Material','Material_Desc','Quantity','Dispatch_Qty','Packing_Qty');
     if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($dash_po_items);
        return $excel->stream('poItemInfo.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($dash_po_items);
       return $excel->stream('poItemInfo.csv');
      }
      else
      {
          $dash_po_items=$dash_po_items->get();
          $data = ['dash_po_items' => $dash_po_items];
          $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_poItem_info', $data); 
          return $pdf->download('poItemInfo.pdf');
        }
//


}
public function exportDlvryDly(Request $request)
 {
   $y=Date('Y');
   $ven_code= Auth::user()->VenderCode;
   $mon =Date('m');
     if($mon<4)
      $y=Date('Y')-1;
     else
      $y=Date('Y');
     $fin_year_start=$y."-04";
     $fin_year_end=($y+1)."-03";
     $delivery_delay=DB::table('delivery_delay_infos')->where('Vendor_Code',Auth::user()->VenderCode)->whereRaw("DATE_FORMAT(Entry_Date,'%Y-%m') between ? and ?", [$fin_year_start,$fin_year_end])->select('PO_Number','Avl_Qty','Material_Code_Desc','Supplier_Name','Supplier_Cont_Person','From_Date','To_Date','Reason');
     if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($delivery_delay);
        return $excel->stream('delivery_delay.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($delivery_delay);
       return $excel->stream('delivery_delay.csv');
      }
      else
      {
          $delivery_delay=$delivery_delay->get();
          $data = ['delivery_delay' => $delivery_delay];
          $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_delivery_delay', $data); 
          return $pdf->download('delivery_delay.pdf');
        }
  
}
public function exportBar(Request $request)
{

 $ven_code= Auth::user()->VenderCode;
     $vend_code=str_pad($ven_code, 10, '0', STR_PAD_LEFT);
     $data = DB::table("gen_bcode_details")
     ->whereYear('Entry_Date', '=', $request->yr)->whereMonth('Entry_Date', '=', $request->mnth)
     ->where('VENDOR_Code',$vend_code)
     ->where('Material',$request->mat)
      ->select('Invoice_No','Company_Code','Plant_Code','PO_Number','Material_Desc','Quantity','Dispatch_Qty')
      ->groupBy('Invoice_No');
       if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($data);
        return $excel->stream('montly_dispatch_detail.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($data);
       return $excel->stream('montly_dispatch_detail.csv');
      }
      else
      {
          $data=$data->get();
          $data = ['data' => $data];
          $pdf = PDF::loadView('vendor_view.pdfview.pdf_view_bar_item_info', $data); 
          return $pdf->download('montly_dispatch_detail.pdf');
        }
}
public function exportNotifiction(Request $request)
{

       $now=date('Y-m-d');
       $notifications = DB::table("notifications")->whereDate('valied_upTo','>=',$now);
       if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($notifications);
        return $excel->stream('active_notification.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($notifications);
       return $excel->stream('active_notification.csv');
      }
      else
      {
          $notifications=$notifications->get();
          $notifications = ['notifications' => $notifications];
          $pdf = PDF::loadView('admin_view.pdfview.active_notification', $notifications); 
          return $pdf->download('active_notification.pdf');
        }
}
public function exportPendingNewVendor(Request $request)
{

       $vendors = DB::table("vendor_registrations")
         ->select('vendor_registrations.vendor_name','business_types.name','vendor_registrations.mobile','vendor_registrations.email','vendor_registrations.phone_no','vendor_registrations.address_1','vendor_registrations.address_2','vendor_registrations.address_3','cities.city_name','states.state_name','countries.country_name','vendor_registrations.gst_no','vendor_registrations.pan_no')
       ->join('business_types','vendor_registrations.business_type','=','business_types.code')->join('cities','vendor_registrations.city','=','cities.id')
       ->join('states','vendor_registrations.state','=','states.state_Code')->join('countries','vendor_registrations.country','=','countries.country_Code')->where('vendor_status_by_admin','Pending');
       if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($vendors);
        return $excel->stream('Awaited_Reg.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($vendors);
       return $excel->stream('Awaited_Reg.csv');
      }
      else
      {
          $vendors=$vendors->get();
          $vendors = ['vendors' => $vendors];
          $pdf = PDF::loadView('admin_view.pdfview.pending_vend_reg', $vendors); 
          return $pdf->download('Awaited_Reg.pdf');
        }
}
public function exportHoldNewVendor(Request $request)
{

       $vendors = DB::table("vendor_registrations")
         ->select('vendor_registrations.vendor_name','business_types.name','vendor_registrations.mobile','vendor_registrations.email','vendor_registrations.phone_no','vendor_registrations.address_1','vendor_registrations.address_2','vendor_registrations.address_3','cities.city_name','states.state_name','countries.country_name','vendor_registrations.gst_no','vendor_registrations.pan_no')
       ->join('business_types','vendor_registrations.business_type','=','business_types.code')->join('cities','vendor_registrations.city','=','cities.id')
       ->join('states','vendor_registrations.state','=','states.state_Code')->join('countries','vendor_registrations.country','=','countries.country_Code')->where('vendor_status_by_admin','Hold');
       if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($vendors);
        return $excel->stream('Hold_vendor_reg.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($vendors);
       return $excel->stream('Hold_vendor_reg.csv');
      }
      else
      {
          $vendors=$vendors->get();
          $vendors = ['vendors' => $vendors];
          $pdf = PDF::loadView('admin_view.pdfview.pending_vend_reg', $vendors); 
          return $pdf->download('Hold_vendor_reg.pdf');
        }
}
public function exportPendingQuery(Request $request)
{

       $faqs=DB::table("faqs")->where('status','hold');
      $faqs= DB::table('faqs')
       ->join('users', 'faqs.vendorCode', '=', 'users.VenderCode')
        ->select('faqs.*','users.UserName');
       if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($faqs);
        return $excel->stream('open_vendr_query.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($faqs);
       return $excel->stream('open_vendr_query.csv');
      }
      else
      {
          $faqs=$faqs->get();
          $faqs = ['faqs' => $faqs];
          $pdf = PDF::loadView('admin_view.pdfview.pending_vendr_query', $faqs); 
          return $pdf->download('pending_vendr_query.pdf');
        }
}

public function exportCreditDebit(Request $request)
{
     $credit_debit_notes=DB::table("credit_debit_notes");
     if($request->export_type=="excel")
      {
        $excel = Exporter::make('Excel');
        $excel->loadQuery($credit_debit_notes);
        return $excel->stream('credit_debit_note.xlsx');
      }
      else if($request->export_type=="csv")
      {
         $excel = Exporter::make('Csv');
         $excel->loadQuery($credit_debit_notes);
       return $excel->stream('credit_debit_note.csv');
      }
      else
      {
          $credit_debit_notes=$credit_debit_notes->get();
          $credit_debit_notes = ['credit_debit_notes' => $credit_debit_notes];
          $pdf = PDF::loadView('vendor_view.pdfview.credit_debit_note', $credit_debit_notes); 
          return $pdf->download('credit_debit_note.pdf');
        }
}


}
