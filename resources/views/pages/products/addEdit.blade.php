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
                                    <label class="form-label w-100" for="purchase_price">purchase price
                                        @if($errors->has('purchase_price')) <span class="text-danger"> {{$errors->first('purchase_price')}}</span> @endif
                                    </label>
                                    <input id="purchase_price" name="purchase_price" @if(isset($data)) value="{{ $data->purchase_price }}" @else value="{{ old('purchase_price') }}" @endif placeholder="purchase price" class="form-control" type="number" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label w-100" for="address">sales price
                                        @if($errors->has('sales_price')) <span class="text-danger"> {{$errors->first('sales_price')}}</span> @endif
                                    </label>
                                    <input id="sales_price" name="sales_price" @if(isset($data)) value="{{ $data->sales_price }}" @else value="{{ old('sales_price') }}" @endif placeholder="sales price" class="form-control" type="number" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row py-3">
                                <div class="col-4">
                                    <label class="form-label w-100" for="is_offer">is has offer Price
                                        <input type="checkbox" class="form-check-input" name="is_offer" value="1" id="is_offer">
                                    </label>
                                </div>
                                <div class="col-8">
                                    <label class="form-label w-100" for="image">offer price
                                        @if($errors->has('offer_price')) <span class="text-danger"> {{$errors->first('offer_price')}}</span> @endif
                                    </label>
                                    <input id="offer_price" name="offer_price" @if(isset($data)) value="{{ $data->offer_price }}" @else value="{{ old('offer_price') }}" @endif placeholder="offer price" class="form-control" type="number" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="{{isset($data) ? 'col-8':'col-12'}}">
                                    <label class="form-label w-100" for="images">Image
                                        @if($errors->has('images')) <span class="text-danger"> {{$errors->first('images')}}</span> @endif
                                    </label>
                                    <input id="images" name="images[]" class="form-control" type="file" accept="images/*" multiple />
                                </div>
                                @if(isset($data))
                                <div class="col-4">
                                    <img src="{{asset($data->image)}}" width="100" class="float-end" alt="">
                                </div>
                                @endif
                            </div>
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