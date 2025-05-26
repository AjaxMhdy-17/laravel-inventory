<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'product_id' , 'category_id' , 'supplier_id' , 'supplier' , 'category' , 'product' , 'unit_price' , 'status' , 'purchase_no' , 'description' , 'buying_qty' , 'price'] ; 
}

