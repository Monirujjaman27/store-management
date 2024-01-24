@extends('master')
@section('content')
<?php
$route = 'products';
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
                        <div class="form-group my-3">
                            <label class="form-label w-100" for="category">category @if($errors->has('category'))<span class="text-danger"> {{$errors->first('category')}}</span> @endif</label>
                            <select name="category" id="category" class="select2 form-select form-control">
                                <option value="">Select</option>
                                @foreach($categories as $item)
                                <option {{ isset($data) && $item->id == $data->category_id ? 'selected':'' }} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label w-100" for="purchase_price">purchase_price
                                        @if($errors->has('purchase_price')) <span class="text-danger"> {{$errors->first('purchase_price')}}</span> @endif
                                    </label>
                                    <input id="purchase_price" name="purchase_price" @if(isset($data)) value="{{ $data->purchase_price }}" @else value="{{ old('purchase_price') }}" @endif placeholder="purchase_price" class="form-control" type="number" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label w-100" for="address">sales_price
                                        @if($errors->has('sales_price')) <span class="text-danger"> {{$errors->first('sales_price')}}</span> @endif
                                    </label>
                                    <input id="sales_price" name="sales_price" @if(isset($data)) value="{{ $data->sales_price }}" @else value="{{ old('sales_price') }}" @endif placeholder="sales_price" class="form-control" type="number" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label w-100" for="image">Image
                                @if($errors->has('image')) <span class="text-danger"> {{$errors->first('image')}}</span> @endif
                            </label>
                            <input id="image" name="image" class="form-control" multiple type="file" accept="image/*" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="image">Product Details
                                @if($errors->has('image')) <span class="text-danger"> {{$errors->first('image')}}</span> @endif
                            </label>
                            <textarea name="details" id="" class="form-control" placeholder="Details" cols="30" rows="2"></textarea>
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