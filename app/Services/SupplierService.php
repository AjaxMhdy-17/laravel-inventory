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
        $this->supplierRepository = $supplierRepository ; 
    }

    public function createSupplier($data)
    {
        $data = new SupplierDTO($data);
        return $this->supplierRepository->create($data) ; 
    }
}
