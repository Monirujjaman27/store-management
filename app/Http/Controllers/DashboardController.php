<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
            return view("pages.dashboard.index");
        } catch (\Throwable $th) {
            // dd($th);
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
