<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\BorrowerTransectionHistorey;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['products'] =  Product::when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();

            $data['customers'] =  Customer::select('id', 'created_at')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();
            $data['suppliers'] =  Supplier::select('id', 'created_at')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();
            $data['purchase'] =  Purchase::select('id', 'created_at', 'total')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();
            $data['sales'] =  Sale::select('id', 'created_at', 'total')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();

            $data['borrowers'] =  Borrower::select('id', 'created_at')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();

            $data['total_transections'] =  BorrowerTransectionHistorey::select('id', 'created_at')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->get();

            $data['lends'] =  BorrowerTransectionHistorey::select('id', 'created_at', 'transection_amount')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->where('type', 'credit')->get();

            $data['return_lends'] =  BorrowerTransectionHistorey::select('id', 'created_at', 'transection_amount')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->where('type', 'debit')->get();

            $data['recent_transections'] =  BorrowerTransectionHistorey::with('borrower')->when($request->date_range, function ($q) use ($request) {
                return $q->whereBetween('created_at', date_range_search($request->date_range));
            })->paginate(10);
            return view("pages.dashboard.index", compact('data'));
        } catch (\Throwable $th) {
            // dd($th);
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
