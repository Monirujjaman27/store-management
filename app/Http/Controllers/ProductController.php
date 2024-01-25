<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public $model;
    public $routename;
    public $tamplate;
    public $db_table = 'products';
    public $upload_file_path;
    public function __construct()
    {
        $this->model = new Product();
        $this->routename = "products.index";
        $this->tamplate = "pages.products";
        $this->upload_file_path = "upload/$this->db_table";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 👉
        // if (!auth()->user()->can('Managers')) return error_message('You Have No Access Permission', 'Unauthorize permissons', 403);
        try {
            $data = $this->model->when($request, function ($q) use ($request) {
                return $q->orderBy('id', $request->has('orderBy') ? $request->order_by : 'desc');
            })
                ->when($request, function ($q) use ($request) {
                    if ($request->date_range) {
                        return $q->whereBetween('created_at', date_range_search($request->date_range));
                    }
                })
                ->when($request->search_query, function ($q) use ($request) {
                    return $q->where('name', 'LIKE', '%' . $request->search_query . '%');
                })
                ->latest()->paginate($request->item ?? 10);
            return view("$this->tamplate.index", compact('data'));
        } catch (Exception $e) {
            // 👉// 👉=======handle DB exception error==========
            return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $categories = ProductCategory::select('id', 'name', 'parent_category_id')->where(['parent_category_id' => null])->get();
            return view("$this->tamplate.addEdit", compact('categories'));
        } catch (\Throwable $e) {
            return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // 👉 CHACK Validation
        $request->validate([
            'name'      => "required|unique:$this->db_table,name",
            'category'  => "required|int",
            'purchase_price'  => "required|int",
            'sales_price'  => "required|int",
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:512', // image rule with allowed mime types and maximum size
        ]);

        DB::beginTransaction();
        try {
            // 👉=============save data================
            $data = $this->model;
            $data->name  = $request->name;
            $data->category_id = $request->has('category') ? $request->category : null;
            $data->purchase_price = $request->purchase_price;
            $data->sales_price = $request->sales_price;
            if ($request->has('is_offer') && $request->is_offer == 1) {
                $data->is_offer = $request->is_offer;
                $data->offer_price = $request->offer_price;
            }
            $data->details = $request->details;
            // 👉======= handle Image Upload ==========
            if ($request->images && count($request->images) > 0) {
                $images = [];
                foreach ($request->images as $key => $img_item) {
                    $images[] = fileUpload($img_item, 'product/images');
                }
                $data->images =  json_encode($images);
            }
            $data->save();
            DB::commit();
            notify()->success("Created Successfully");
            return redirect()->route("$this->routename");
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            // 👉=======handle DB exception error==========
            return error_message('Database Exception Error', $th->getMessage(), $th->getCode());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
        } catch (Exception $e) {
            // 👉// 👉=======handle DB exception error==========
            return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
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
            $categories = ProductCategory::select('id', 'name', 'parent_category_id')->where('id', '!=', $id)->where(['parent_category_id' => null])->get();
            $data = $this->model->find($id);
            if (!$data) return abort(404);
            return view("$this->tamplate.addEdit", compact('data', 'categories'));
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => "required|unique:$this->db_table,name,$id",
            'parent_category'  => "nullable|int",
        ]);

        try {
            DB::beginTransaction();
            // 👉=============save data================
            $data = $this->model->find($id);
            if (!$data) return error_message('data Not found');
            $data->name     = $request->name;
            $data->parent_category_id = $request->has('parent_category') ? $request->parent_category : null;
            if ($request->images) $data->image = fileUpload($request->images, $this->upload_file_path, $data->image);
            $data->save();
            DB::commit();
            notify()->success("Updated Successfully");
            return redirect()->route("$this->routename");
        } catch (Exception $e) {
            DB::rollBack();
            // 👉// 👉=======handle DB exception error==========
            return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data =  $this->model->with('purchase_items', 'sales_items')->find($id);
            if (!$data) return error_message('data Not Found');
            if ($data->purchase_items->count() > 0 || $data->sales_items->count() > 0) return error_message("Unable to delete data against the relation.");
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (Exception $e) {
            // 👉// 👉=======handle DB exception error==========
            return error_message($e->getMessage(), $e->getCode());
        }
    }
}
