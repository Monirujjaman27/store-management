<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemSettingController extends Controller
{

    public $model;

    public function __construct()
    {
        $this->model = new AdminSetting();
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

    //👉    store system settings
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $key => $value) {
                // logo upload 
                if ($key == 'site_logo') {
                    $value = fileUpload($request->site_logo, 'uploads/logo/', $request->old_site_logo);
                    $this->updateOrCreateSetting($key, $value);
                }

                // other inputs 
                if (!empty($value) && $key !== 'old_site_logo') $this->updateOrCreateSetting($key, $value);
            }
            notify()->success("Save Successfully");
            DB::commit();
            return back();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            // return error_message('Database Exception Error', $e->getMessage(), $e->getCode());
        }
    }

    //👉 updateOrCreateSetting
    public function updateOrCreateSetting($slug, $value)
    {
        $setting = AdminSetting::where('slug', $slug)->first();
        if (!$setting && !empty($slug) && !empty($value)) {
            AdminSetting::create(['slug' => $slug, 'value' => $value]);
        } elseif (!empty($value)) {
            $setting->update(['value' => $value]);
        }
    }
}
