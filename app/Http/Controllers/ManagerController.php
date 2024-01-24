<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{

    public $model;

    public function __construct()
    {
        $this->model = new User();
    }
    public function index(Request $request)
    {
        try {
            $data =  $this->model->select('slug', 'value')->get();
            return view("pages.settings.index", compact('data'));
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
            if (!$data) return error_message('data Not found');
            return view("pages.profile.index", compact('data'));
        } catch (\Throwable $th) {
            notify()->warning($th->getMessage());
            return back();
        }
    }

    // manager user change Password
    public function managerChangePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);
        try {
            $this->model->find($id)->update(['password' => Hash::make($request->password)]);
            notify()->success('Password Change Successfully');
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            notify()->warning($th->getMessage());
            return back();
        }
    }
    // manager change profile
    /* profile_old
       * profile_for
       * profile 
       */
    public function update_profile(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('profile')) {
                $data = $this->model->find($id);
                if (!$data) return error_message('data not found');
                $data->profile  = fileUpload($request->profile, 'uploads/manager', $data->profile);
                $data->save();
            }
            DB::commit();
            notify()->success('Profile Change Successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            notify()->warning($th->getMessage());
            return back();
        }
    }
}
