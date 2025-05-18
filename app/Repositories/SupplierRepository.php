<?php

namespace App\Repositories;

use App\Dtos\SupplierDTO;
use App\Models\Supplier;

class SupplierRepository
{
    public function getAll()
    {
        return Supplier::all();
    }

    public function find(string $id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(SupplierDTO $data)
    {
        return Supplier::create($this->saveData($data));
    }

    public function update(SupplierDTO $data, string $id)
    {
        $supplier = $this->find($id);
        return $supplier->update($this->saveData($data));
    }

    public function delete(string $id)
    {
        $supplier = $this->find($id);
        return $supplier->delete();
    }

    public function saveData($data)
    {
        return [
            'name' => $data->name,
            'phone' => $data->phone,
            'email' => $data->email,
            'address' => $data->address,
        ];
    }
}
