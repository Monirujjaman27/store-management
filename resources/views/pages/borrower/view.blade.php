@extends('master')
@section('content')
<?php
$route = 'borrowers';
?>
<div class="card">
    <div class="card-body">
        <div class="text-center mb-1">
            <h3 class="mb-2 text-capitalize">{{$data->name}}</h3>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 m-auto">
                <table class="table border">
                    <thead>
                        <tr>
                            <th>avater</th>
                            <td>:</td>
                            <td>
                                <img width="40px" class="rounded-circle" src="{{asset($data->avater)}}" alt="">
                            </td>
                        </tr>
                        <tr>
                            <th>name</th>
                            <td>:</td>
                            <td>{{$data->name}}</td>
                        </tr>
                        <tr>
                            <th>phone</th>
                            <td>:</td>
                            <td>{{$data->phone}}</td>
                        </tr>
                        <tr>
                            <th>email</th>
                            <td>:</td>
                            <td>{{$data->email}}</td>
                        </tr>
                        <tr>
                            
                            <th>address</th>
                            <td>:</td>
                            <td>{{$data->address}}</td>
                        </tr>
                        <tr>
                            <th>gender</th>
                            <td>:</td>
                            <td>{{$data->gender}}</td>
                        </tr>
                        <tr>
                            <th>present_lends_amount</th>
                            <td>:</td>
                            <td>{{$data->present_lends_amount}}</td>
                        </tr>
                        <tr>
                            <th>total_lends</th>
                            <td>:</td>
                            <td>{{$data->total_lends}}</td>
                        </tr>
                        <tr>
                            <th>total_return</th>
                            <td>:</td>
                            <td>{{$data->total_return}}</td>
                        </tr>

                    </thead>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 col-sm-12 border">
                <h2>Leads</h2>
                <div class="card-datatable table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Actions</th>
                                <th>Borrower</th>
                                <th>type</th>
                                <th>transection id</th>
                                <th>amount</th>
                                <th>note</th>
                                <th>created date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lends as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="text-primary dropdown-item" href='{{route("$route.edit", $item->id)}}'><i class="bi bi-pencil-square"></i> Edit</a>
                                            <form action='{{ route("$route.destroy",$item->id)}}' method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure to delete')" type="submit" class="dropdown-item btn-danger text-danger"><i class="bi bi-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$item->borrower->name}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->transection_id}}</td>
                                <td>{{$item->transection_amount}}</td>
                                <td>{{$item->note}}</td>
                                <td>{{ $item->created_at->format('d-m-Y h:i a') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 border">
                <h2>Return Leads</h2>
                <div class="card-datatable table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Actions</th>
                                <th>Borrower</th>
                                <th>type</th>
                                <th>transection id</th>
                                <th>amount</th>
                                <th>note</th>
                                <th>created date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($return_lends as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="text-primary dropdown-item" href='{{route("$route.edit", $item->id)}}'><i class="bi bi-pencil-square"></i> Edit</a>
                                            <form action='{{ route("$route.destroy",$item->id)}}' method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure to delete')" type="submit" class="dropdown-item btn-danger text-danger"><i class="bi bi-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$item->borrower->name}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->transection_id}}</td>
                                <td>{{$item->transection_amount}}</td>
                                <td>{{$item->note}}</td>
                                <td>{{ $item->created_at->format('d-m-Y h:i a') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection