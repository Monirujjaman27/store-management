<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public $model;
    public $modelName;
    public $routename;
    public $table;
    public $tamplate;
    public $upload_file_path;
    public function __construct()
    {
        $this->model = new Supplier();
        $this->modelName = "Supplier";
        $this->routename = "suppliers.index";
        $this->table = "suppliers";
        $this->tamplate = "pages.suppliers";
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
            $data = $this->model->latest()->paginate($request->item ?? 10);
            return view("$this->tamplate.index", compact('data'));
        } catch (\Throwable $th) {
            // dd($th);
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
        return view("$this->tamplate.addEdit");
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
            'name' => "required|unique:$this->table,name",
            'phone' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:512', // image rule with allowed mime types and maximum size
        ]);
        try {
            $data = $this->model;
            $data->name = $request->name;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->gender = $request->gender;
            $data->address = $request->address;
            $data->phone = $request->phone;
            if ($request->has('image')) $data->avater = fileUpload($request->image, $this->upload_file_path);
            $data->save();
            notify()->success("Created Successfully");
            return redirect()->route("$this->routename");
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
    public function show($id)
    {
        try {
            // $this->model->find($id)->update(['status' => DB::raw("IF(status = 1, 0 ,1)")]);
            // notify()->success("Delete Successfully");
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
            $data = $this->model->find($id);
            if (!$data) return abort(404);
            return view("$this->tamplate.addEdit", compact('data'));
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
            'name' => "required|unique:$this->table,name,$id",
            'phone' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:512', // image rule with allowed mime types and maximum size
        ]);
        try {
            // dd($request->all());

            $data = $this->model->find($id);
            if (!$data) return error_message('Data Not found');
            $data->name = $request->name;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->gender = $request->gender;
            $data->address = $request->address;
            $data->phone = $request->phone;
            if ($request->has('image')) $data->avater = fileUpload($request->image, $this->upload_file_path, $data->avater);
            $data->save();
            notify()->success("Updated Successfully");
            return redirect()->route("$this->routename");
        } catch (\Throwable $th) {
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
            $data  = $this->model->with('manager', 'customer', 'sub_zone')->find($id);
            if ($data->upazila->count() > 0 | $data->customer->count() > 0 | $data->sub_zone->count() > 0) return error_message('data cannot be delete');
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
