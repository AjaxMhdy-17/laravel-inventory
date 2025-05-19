<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitCategoryRequest;
use App\Models\Supplier;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $units = Unit::query()->latest();
            return DataTables::eloquent($units)
                ->addColumn('status', function ($unit) {
                    return $unit->status == 1 ? "<span class='badge badge-primary'>Active</span>" : " <span class='badge badge-danger'>In Active</span>";
                })
                ->addColumn('created_at', function ($unit) {
                    return Carbon::parse($unit->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($unit) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.customer.unit.edit', $unit->id) . '">Edit</a>
                                <div class="dropdown-divider"></div>
                                 <form class="delete-form" method="POST" action="' . route('admin.customer.unit.destroy', ['unit' => $unit->id]) . '">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                     <button type="submit" class="dropdown-item text-danger show-alert-delete-box">Delete</button>
                                </form>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = "Unit";
        return view('backend.unit.list', $data);
    }

    public function create()
    {
        $data['title'] = "Unit";
        return view('backend.unit.create', $data);
    }

    public function store(UnitCategoryRequest $request)
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        Unit::create($data);
        $notification = array(
            'message' => "Unit Added Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.unit.index')->with($notification);
    }



    public function edit(string $id)
    {
        $data['title'] = "Unit";
        $data['unit'] = Unit::findOrFail($id);
        return view('backend.unit.edit', $data);
    }


    public function update(UnitCategoryRequest $request, string $id)
    {
        $data = $request->validated();

        if (isset($data['status'])) {
            $data['status'] = $data['status'] == 'on' ? 1 : 0;
        }
        $unit = Unit::findOrFail($id);
        $unit->update($data);
        $notification = array(
            'message' => "Unit Updated Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.unit.index')->with($notification);
    }


    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        $notification = array(
            'message' => "Unit Deleted Successfully !",
            'alert-type' => 'success'
        );
        return redirect()->route('admin.customer.unit.index')->with($notification);
    }
}
