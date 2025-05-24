<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    use HandlesImageUploads;

    // protected CustomerRepository $customerRepository;

    // public function __construct(CustomerRepository $customerRepository)
    // {
    //     $this->customerRepository = $customerRepository;
    // }


    public function createProduct($data)
    {
        if (isset($data['photo'])) {
            $imagePath = $this->uploadImage($data['photo'], 'upload/product', 300, 300);
            $data['photo'] = $imagePath;
        }
        isset($data['status']) ?  $data['status'] = 1 : $data['status'] = 0;
        $data = array_merge(['user_id' => Auth::user()->id], $data);
        return Product::create($data);
        // return $this->customerRepository->create($data);
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function update($data, $id)
    {
        $product = $this->find($id);
        isset($data['status']) ?  $data['status'] = 1 : $data['status'] = 0;
        if (isset($data['photo'])) {
            $this->deleteImage($product->photo);
            $imagePath = $this->uploadImage($data['photo'], 'upload/product', 300, 300);
            $data['photo'] = $imagePath;
        }
        return $product->update($data);
    }

    public function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }

    public function suppliers()
    {
        return Supplier::all();
    }

    public function categories()
    {
        return Category::all();
    }

    public function units()
    {
        return Unit::all();
    }
}
