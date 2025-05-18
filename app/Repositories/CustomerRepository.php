<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{


    public function getAll()
    {
        return Customer::all();
    }

    public function find(string $id)
    {
        return Customer::findOrFail($id);
    }

    public function create($data)
    {
        return Customer::create($data);
    }

    public function update($customer, $data)
    {
        return $customer->update($data); 
    }

    public function delete($customer)
    {
        return $customer->delete();
    }
}
