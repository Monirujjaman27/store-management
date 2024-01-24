@extends('master')
@php $route = 'settings' @endphp
<style>
    label {
        text-transform: capitalize !important;
    }
</style>
@section('title') {{'settings'}} @endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form action='{{route("$route.store")}}' method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <h3>General</h3>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="form-label" for="site_name">site name @if($errors->has('site_name'))<span class="text-danger"> {{$errors->first('site_name')}}</span> @endif</label>
                        <input type="text" value="{{$data->where('slug','site_name')->first() ? $data->where('slug','site_name')->first()->value : ''}}" name="site_name" id="site_name" placeholder="" class="form-control" />
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group d-flex">
                        <div class="w-75">
                            <label class="form-label" for="site_logo">Logo @if($errors->has('site_logo'))<span class="text-danger"> {{$errors->first('site_logo')}}</span> @endif</label>
                            <input type="file" name="site_logo" id="site_logo" placeholder="" class="form-control" />
                            <input type="hidden" name="old_site_logo" value="{{$data->where('slug','company_website')->first() ? $data->where('slug','company_website')->first()->value : ''}}" />
                        </div>
                        <div class="w-25">
                            <img class="border p-2 float-end" src="{{$data->where('slug','site_logo')->first() ? asset($data->where('slug','site_logo')->first()->value) : ''}}" alt="">
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <input type="submit" value="Save" class="btn btn-outline-primary btn-sm mt-1 float-right">
                </div>
            </div>

        </form>

    </div>
</div>
@endsection