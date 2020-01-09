<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenBcodeDetails extends Model
{
    //
public $timestamps = false;
      protected $fillable = [
        'VENDOR_Code', 'Company_Code', 'Plant_Code','Invoice_No','Invoice_Date','Entry_Date','LR_No','Transpoter_Name','PO_Number','PO_Item','Material','Material_Desc','UOM','Quantity','Dispatch_Qty','Packing_Qty','Invoice_Bcode_Img_Name','Barcode_Img_Name',
    ];
}
