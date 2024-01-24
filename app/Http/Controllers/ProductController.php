<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;

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
            $data = $this->model->with('parent_category', 'child_category')
                ->when($request, function ($q) use ($request) {
                    return $q->orderBy('id', $request->has('orderBy') ? $request->orderBy : 'desc');
                })
                ->when($request->searchQuery, function ($q) use ($request) {
                    return $q->where('name', 'LIKE', '%' . $request->searchQuery . '%');
                })
                ->latest()->paginate($request->itemsPerPage ?? 10);
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
            $categories = ProductCategory::select('id', 'name', 'parent_category_id')->get();
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
        // 👉 CHACK Validation
        $request->validate([
            'name'             => "required|unique:$this->db_table,name",
            'parent_category'  => "nullable|array",
        ]);

        DB::beginTransaction();
        try {
            // 👉=============save data================
            $data = $this->model;
            $data->name  = $request->name;
            $data->parent_category_id = $request->has('parent_category') ? $request->parent_category : null;
            if ($request->image) $data->image = fileUpload($request->image, $this->upload_file_path);
            $data->save();
            DB::commit();
            notify()->success("Created Successfully");
            return redirect()->route("$this->routename");
        } catch (Exception $e) {
            DB::rollBack();
            // 👉=======handle DB exception error==========
            return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
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
            'parent_category'  => "nullable|array",
            'branche'          => "nullable|array",
            'warehouse'        => "nullable|array",
        ]);

        try {
            DB::beginTransaction();
            // 👉=============save data================
            $data = $this->model->find($id);
            if (!$data) return error_message('data Not found');
            $data->name     = $request->name;
            $data->parent_category_id = $request->has('parent_category') && isset($request->parent_category['value']) ? $request->parent_category['value'] : null;
            if ($request->image) $data->image = fileUpload($request->image, 'product/category', $data->image);
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
            $data =  $this->model->find($id)->delete();
            if ($data->medicine->count() > 0) return error_message("Unable to delete data against the relation.");
            notify()->success("Delete Successfully");
            return back();
        } catch (Exception $e) {
            // 👉// 👉=======handle DB exception error==========
            return error_message($e->getMessage(), $e->getCode());
        }
    }
}
