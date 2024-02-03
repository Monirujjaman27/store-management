@extends('master')
<style>
    label {
        text-transform: capitalize !important;
    }
</style>
@section('title') {{'Profile'}} @endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="mb-1">
            <h3 class="mb-2 text-capitalize">{{'Profile'}}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form action='{{route("manager.change-password", $data->id)}}' method="POST">
                    @method('put')
                    @csrf
                    <label for="">Change Password</label>
                    <div class="form-group">
                        <label class="form-label" for="password">New Pssword @if($errors->has('password'))<span class="text-danger"> {{$errors->first('password')}}</span> @endif</label>
                        <input type="text" disabled value="" name="password" id="password" placeholder="new password" type="password" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">password confirmation @if($errors->has('password_confirmation'))<span class="text-danger"> {{$errors->first('password_confirmation')}}</span> @endif</label>
                        <input type="text" disabled value="" name="password_confirmation" id="password_confirmation" placeholder="confirm password" type="password" class="form-control" />
                    </div>
                    <input type="submit" value="Update Password" class="btn btn-outline-primary btn-sm mt-1 float-right">
                </form>
            </div>
            <div class="col-md-6 col-sm-12">
                <form action='{{route("manager.change-profile", $data->id)}}' method="POST" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="form-group d-flex">
                        <div class="w-75">
                            <label class="form-label" for="profile">Profile @if($errors->has('profile'))<span class="text-danger"> {{$errors->first('profile')}}</span> @endif</label>
                            <input type="file" name="profile" id="profile" placeholder="" class="form-control" />
                            <input type="hidden" name="old_profile" value="" />
                        </div>
                        <div class="w-25">
                            <img class="border p-2 float-end" src="{{asset($data->profile)}}" alt="">
                        </div>
                    </div>
                    <input type="submit" value="Update Profile" class="btn btn-outline-primary btn-sm mt-1 float-right">
                </form>
            </div>

        </div>
    </div>
</div>
@endsection