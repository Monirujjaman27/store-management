<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public $model;
    public $routename;
    public $table;
    public $tamplate;
    public $upload_file_path;
    public function __construct()
    {
        $this->model = new Sale();
        $this->routename = "sale.index";
        $this->table = "sales";
        $this->tamplate = "pages.sales";
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
            $data = $this->model->with('customer', 'sale_items')
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
        $product = Product::select('id', 'name', 'sku', 'purchase_price', 'sales_price', 'stock_quantity')->get();
        $customer = Customer::select('id', 'name', 'phone')->get();
        return view("$this->tamplate.addEdit", compact('product', 'customer'));
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
            'customer' => "required|int",
            'product_id' => "required|array",
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model;
            $data->inv_no = "inv_" . time();
            $data->customer_id = $request->customer;
            $data->total = $request->total;
            $data->subtotal = $request->total;
            $data->save();
            foreach ($request->product_id as $index => $item) {
                $product = Product::where('id', $item)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity -= $request->qty[$index];
                    $product->save();
                    SalesItem::create([
                        'sale_id' => $data->id,
                        'customer_id' => $data->customer_id,
                        'product_id' => $item,
                        'qty' => $request->qty[$index],
                        'price' => $request->price[$index],
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = $this->model->with('customer', 'sale_items', 'sale_items.product')->find($id);

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
            $data = $this->model->with('customer', 'sale_items', 'sale_items.product')->find($id);

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
            $data = SalesItem::find($id);
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
            $data = $this->model->with('customer', 'sale_items', 'sale_items.product')->find($id);
            if (!$data) return error_message('data Not Found');
            $product = Product::select('id', 'name', 'sku', 'purchase_price', 'sales_price', 'stock_quantity')->get();
            $customer = Customer::select('id', 'name', 'phone')->get();
            return view("$this->tamplate.addEdit", compact('product', 'customer', 'data'));
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
            'customer' => "required|int",
            'product_id' => "required|array",
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model->find($id);
            if (!$data) return error_message('data not Found');
            $data->customer_id = $request->customer;
            $data->total = $request->total;
            $data->subtotal = $request->total;
            $data->save();

            $purchase_item = SalesItem::where(['sale_id' => $data->id, 'customer_id' => $data->customer_id])->get();
            foreach ($purchase_item as $key => $p_item) {
                $product = Product::where('id', $p_item->product_id)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity += $p_item->qty;
                    $product->sales_quantity =  $product->sales_quantity -= $p_item->qty;
                    $product->save();
                }
                $p_item->delete();
            }

            foreach ($request->product_id as $index => $item) {
                $product = Product::where('id', $item)->first();
                if ($product) {
                    $product->stock_quantity =  $product->stock_quantity -= $request->qty[$index];
                    $product->sales_quantity =  $product->sales_quantity += $request->qty[$index];
                    $product->save();
                    SalesItem::create([
                        'sale_id' => $data->id,
                        'customer_id' => $data->customer_id,
                        'product_id' => $item,
                        'qty' => $request->qty[$index],
                        'price' => $request->price[$index],
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
            $data  = $this->model->with('sale_items')->find($id);
            if ($data->sale_items->count() > 0) return error_message('data cannot be delete when present relation chind data');
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
