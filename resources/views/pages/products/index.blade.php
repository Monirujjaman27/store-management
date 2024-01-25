@extends('master')
@section('content')
<?php
$route = 'products';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title text-capitalize text-2xl">{{$route}}
            <a href='{{route("$route.create")}}' class="ml-4 btn btn-primary">Add item</a>
        </h1>
        <div class="">
            <form action='{{route("$route.index")}}'>
                <div class="row">
                    <div class="col-2">
                        <select class="form-control" name="item" onchange="this.form.submit()" id="">
                            <option @if ($data->count() == '10') selected @endif value="10">10</option>
                            <option @if ($data->count() == '50') selected @endif value="50">50</option>
                            <option @if ($data->count() == '100' ) selected @endif value="100">100</option>
                            <option @if ($data->count() == $data->total() ) selected @endif value="{{$data->total()}}">All</option>
                        </select>
                    </div>
                    <div class="col-md-7 p-0">
                        <div class="d-flex">
                            <input type="text" name="date_range" value="{{request('date_range')}}" class="form-control w-100" style="min-width: 318px !important;" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                            <div class="input-group">
                                <input type="search" name="search_query" class="form-control" value="{{request()->search_query}}" placeholder="Search">
                                <button type="submit" class="btn btn-outline-primary p-1 fs-tiny">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 p-0">
                        <a class="btn btn-warning px-2" href='{{route("$route.index")}}'>Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table border-top">
            <thead>
                <tr>
                    <th>#SKU</th>
                    <th>Name</th>
                    <th>images</th>
                    <th>purchase price</th>
                    <th>sales price</th>
                    <th>offer price</th>
                    <th>stock quantity</th>
                    <th>sales quantity</th>
                    <th>created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{$item->sku}}</td>
                    <td>
                        <div class="d-flex">

                            {{$item->name}}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            @if($item->images)
                            @foreach(json_decode($item->images) as $image)
                            <img width="20" src="{{ asset($image) }}" alt="Product Image">
                            @endforeach
                            @endif
                        </div>
                    </td>
                    <td>{{$item->purchase_price}}</td>
                    <td>{{$item->sales_price}}</td>
                    <td>{{$item->offer_price}}</td>
                    <td>{{$item->stock_quantity}}</td>
                    <td>{{$item->sales_quantity}}</td>
                    <td>{{ $item->created_at->format('d-m-Y h:i a') }}</td>
                    <td>
                        <div class="d-flex">
                            <a class="btn btn-sm btn-primary" href='{{route("$route.edit", $item->id)}}'><i class="bi bi-pencil-square"></i></a>

                            <form action='{{ route("$route.destroy",$item->id)}}' method="post">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure to delete')" type="submit" class="btn btn-sm btn-danger text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="ml-4 data_table_pagination">{{ $data->links() }}</div>
    </div>
</div>
@endsection