<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;


// 👉 throw error msg 
function error_message($ms)
{
    notify()->error($ms);
    return back();
}

//👉 global file upload method
function fileUpload($new_file, $path, $old_file_name = NULL)
{
    //  👉 make dir
    if (!file_exists(public_path($path))) mkdir(public_path($path), 0777, TRUE);
    //  👉 remove old file when update 
    if (isset($old_file_name) && $old_file_name != "" && file_exists($old_file_name)) unlink($old_file_name);
    // 👉 file name 
    $file_name = $new_file->getClientOriginalName();
    if (file_exists(public_path($path . $file_name))) {
        if (!File::exists($path . $file_name)) File::makeDirectory($path, 0777, true, true);
        $file_name  = rand(00, 99) . $file_name;
    } else {
        $file_name  = $file_name;
    }
    //  👉move file upload
    $new_file->move($path, $file_name);
    return $path . '/' . $file_name;
}

/*
*👉 format date
* manager_id requared 
*package_id requared 
*/
//filter date range 
function date_range_search($date_range)
{
    $date_range = str_replace(' ', '', explode('to', $date_range));
    $start_date = Carbon::parse($date_range[0])->startOfDay();

    if (count($date_range) > 1) {
        $end_date = Carbon::parse($date_range[1])->endOfDay();
    } else {
        $end_date = Carbon::now()->endOfDay();
    }
    return [$start_date, $end_date];
}
