<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use App\User;
use Redirect;
use DB;
use App\VendorRegistration;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Company;use App\Countries;use App\States;
use App\Cities;use App\vendorContacts;use App\vendorReferances;
use App\vendorCertifications;use App\vendorCapcityDtls;
use App\Business_types;
use Response;

class NewVendorController extends Controller
{
    //

    public function newRegFrm()
    {
        return view('admin_view.new_Reg_Frm'); 
    }

   public function forgetpass_frm()
    {
        return view('admin_view.forget_pass'); 
     }
public function change_new_vendor_password(Request $request)
{
     $this->validate($request, 
           [
             'vend_mob'     => 'required',
          ],
          [
           'vend_mob.required'=>"* Mobile No. field is required",
           
         ]);
       date_default_timezone_set("Asia/Kolkata");
      $date = date('Y-m-d H:i:s');
      $mob = $request->vend_mob;
      $otp = rand(100000, 999999);
      $request->session()->put('user_mobile_no',$mob);
      $request->session()->put('user_otp',$otp);
      $msg="Your otp for forgot password is ".$otp;
      $resp= $this->send_otp($mob,$otp,$msg);
        if($resp==200)
       {
       DB::update('update vendor_registrations set otp = ? , otp_date_time=?  where mobile = ?',[$otp,$date,$mob]);
       $request->session()->forget('error_msg');
          Session::flash('Success', 'Otp sent success please check your mobile!'); return redirect()->route('ven_resend_otp_frm');

      }
     else 
     {
     Session::flash('error','Unable to send otp.. Please try again'); 
        return redirect()->route('ven_resend_otp_frm');
     }

}
  public function send_otp($mobile_no,$otp,$msg)
  {
     
     $client = new \GuzzleHttp\Client();
     $res = $client->get('http://nimbusit.co.in/api/swsend.asp', ['form_params' => ['username' =>'t1termserpapi','password' =>'34682252','sender'
      =>'TERMSE','sendto' =>$mobile_no,'message' =>$msg]]);
       $resp=$res->getStatusCode(); // 200
      return  $resp;

  }
    public function registerNewVendor(Request $request)
    {
      if($request->next_step=="get_otp")
      {
           $this->validate($request, 
           [
             'vend_mob'     => 'required',
          ],
          [
           'vend_mob.required'=>"* Mobile No. field is required",
           
         ]);

          $mob = $request->vend_mob;
            $test=DB::table('vendor_registrations')->where('mobile',$mob)->where('password','!=','')->first();
     
       if(!empty($test))
       {
         $this->validate($request, 
           [
             'vend_mob'     => 'required|unique:vendor_registrations,mobile',
          ],
          [
           'vend_mob.required'=>"* Mobile No. field is required",
           'vend_mob.unique'=>"* Mobile No. has already beeen taken",
         ]);
        
       }
       else
       {
 
      $otp = rand(100000, 999999);
      $request->session()->put('user_mobile_no',$mob);
      $request->session()->put('user_otp',$otp);
      $msg="Your otp for vendor registration is:".$otp;
      $resp= $this->send_otp($mob,$otp,$msg);
 //$resp=200;
     if($resp==200)
     {
         $vendcode=$this->getVendorCode();
         date_default_timezone_set("Asia/Kolkata");
         $date = date('Y-m-d H:i:s');
         $check_data=DB::table('vendor_registrations')->where('mobile',$mob)->first();
         if(empty($check_data))
         {
          $data=array('vendor_code'=>$vendcode,'mobile'=>$mob,'otp'=>$otp,'otp_date_time'=>$date);
           DB::table('vendor_registrations')->insert($data);
         }
         else
         {
           DB::update('update vendor_registrations set otp = ? , otp_date_time=?  where mobile = ?',[$otp,$date,$mob]);

         }

           $request->session()->forget('error_msg');
          Session::flash('Success', 'Otp sent success please check your mobile!'); return redirect()->route('ven_resend_otp_frm');
     }
    else 
     {
     Session::flash('error','Unable to send otp.. Please try again'); 
        return redirect()->route('ven_resend_otp_frm');
     }
    } 
  }
   else
   {
        /*$user=$request->get('log_mob');
        $password= $request->get('vend_pass');
         $vendor=VendorRegistration::where('mobile',$user)
        ->where('password',$password)->first();
      if(!empty($vendor))
        {

           $request->session()->forget('user_mobile_no');
           $request->session()->put('vend_code',$vendor->vendor_code);
          return redirect()->route("get_vendor_reg_frm");  
        }
      else
       {
        return back()->with('log_error','Invalied Login Credential');
      }*/

   
        
       
      $this->validate($request, [
            'log_mob'   => 'required',
            'vend_pass' => 'required'
        ]);
 
      //  return $request->all();
        
        if (Auth::guard('registerd_vendor')->attempt(['mobile' => $request->log_mob, 'password' => $request->vend_pass])) {

              $vendor_basic=VendorRegistration::where('mobile',$request->log_mob)->first();
             session ([ 'vendor_data' => $vendor_basic ]);
              return redirect()->route("get_vendor_reg_frm");
            
        }
       return back()->with('log_error','Invalied Login Credential');

       
   }


    }
    public function resendOtpFrm()
    {
       return view('admin_view.otp_frm'); 
    }
    public function resend_otp(Request $request)
    {
      $mob=session('user_mobile_no');
      $otp=session('user_otp');
      $data=VendorRegistration::where('mobile',$mob)->where('otp',$otp)->first();
       $msg="Your otp for vendor registration is:".$otp;
      if(empty( $data))
      {
        
      $resp= $this->send_otp($mob,$otp,$msg);
      if($resp==200)
      {
        
        date_default_timezone_set("Asia/Kolkata");
        $date = date('Y-m-d H:i:s');
        $data=array('vendor_code'=>$vendcode,'mobile'=>$mob,'otp'=>$otp,'otp_date_time'=>$date,'password'=>$otp);
         DB::table('vendor_registrations')->insert($data);
         $request->session()->forget('error_msg');
         Session::flash('success_msg', 'Otp sent success please check your mobile!'); 
         //return view('admin_view.otp_frm'); 
         return redirect()->route('ven_resend_otp_frm');

      }
      else
      {
         $request->session()->forget('success_msg');
         Session::flash('error_msg','Unable to send otp.. Please try again'); 
        // return view('admin_view.otp_frm'); 
         return redirect()->route('ven_resend_otp_frm');
         
      }
    }
    else
    {
      $resp= $this->send_otp($mob,$otp,$msg);
      if($resp==200)
      {
       $request->session()->forget('error_msg');
       Session::flash('success_msg', 'Otp sent success please check your mobile!'); 
        return redirect()->route('ven_resend_otp_frm');
       }
       else
       {
        $request->session()->forget('success_msg');
        Session::flash('error_msg','Unable to send otp.. Please try again'); 
       return redirect()->route('ven_resend_otp_frm');
       }
    }

    }

  function set_Password(Request $request)
  {
      $this->validate($request, 
           [
              'password' => 'required|confirmed|min:3',
          ],
          [
           'password.required'=>"* Password field is required",
           'password.confirmed'=>"* Password and confirmed password must matched",
         ]);
        $pass=Hash::make($request->password);
       
       $user_mob=session('user_mobile_no');
        DB::update('update vendor_registrations set password = ? where mobile = ?',[$pass,$user_mob]);
        return redirect()->route('register_New_Vendor');
  }
   public function get_set_Password_frm()
   {
      
    return view('admin_view.set_new_vend_pass'); 
   }
    public function validateOtp(Request $request)
    {
      date_default_timezone_set("Asia/Kolkata");
      $date = date('Y-m-d H:i:s');
      $user_mob=session('user_mobile_no');
      $otp=$request->otp;
      $data=VendorRegistration::where('mobile',$user_mob)->where('otp',$otp)->first();
      if(!empty($data))
      {
            $verified='Y';
            DB::update('update vendor_registrations set is_verified=? where mobile = ?',[$verified,$user_mob]);
            $request->session()->forget('error_msg');
            Session::flash('success_msg', 'Mobile verification Success!'); 
             return redirect()->route('set_Password_frm');
        /*  $send_time=strtotime($data->otp_date_time);
            $cur_time= strtotime($date);
            $diff=($cur_time-$send_time)/60;*/
      }
      else
      {
        $request->session()->forget('success_msg');
        Session::flash('error_msg', 'The OTP entered is incorrect'); 
         return view('admin_view.otp_frm'); 
      }
      
    }

    public function get_state(Request $request)
    {
      //return $request->country;
      $states=States::where('c_Code',$request->country);
       $count=$states->count();
      $states=$states->get();
     return response()->json([ 'count' =>$count ,'states' =>  $states]);
      
    }
     public function get_city(Request $request)
     {
      $cities=Cities::where('st_Code',$request->state);
      $count=$cities->count();
      $cities=$cities->get();
      return response()->json([ 'count' =>$count ,'cities' =>  $cities]);
  
     }
     public function getVendorRegdetails(Request $request)
    {
       $countries=Countries::all();
       $btypes=Business_types::all();
       $logged_vendor=session('vendor_data');
       $vcode= $logged_vendor->vendor_code;
       $vendor_basic=VendorRegistration::where('vendor_code',$vcode)->first();
        $vendor_contacts=vendorContacts::where('vendorcode',$vcode)->get();
       $vendor_ref=vendorReferances::where('vendorcode',$vcode)->get();
       $vendor_cert=vendorCertifications::where('vendorcode',$vcode)->get();
       $vendor_cap=vendorCapcityDtls::where('vendorcode',$vcode)->first();
        $states=States::where('c_Code',$vendor_basic->country)->get();
       $cities=Cities::where('st_Code',$vendor_basic->state)->get();
       
        return view('admin_view.vendor_reg_frm',
        compact('countries','btypes','states','cities','vendor_basic','vendor_contacts','vendor_ref','vendor_cert','vendor_cap'));

    }
     public function getVendorCode()
    {
       $code="";
        $vend= DB::table('vendor_registrations')->where('vendor_code', \DB::raw("(select max(`vendor_code`)  from vendor_registrations)"))->first();
     //  $vend=VendorRegistration::orderBy('id', 'desc')->skip(1)->take(1)->first();
       if(empty($vend))
       {
       $code=1;
       }
       else
       {
        $code=$vend->vendor_code+1;
        
        }
     return $code;

    }
    public function creteVendorRequest(Request $request)
    {
     
  
        if($request->save=="save_basic_info")
        {
         $this->validate($request, 
           [
              'vendor_name'   =>        'required',
              'btype'         =>        'required',
              'country'       =>        'required',
              'state'         =>        'required',
              'city'          =>        'required',
              'address_1'     =>        'required',
              'address_2'     =>        'required',
              'address_3'     =>        'required',
              'gst_no'        =>        'required',
              'pan_no'        =>        'required',
              'cin_no'        =>        'required',
              'proprietor'    =>        'required',
              'email'         =>        'required',
              'phone'         =>        'required',
              'fax'           =>        'required',
           'nature_of_business' =>       'required',
          ],
          [
           'vendor_name.required'   =>"* Vendor name field is required",
           'btype.required'         => "* Vendor business type field is required",
           'country.required'       =>"* Country field is required",
           'state.required'         =>"* State field is required",
           'city.required'          =>"* City field is required",
           'address_1.required'     =>"* Address 1 field is required",
           'address_2.required'     =>"* Address 2 field is required",
           'address_3.required'     =>"* Address 3 field is required",
           'gst_no.required'        =>"* GST Number field is required",
           'pan_no.required'        =>"* PAN Number field is required",
            'cin_no.required'       =>"* CIN Number field is required",
            'proprietor.required'   =>"* Proprietor Name  field is required",
            'email.required'        =>"* Email  field is required",
            'phone.required'        =>"* Phone  field is required",
            'fax.required'          =>"*Fax field is required",
            'nature_of_business.required'=>"* Nature of business  field is required",
         ]);
         if ($request->session()->has('user_mobile_no')) 
         {
            $user_mob=session('user_mobile_no');
            
         }
         else
         {
           $vendcode=session('vend_code');
           $user_mob= $request->mob;
         }
          
          $vend_status=1;
          $data=array(
                  $request->vendor_name,
                 
                  $request->country,
                  $request->state,
                  $request->city,
                  $request->address_1,
                  $request->address_2,
                  $request->address_3,
                  $request->gst_no,
                  $request->pan_no,
                  $request->cin_no,
                  $request->proprietor,
                  $request->email,
                  $request->phone,
                  $request->fax,
                  $request->btype,
                  $request->nature_of_business,
                  $vend_status,
                  $user_mob
             );
         DB::update('update vendor_registrations set vendor_name=?,country=?,state=?,city=? ,address_1=?,address_2=?,address_3=?,gst_no=?,pan_no=?,cin_no=?,proprietor=?,email=?,phone_no=?,fax=?,business_type=?,nature_of_business=?,vendor_status=? where mobile = ?',$data);
          $request->session()->forget('error_msg');
            Session::flash('success_msg', 'Vendor basic info added success !'); 
             return redirect()->route('get_vendor_reg_frm');
           }
           if($request->save=="save_contact_info")
           {
             if($request->session()->has('user_mobile_no')) 
             {
               $user_mob=session('user_mobile_no');
               $vend=VendorRegistration::where('mobile', $user_mob)->first();
               $vend_code=$vend->vendor_code;
            }
            else
               {
               $vend_code=session('vend_code');
               $con_del=vendorContacts::where('vendorcode',$vend_code)->delete();
              }
          
             for($i=0;$i<count($request->cname);$i++)
             {
              $data=[
                'vendorcode'=>$vend_code,'name'=>$request->cname[$i],
                'phone'=>$request->cfon[$i],'mobile'=>$request->cmob[$i],
                'email'=>$request->cemail[$i],'designation'=>$request->cdesig[$i],
                'department'=>$request->cdepart[$i],"created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()];
               DB::table('vendor_contacts')->insert($data);
                
             }
            $request->session()->forget('error_msg');
          Session::flash('success_msg', 'Contact details added success !');return redirect()->route('get_vendor_reg_frm');
           }
           if($request->save=="save_referance")
           {
             if($request->session()->has('user_mobile_no')) 
             {
               $user_mob=session('user_mobile_no');
               $vend=VendorRegistration::where('mobile', $user_mob)->first();
               $vend_code=$vend->vendor_code;
            }
            else
               {
               $vend_code=session('vend_code');
              $ref_del=vendorReferances::where('vendorcode',$vend_code)->delete();
              }
         
             for($i=0;$i<count($request->refname);$i++)
             {
              $data=[
                'vendorcode'=>$vend_code,'name'=>$request->refname[$i],
                'address'=>$request->refaddr[$i],'contact'=>$request->refcont[$i],
                'remarks'=>$request->refrem[$i],"created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()];
               DB::table('vendor_referances')->insert($data);
                
             }
            $request->session()->forget('error_msg');
          Session::flash('success_msg', 'Referance details added success !');return redirect()->route('get_vendor_reg_frm');
           }
           if($request->save=="save_certificate")
           {
   
            if($request->session()->has('user_mobile_no')) 
             {
               $user_mob=session('user_mobile_no');
               $vend=VendorRegistration::where('mobile', $user_mob)->first();
               $vend_code=$vend->vendor_code;
              $cert_count=0;
            }
            else
               {
               $vend_code=session('vend_code');
                $certi=vendorCertifications::where('vendorcode',$vend_code);
                $cert_count=$certi->count();
                $del_cert=$certi->delete();
             
              }
      
            $file= $request->file('certdoc');
             for($i=0;$i<count($request->certname);$i++)
             {
               $cert=new vendorCertifications();
              if(!empty($file[$i]))
              {

                $fileExtension = $file[$i]->getClientOriginalExtension();
                $name=$vend_code."_".$request->certname[$i]."_docs_".strtotime("now").".".$fileExtension;
                $file[$i]->move(public_path().'/vendor_docs/', $name);  
                $cert->uploads=$name; 
                 if($i<$cert_count)
                 {
              $p="vendor_docs/".trim($request->file_name[$i]);
                if(file_exists($p))
                {
                    @unlink($p);
                 }
               }
              }
              else
              {
                 $cert->uploads=$request->file_name[$i]; 
              }
       
              $cert->vendorcode=$vend_code;
              $cert->name=$request->certname[$i];
              $cert->valid_up_to=$request->certtupto[$i];
              $cert->remarks=$request->certrmrk[$i];
              $cert->created_at= \Carbon\Carbon::now();
              $cert->updated_at=\Carbon\Carbon::now();
              $cert->save();
              
          }
            $request->session()->forget('error_msg');
          Session::flash('success_msg', 'Certification details  added success !');return redirect()->route('get_vendor_reg_frm');
           }

          if($request->save=="save_capacity")
           {
            if($request->session()->has('user_mobile_no')) 
             {
               $user_mob=session('user_mobile_no');
               $vend=VendorRegistration::where('mobile', $user_mob)->first();
               $vend_code=$vend->vendor_code;
                $data=[
           'vendorcode'=>$vend_code,'capcity_dtl'=>$request->cap_dtl,
          'no_worker'=>$request->no_workr,'no_staff'=>$request->no_staff,
          'no_machine'=>$request->no_machine,"created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()];
               DB::table('vendor_capcity_dtls')->insert($data);
                
              
            }
            else
               {
               $vend_code=session('vend_code');
               $n=vendorCapcityDtls::where('vendorcode',$vend_code)->count();
               if($n>0)
               {
               $data=[
                'capcity_dtl'=>$request->cap_dtl,
               'no_worker'=>$request->no_workr,'no_staff'=>$request->no_staff,
                'no_machine'=>$request->no_machine,"updated_at" => \Carbon\Carbon::now()];    

              DB::table('vendor_capcity_dtls')->where('vendorcode', $vend_code)
              ->update($data);
            }
            else
            {
               $data=[
           'vendorcode'=>$vend_code,'capcity_dtl'=>$request->cap_dtl,
          'no_worker'=>$request->no_workr,'no_staff'=>$request->no_staff,
          'no_machine'=>$request->no_machine,"created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()];
               DB::table('vendor_capcity_dtls')->insert($data);

            }

            }
 
          $request->session()->forget('error_msg');
          Session::flash('success_msg', 'Capcity details  added success !');return redirect()->route('get_vendor_reg_frm');
           }
       }
    public function get_vedor_details_frm()
    {
      return view('admin_view.vendor_reg_frm'); 

    }

}
