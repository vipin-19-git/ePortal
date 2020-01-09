<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryDelayInfo extends Model
{
   public $timestamps = true;
    protected $fillable = ['Company_Code', 'Plant_Code', 'Vendor_Code','Supplier_Name','From_Date','To_Date','PO_Number','Supplier_Cont_Person','Material_Code_Desc','Avl_Qty','Reason','Entry_Date','created_at']; 
}
