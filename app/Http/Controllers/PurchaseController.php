<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public $model;
    public $modelName;
    public $routename;
    public $table;
    public $tamplate;
    public $upload_file_path;
    public function __construct()
    {
        $this->model = new Purchase();
        $this->modelName = "Purchase";
        $this->routename = "purchase.index";
        $this->table = "purchases";
        $this->tamplate = "pages.purchase";
        $this->upload_file_path = "upload/$this->table/avater";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->model->with('supplier', 'purchase_items')
                ->when($request, function ($q) use ($request) {
                    if ($request->date_range) {
                        return $q->whereBetween('created_at', date_range_search($request->date_range));
                    }
                })

                ->when($request->search_query, function ($q) use ($request) {
                    $searchQuery = '%' . $request->search_query . '%';
                    return $q->where('total', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere('inv_no', 'LIKE', '%' . $searchQuery . '%');
                })
                ->latest()->paginate($request->item ?? 10);
            // dd($data);
            return view("$this->tamplate.index", compact('data'));
        } catch (\Throwable $th) {
            dd($th);
            notify()->warning($th->getMessage());
            return back();
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product::select('id', 'name', 'sku', 'purchase_price', 'sales_price')->get();
        $supplier = Supplier::select('id', 'name')->get();
        return view("$this->tamplate.addEdit", compact('product', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => "required|int",
            'product_id' => "required|array",
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model;
            $data->inv_no = "inv_" . time();
            $data->supplier_id = $request->supplier;
            $data->total = $request->total;
            $data->subtotal = $request->total;
            $data->save();
            foreach ($request->product_id as $index => $item) {
                $product = Product::where('id', $item)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity += $request->qty[$index];
                    $product->save();
                    PurchaseItem::create([
                        'purchase_id' => $data->id,
                        'supplier_id' => $data->supplier_id,
                        'product_id' => $item,
                        'batch' => $request->batch_no[$index],
                        'qty' => $request->qty[$index],
                        'sale_price' => $request->sales_price[$index],
                        'purchase_price' => $request->purchase_price[$index],
                        'total_price' => $request->subtotal[$index],
                    ]);
                }
            }
            DB::commit();
            notify()->success("Purchase Successfully");
            return redirect()->route("$this->routename");
        } catch (\Throwable $th) {
            DB::rollBack();
            notify()->warning($th->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Purchase::with('supplier', 'purchase_items', 'purchase_items.product')->find($id);

            if (!$data) return error_message('data Not Found');
            return view("$this->tamplate.invoice", compact('data'));
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_invoice($id)
    {
        try {
            $data = Purchase::with('supplier', 'purchase_items', 'purchase_items.product')->find($id);

            if (!$data) return error_message('data Not Found');
            return view("$this->tamplate.print", compact('data'));
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_purchase_item($id)
    {
        try {
            $data = PurchaseItem::find($id);
            if (!$data) return error_message('data Not Found');
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data = Purchase::with('purchase_items', 'purchase_items.product')->find($id);
            if (!$data) return error_message('data Not Found');
            $product = Product::select('id', 'name', 'sku', 'purchase_price', 'sales_price')->get();
            $supplier = Supplier::select('id', 'name')->get();
            return view("$this->tamplate.addEdit", compact('product', 'supplier', 'data'));
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier' => "required|int",
            'product_id' => "required|array",
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model->find($id);
            if (!$data) return error_message('data not Found');
            $data->supplier_id = $request->supplier;
            $data->total = $request->total;
            $data->subtotal = $request->total;
            $data->save();

            $purchase_item = PurchaseItem::where(['purchase_id' => $data->id, 'supplier_id' => $data->supplier_id])->get();
            foreach ($purchase_item as $key => $p_item) {
                $product = Product::where('id', $p_item->product_id)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity -= $p_item->qty;
                    $product->save();
                }
                $p_item->delete();
            }

            foreach ($request->product_id as $index => $item) {
                $product = Product::where('id', $item)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity += $request->qty[$index];
                    $product->save();
                    PurchaseItem::create([
                        'purchase_id' => $data->id,
                        'supplier_id' => $data->supplier_id,
                        'product_id' => $item,
                        'batch' => $request->batch_no[$index],
                        'qty' => $request->qty[$index],
                        'sale_price' => $request->sales_price[$index],
                        'purchase_price' => $request->purchase_price[$index],
                        'total_price' => $request->subtotal[$index],
                    ]);
                }
            }
            DB::commit();
            notify()->success("Purchase Successfully");
            return redirect()->route("$this->routename");
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            notify()->warning($th->getMessage());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data  = $this->model->with('purchase_items')->find($id);
            if ($data->purchase_items->count() > 0) return error_message('data cannot be delete when present relation chind data');
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
