<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\BorrowerTransectionHistorey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowerController extends Controller
{
    public $model;
    public $routename;
    public $table;
    public $tamplate;
    public function __construct()
    {
        $this->model = new Borrower();
        $this->routename = "borrowers.index";
        $this->table = "borrowers";
        $this->tamplate = "pages.borrower";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->model
                ->when($request, function ($q) use ($request) {
                    if ($request->date_range) {
                        return $q->whereBetween('created_at', date_range_search($request->date_range));
                    }
                })

                ->when($request->search_query, function ($q) use ($request) {
                    $searchQuery = '%' . $request->search_query . '%';
                    return $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere('address', 'LIKE', $searchQuery)
                        ->orWhere('phone', 'LIKE', $searchQuery);
                })

                ->paginate($request->item ?? 10);
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
            if ($request->has('image')) $data->avater = fileUpload($request->image, 'uploads/customer/avater');
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
            $data = $this->model->find($id);
            $lends =  BorrowerTransectionHistorey::where(['borrower_id' => $id, 'type' => 'credit'])->get();
            $return_lends =  BorrowerTransectionHistorey::where(['borrower_id' => $id, 'type' => 'debit'])->get();
            if (!$data) return abort(404);
            return view("$this->tamplate.view", compact('data', 'lends', 'return_lends'));
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
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->gender = $request->gender;
            $data->address = $request->address;
            if ($request->has('image')) $data->avater = fileUpload($request->image, 'uploads/customer/avater', $data->avater);
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
            $data  = $this->model->with('borrower_transection_historey')->find($id);
            if ($data->borrower_transection_historey->count() > 0) return error_message('data cannot be delete');
            unlink_image($data->avater);
            $data->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
