<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierStockController extends Controller
{
    public function index()
    {

        $data['title'] = "Supplier Search";
        $data['suppliers'] = Supplier::all();
        return view('backend.supplierStock.supplier', $data);
    }

    public function stock(Request $request)
    {

        if ($request->ajax()) {
            $id = $request->input('supplier_id');
            $products = Product::with(['suppliers', 'category'])->where('supplier_id', $id);
            return DataTables::eloquent($products)
                ->addIndexColumn()
                ->addColumn('photo', function ($product) {
                    $imageUrl = isset($product->photo) ? asset($product->photo) : asset('backend/assets/dist/img/avatar5.png');
                    return '<img src="' . $imageUrl . '" alt="Photo" width="50" height="50">';
                })
                ->addColumn('supplier', function ($category) {
                    return $category->suppliers->name;
                })
                ->addColumn('category', function ($product) {
                    $categoryName = optional($product->category)->name ?? 'N/A';
                    return "<span>{$categoryName}</span>";
                })
                ->addColumn('unit', function ($product) {
                    $unitName = optional($product->unit)->name ?? 'N/A';
                    return "<span>{$unitName}</span>";
                })
                ->addColumn('created_at', function ($product) {
                    return Carbon::parse($product->created_at)->format('Y-m-d');
                })
                ->addColumn('action', function ($product) {
                    return '
                       <div class="action">
                            <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('admin.product.all.show', ['all' => $product->id]) . '">View</a>
                            </div>
                        </div>
                       </div>
                    ';
                })
                ->rawColumns(['action', 'photo', 'supplier', 'unit', 'category'])
                ->make(true);
        }

        $data['title'] = "Supplier Search Result";
        $data['supplier'] = Supplier::findOrFail($request->supplier_id);
        return view('backend.supplierStock.list', $data);
    }
}
