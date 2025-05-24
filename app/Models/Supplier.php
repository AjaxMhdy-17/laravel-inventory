<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone', 'email', 'address'];



    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }



    public function categories(){
        return $this->belongsToMany(Category::class) ; 
    }


    public function product()
    {
        return $this->hasMany(Product::class , 'supplier_id');
    }
}
