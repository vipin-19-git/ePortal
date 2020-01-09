<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {

    return view('welcome');
    
});
Route::get('/login', 'AdminController@index');
Route::post('/login', 'AdminController@vendor_login')->name('login');
Route::get('/Admin_login', 'AdminController@index2')->name('admin_login');
Route::post('/Admin_login', 'AdminController@admin_login')->name('admin_authenticate');

Route::get('/forgotpassword', 'AdminController@forgotpass')->name('forGetPassword');
Route::post('/forgotpassword', 'AdminController@sendresetlink')->name('sendResetLink');
Route::get('/resetpassword/{vendorcode}/{token}', 'AdminController@resetPassword');

Route::group(['prefix' => 'admin'], function()
{ 
	Route::group(['middleware'=>'auth'],function()
     {

Route::get('/dashboard', 'AdminController@admin_dash')->name('admin.dash');
Route::get('/Pending_Request', 'AdminController@showPendingRegReq')->name('admin.show_pending_reg_req');
Route::post('/Pending_Request','ExportController@exportPendingNewVendor')->name('admin.exprt_pndng_New_Vndr');
Route::get('/Hold_Request', 'AdminController@showHoldRegReq')->name('admin.show_Hold_reg_req');
Route::post('/Hold_Request','ExportController@exportHoldNewVendor')->name('admin.exprt_hold_New_Vndr');
Route::get('/Pending_Query', 'AdminController@showPendingQuery')->name('admin.show_pending_query');
Route::post('/Pending_Query','ExportController@exportPendingQuery')->name('admin.exprt_pndng_qry');
Route::get('/Delivery_Schedule', 'AdminController@delivery_Schedule_Frm')->name('admin.delivery_schedule');
Route::post('/Delivery_Schedule/{page}', 'AdminController@delivery_Schedule')->name('admin.get_schedule');
Route::get('/Show_Query', 'AdminController@showVendorQuery')->name('admin.show_vendor_query');
Route::get('/VendorWise_Query', 'AdminController@showVendorWiseQuery')->name('admin.show_vendorWise_query');
Route::post('/Show_Query', 'AdminController@vendorQueryAns')->name('admin.vendor_query_ans');
Route::post('/Get_Query_data', 'AdminController@get_queryData')->name('admin.get_queryData');
Route::get('/Upload_Vendor', 'AdminController@get_upload_Vendor_Frm')->name('admin.upload_vendor');
Route::post('/downloadVendFormat', 'AdminController@downloadVendRegFormat')->name('admin.vendRegFormat');
Route::get('/Add_Vendor', 'AdminController@get_Add_Vendor_Frm')->name('admin.add_vendor');
Route::post('/Add_Vendor', 'AdminController@createVendor')->name('admin.create_Vendor');
 Route::post('/check_vendor','AdminController@is_available_vendor')->name('admin.is_available_vndr');
 Route::get('/Edit_Vendor','AdminController@editVendor')->name('admin.edit_vendor');
Route::post('/Edit_Vendor','AdminController@updateVendor')->name('admin.update_vendor');
Route::post('/Delete_Vendor','AdminController@del_Vendor')->name('admin.delete_Vendor');
Route::post('/Vendor_status','AdminController@change_vendor_status')->name('admin.change_vnd_status');
Route::get('/Delete_Invoice', 'AdminController@get_Del_Inv_Frm')->name('admin.delete_invoice_frm');
Route::post('/Delete_Invoice', 'AdminController@delInvoice')->name('admin.delete_invoice');
Route::get('/Add_Notifications', 'AdminController@newNotificationForm')->name('admin.add_notification_frm');
Route::post('/Add_Notifications', 'AdminController@addNewNotifications')->name('admin.add_notification');
Route::get('/Add_VPolicy', 'AdminController@newVendorPolicy')->name('admin.add_vendorPolicy_frm');
Route::post('/Add_VPolicy', 'AdminController@add_new_policy')->name('admin.add_vpolicy');
Route::post('/Get_VPolicy', 'AdminController@downloadVendorPolicy')->name('admin.getVendorPolicy');
Route::post('/Change_VpStatus', 'AdminController@updateVpolicyStatus')->name('admin.updateVendorPolicy');
Route::get('/New_Registration', 'AdminController@show_New_Vend_Reg_Request')->name('admin.show_New_Vendor_Request');
Route::post('/New_Registration', 'AdminController@changeNewVendorStatus')->name('admin.change_vendor_Status');
Route::post('/Get_Vendor_Reg_Data','AdminController@getVendorRegData')->name('admin.get_vendor_reg_data');
Route::post('/downloadCertificate','AdminController@downloadVendCertificate')->name('admin.getCertificate');
Route::get('/EmailWorkFlow','AdminController@getEmailWorkFlow')->name('admin.getWorkFlow'); 
Route::post('/EmailWorkFlow','AdminController@sendEmailWorkFlow')->name('admin.sendWorkFlow'); 
Route::get('/ChangePassword','AdminController@getChangePassFrm')->name('admin.get_Change_Pass_Frm'); 
Route::post('/ChangePassword','AdminController@updatePassword')->name('admin.update_password'); 
   });   

});

Route::group(['prefix' => 'vendor'], function()
{
 Route::group(['middleware'=>'auth'],function()
    {
      
     Route::get('/home','AdminController@home')->name('vendor.home');
     Route::get('/generated_invoice','AdminController@dash_generated_invoice')->name('vendor.generated_invoice'); 
     Route::get('/delivery_delay_info','AdminController@dash_delivery_delay_info')->name('vendor.delivery_delay_info'); 
     Route::get('/dispatched_po','AdminController@dash_dispactched_po')->name('vendor.dispactched_po');  
     Route::post('/Gen_QrCode', 'VendorController@get_qr_frm')->name('vendor.qrcode');
     Route::get('/Gen_QrCode','VendorController@get_qr_frm')->name('vendor.qrcode'); 
     Route::post('/GenMultiPO_QrCode', 'VendorController@genMultiPoQr')->name('vendor.multiPoqrcode');
     Route::get('/GenMultiPO_QrCode', 'VendorController@genMultiPoQr');
      Route::post('/GetMultiPO_QrCode','VendorController@genMultiPOQrCode') ->name('vendor.generate_MultiPOQCode');
     Route::get('/GetMultiPO_QrCode','VendorController@genMultiPOQrCode')->name('vendor.generate_MultiPOQCode');
     Route::post('/chart_data','AdminController@chart_data')->name('vendor.chart_data'); 
     Route::post('/recently_schedule','AdminController@dash_recent_schedule')->name('vendor.get_recently_schedule');  
     Route::post('/search_schedule','AdminController@dash_search_schedule')->name('vendor.search_sch_data'); 
     Route::get('/Delivery_Schedule','VendorController@delivery_schedule_frm')->name('vendor.delivery_schedule');
     Route::post('/Delivery_Schedule','VendorController@get_delivery_schedule')->name('vendor.get_delivery_schedule');
     Route::post('/Delivery_Schedules','VendorController@get_plant_code')->name('vendor.get_corresponding_plant');
     Route::post('/Delivery_po_head_export','VendorController@export_po_header')->name('vendor.export_header');
     Route::post('/Delivery_po_detail_export', 'VendorController@export_po_detail')->name('vendor.export_detail');
     Route::post('/Get_QrCode','VendorController@genQrCode') ->name('vendor.generate_QCode');
     Route::get('/Get_QrCode','VendorController@genQrCode')->name('vendor.generate_QCode');
     Route::post('/check_invoice','VendorController@is_available_invoice')->name('vendor.is_available_invoice');
     Route::get('/Reprint','VendorController@reprint_barCode')->name('vendor.reprint');
     Route::post('/Reprint','VendorController@get_generated_bar_code')->name('vendor.get_generated_bar_code');
     Route::get('/ReconStatement','VendorController@get_recon_stmt_frm')->name('vendor.get_recon_frm');
     Route::post('/ReconStatement','VendorController@generateReconStmt')->name('vendor.generate_Recon_Stmt');
     Route::get('/InvoiceStatus','VendorController@getInvoiceStatusFrm')->name('vendor.get_Invoice_Status_Frm'); 
     Route::post('/InvoiceStatus','VendorController@show_invoice_status')->name('vendor.get_invoice_status'); 
     Route::get('/MaterialReturn','VendorController@getMaterialReturnFrm') ->name('vendor.get_Material_Return_Frm');
     Route::post('/MaterialReturn','VendorController@getMaterialReturnData')->name('vendor.get_Material_Return_Data');
     Route::get('/PaymentAdvice','VendorController@getPaymentAdviceFrm')->name('vendor.get_Payment_Advice_Frm');
     Route::post('/PaymentAdvice','VendorController@payment_advice_details')->name('vendor.get_Payment_Advice_dtl');
     Route::post('/GiveAdvice','VendorController@GivePaymentAdvice')->name('vendor.give_Payment_advice');
     Route::get('/Credit-Debit-Note','VendorController@getCreditDebitFrm')->name('vendor.get_Credit_Debit_Frm');
     Route::post('/Credit-Debit-Note','VendorController@show_Credit_Debit_Note')->name('vendor.show_Credit_Debit_Data');
     Route::get('/DeliveryDelayInfo','VendorController@getDeliveryDelayFrm')->name('vendor.get_Delivery_Delay_Frm'); 
     Route::post('/getPoNumber','VendorController@getPo')->name('vendor.get_all_po');  
     Route::post('/getMatDescrp','VendorController@getMatDescp')->name('vendor.get_Material_Descrp');   
     Route::post('/DeliveryDelayInfo','VendorController@postDeliveryDelayFrm')->name('vendor.post_Delivery_Delay_Frm');
     Route::get('/Vendor_Policy', 'VendorController@v_policy')->name('vendor.Vendor_Policy');  
     Route::get('/AskQuery','VendorController@askQueryFrm')->name('vendor.get_ask_Query_Frm'); 
     Route::post('/AskQuery','VendorController@saveVendorQuery')->name('vendor.post_ask_query'); 
     Route::post('/export_inv_gen','ExportController@exportInvGen')->name('vendor.export_invoice_gen');
     Route::post('/exprt_dlivery_dlay','ExportController@exportDlvryDly')->name('vendor.exprt_deli_dly');
     Route::post('/export_disp_po','ExportController@exportDispatPo')->name('vendor.export_disp_po');

    });

  });

   Route::post('/logout', function ()
    {
      
    Auth::user()->is_login = 0; // Flip the flag to false
    Auth::user()->save();
     Auth::logout();
     return redirect('/login');
   })->name('logout');

      Route::post('/Admin_logout', function ()
    {
      
    Auth::user()->is_login = 0; // Flip the flag to false
    Auth::user()->save();
     Auth::logout();
     return redirect('/Admin_login');
   })->name('admin_logout');