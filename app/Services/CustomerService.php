<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Traits\HandlesImageUploads;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CustomerService
{
    use HandlesImageUploads ; 

    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }


    public function createCustomer($data)
    {
        if (isset($data['photo'])) {
            $imagePath = $this->uploadImage($data['photo'],'upload/customer',300,300) ; 
            $data['photo'] = $imagePath ; 
        }
        return $this->customerRepository->create($data);
    }

    public function find($id)
    {
        return $this->customerRepository->find($id);
    }

    public function updateCustomer($data, $id)
    {
        $customer = $this->customerRepository->find($id) ; 
        if($data['photo']){
            $this->deleteImage($customer->photo) ;
            $imagePath = $this->uploadImage($data['photo'],'upload/customer',300,300) ; 
            $data['photo'] = $imagePath ;
        }

        return $this->customerRepository->update($customer , $data,);
    }


    public function deleteCustomer($id) 
    {
        $customer = $this->find($id);
        $this->deleteImage($customer['photo']) ; 
        return $this->customerRepository->delete($customer);
    }
}
