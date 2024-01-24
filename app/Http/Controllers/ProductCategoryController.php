<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public $model;
    public $routename;
    public $tamplate;
    public $db_table = 'product_categories';
    public $upload_file_path;
    public function __construct()
    {
        $this->model = new ProductCategory();
        $this->routename = "product-category.index";
        $this->tamplate = "pages.product-category";
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
        // 👉 CHACK Validation
        $request->validate([
            'name'             => "required|unique:$this->db_table,name",
            'parent_category'  => "nullable|int",
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:512', // image rule with allowed mime types and maximum size
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
            if ($request->image) $data->image = fileUpload($request->image, $this->upload_file_path, $data->image);
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
            $data =  $this->model->with('child_category')->find($id);
            if (!$data) return error_message('data Not Found');
            if ($data->child_category->count() > 0) return error_message("Unable to delete data against the relation.");
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (Exception $e) {
            // 👉// 👉=======handle DB exception error==========
            return error_message($e->getMessage(), $e->getCode());
        }
    }
}
