<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status', 'cleaned_name'];


    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
