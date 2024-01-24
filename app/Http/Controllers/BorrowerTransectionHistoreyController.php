<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\BorrowerTransectionHistorey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowerTransectionHistoreyController extends Controller
{

    public $model;
    public $routename;
    public $table;
    public $tamplate;
    public function __construct()
    {
        $this->model = new BorrowerTransectionHistorey();
        $this->routename = "borrower-transection.index";
        $this->table = "borrower_transection_historeys";
        $this->tamplate = "pages.borrower-transection";
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
                ->with('borrower')
                ->when($request, function ($q) use ($request) {
                    if ($request->date_range) {
                        return $q->whereBetween('created_at', date_range_search($request->date_range));
                    }
                })
                ->when($request->search_query, function ($q) use ($request) {
                    $searchQuery = '%' . $request->search_query . '%';
                    return $q->where('transection_id', 'LIKE', '%' . $searchQuery . '%');
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
        $borrowers = Borrower::select('id', 'name', 'phone', 'present_lends_amount')->get();
        return view("$this->tamplate.addEdit", compact('borrowers'));
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
            'borrower' => 'required|int',
            'type' => 'required',
            'amount' => 'required|int',
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model;
            $data->borrower_id = $request->borrower;
            $data->transection_id = time();
            $data->transection_amount = $request->amount;
            $data->type = $request->type;
            $data->note = $request->note;
            $data->save();
            $borror = Borrower::where('id', $request->borrower)->first();
            if ($request->type == 'credit') {
                // pay lend
                $borror->increment('present_lends_amount', $request->amount);
                $borror->increment('total_lends', $request->amount);
            } else {
                // return
                $borror->decrement('present_lends_amount', $request->amount);
                $borror->increment('total_return', $request->amount);
            }
            notify()->success("Created Successfully");
            DB::commit();
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
            $borrowers = Borrower::select('id', 'name', 'phone', 'present_lends_amount')->get();
            return view("$this->tamplate.addEdit", compact('data', 'borrowers'));
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
            'borrower' => 'required|int',
            'type' => 'required',
            'amount' => 'required|int',
        ]);
        DB::beginTransaction();
        try {
            $data = $this->model->find($id);
            if (!$data) return error_message('Data Not Found');
            $data->borrower_id = $request->borrower;
            $data->transection_amount = $request->amount;
            $data->type = $request->type;
            $data->note = $request->note;
            $data->save();

            $borror = Borrower::where('id', $request->borrower)->first();
            if ($request->type == 'credit') {
                // pay lend
                $borror->increment('present_lends_amount', $request->amount);
                $borror->increment('total_lends', $request->amount);
            } else {
                // return
                $borror->decrement('present_lends_amount', $request->amount);
                $borror->increment('total_return', $request->amount);
            }
            notify()->success("Updated Successfully");
            DB::commit();
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
            $this->model->find($id)->delete();
            notify()->success("Delete Successfully");
            return back();
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
