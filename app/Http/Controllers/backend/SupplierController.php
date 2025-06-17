<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Team;
use App\Services\SupplierService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{

    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

  
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Supplier::query()->latest();
            return DataTables::eloquent($suppliers)
                ->addColumn('created_at', function ($supplier) {
                    return Carbon::parse($supplier->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($supplier) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.supplier.edit', $supplier->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.supplier.destroy', ['supplier' => $supplier->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['title'] = "Supplier";
        return view('backend.supplier.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data['title'] = "Supplier Create";
        return view('backend.supplier.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->dataValidation($request);
        $this->supplierService->createSupplier($data);
        $notification = array(
            'message' => "Supplier Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.supplier.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['supplier'] = $this->supplierService->find($id);
        $data['title'] = "Supplier";
        return view('backend.supplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $this->dataValidation($request, $id);
        $this->supplierService->updateSupplier($data, $id);
        $notification = array(
            'message' => "Supplier Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.supplier.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->supplierService->deleteSupplier($id);
        $notification = array(
            'message' => "Supplier Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.supplier.index')->with($notification);
    }

    public function dataValidation($request, $id = null)
    {
        return $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers')->ignore($id)
            ],
            'phone' => 'required',
            'address' => 'required'
        ]);
    }
}
