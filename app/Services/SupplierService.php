<?php

namespace App\Services;

use App\DTOs\SupplierDTO;
use App\Models\Supplier;
use App\Repositories\SupplierRepository;

class SupplierService
{
    // SupplierDTO ; 

    protected SupplierRepository $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function createSupplier($data)
    {
        $data = new SupplierDTO($data);
        return $this->supplierRepository->create($data);
    }

    public function find($id)
    {
        return $this->supplierRepository->find($id);
    }

    public function updateSupplier($data, $id)
    {
        $data = new SupplierDTO($data);
        return $this->supplierRepository->update($data, $id);
    }


    public function deleteSupplier($id)
    {
        $supplier = $this->find($id);
        foreach($supplier->categories as $category){
            if($category->suppliers()->count() == 1){
                $category->delete() ; 
            }
            else{
                $supplier->categories()->detach($category->id) ; 
            }
        }
        return $this->supplierRepository->delete($id);
    }
}
