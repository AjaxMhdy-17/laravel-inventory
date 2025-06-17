<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query()->latest();
            return DataTables::eloquent($customers)
                ->addColumn('photo', function ($customer) {
                    $imageUrl = isset($customer->photo) ? asset($customer->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('created_at', function ($customer) {
                    return Carbon::parse($customer->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($customer) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.customer.all.edit', $customer->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.customer.all.destroy', ['all' => $customer->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
        $data['title'] = "Customer";
        return view('backend.customer.list', $data);
    }

    public function create()
    {
        $data['title'] = "Customer";
        return view('backend.customer.create', $data);
    }

    public function store(Request $request)
    {
        $data = $this->dataValidation($request);
        $this->customerService->createCustomer($data);

        $notification = array(
            'message' => "Customer Created Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.all.index')->with($notification);
    }

    public function edit(string $id)
    {
        $data['title'] = "Customer";
        $data['customer'] = $this->customerService->find($id);
        return view('backend.customer.edit', $data);
    }


    public function update(Request $request, string $id)
    {
        $data = $this->dataValidation($request, $id);
        $this->customerService->updateCustomer($data, $id);
        $notification = array(
            'message' => "Customer Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.all.index')->with($notification);
    }

    public function destroy(string $id)
    {

        $this->customerService->deleteCustomer($id);
        $notification = array(
            'message' => "Customer Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.all.index')->with($notification);
    }

    public function dataValidation($request, $id = null)
    {
        return $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('customers')->ignore($id)
            ],
            'phone' => 'required',
            'address' => 'required',
            'photo' => 'sometimes'
        ]);
    }
}
