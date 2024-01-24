@extends('master')
@section('content')
<?php
$route = 'customers';
$title = (isset($data) ? 'Edit ' : 'Add ') .  $route;
?>
@if(isset($data)) @php $form_action = route("$route.update", $data->id); @endphp @else @php $form_action = route("$route.store"); @endphp @endif
<div class="card">
    <div class="card-body">
        <div class="text-center mb-1">
            <h3 class="mb-2 text-capitalize">{{$title}}</h3>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 m-auto">
                <form action="{{$form_action}}" class="row g-3" method="POST" enctype="multipart/form-data">
                    @if(isset($data))
                    @method('put')
                    @endif
                    @csrf
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label w-100" for="name">name
                                @if($errors->has('name')) <span class="text-danger"> {{$errors->first('name')}}</span> @endif
                            </label>
                            <input id="name" name="name" @if(isset($data)) value="{{ $data->name }}" @else value="{{ old('name') }}" @endif placeholder="Name" class="form-control" type="text" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="email">email
                                @if($errors->has('email')) <span class="text-danger"> {{$errors->first('email')}}</span> @endif
                            </label>
                            <input id="email" name="email" @if(isset($data)) value="{{ $data->email }}" @else value="{{ old('email') }}" @endif placeholder="email" class="form-control" type="email" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="Gender">Gender @if($errors->has('gender'))<span class="text-danger"> {{$errors->first('gender')}}</span> @endif</label>
                            <select name="gender" id="Gender" class="select2 form-select form-control">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="phone">phone
                                @if($errors->has('phone')) <span class="text-danger"> {{$errors->first('phone')}}</span> @endif
                            </label>
                            <input id="phone" name="phone" @if(isset($data)) value="{{ $data->phone }}" @else value="{{ old('phone') }}" @endif placeholder="phone" class="form-control" type="number" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="address">address
                                @if($errors->has('address')) <span class="text-danger"> {{$errors->first('address')}}</span> @endif
                            </label>
                            <input id="address" name="address" @if(isset($data)) value="{{ $data->address }}" @else value="{{ old('address') }}" @endif placeholder="address" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="image">Profile
                                @if($errors->has('image')) <span class="text-danger"> {{$errors->first('image')}}</span> @endif
                            </label>
                            <input id="image" name="image" class="form-control" type="file" accept="image/*" />
                        </div>
                    </div>
                    <div class="col-12 text-left">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <a href='{{route("$route.index")}}' class="btn btn-warning">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection