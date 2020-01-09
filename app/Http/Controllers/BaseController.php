<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications;
use App\FAQs; 
use View;
use DB;

class BaseController extends Controller
{
    //
    public function __construct()
    {
         ini_set('max_execution_time', 300);
        $now=date('Y-m-d');
        $nots=Notifications::whereDate('valied_upTo','>=',$now);
        $no_of_notification=$nots->count();
        $notifications=$nots->get();
        $nofaq=FAQs::where('status','open')->count();
       $vendor_faqs = DB::table('faqs')
                ->join('users','faqs.vendorCode', '=', 'users.VenderCode')
                 ->select('users.UserName','faqs.vendorCode', DB::raw('count(*) as total'))
                 ->where('faqs.status','open')
                 ->groupBy('vendorCode')
                 ->get();
                 //return $vendor_faqs;
    View::share(['notifications'=> $notifications,'no_of_notification'=>$no_of_notification,'nofaq'=> $nofaq,'vendor_faqs'=>$vendor_faqs]);
  }
}
