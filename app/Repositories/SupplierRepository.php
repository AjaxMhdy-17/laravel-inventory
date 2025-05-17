<?php

namespace App\Repositories;

use App\Dtos\SupplierDTO;
use App\Models\Supplier;

class SupplierRepository
{
    public function getAll() {}
    public function find(int $id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(SupplierDTO $data)
    {
        return Supplier::create([
            'name' => $data->name,
            'phone' => $data->phone,
            'email' => $data->email,
            'address' => $data->address,
        ]);
    }

    public function update() {}

    public function delete() {}
}
