@extends('master')
@section('content')
<?php
$route = 'borrowers';
?>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Total {{$route}} </span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">{{$data->total()}}</h3>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-user ti-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                    <th>#ID</th>
                    <th>Actions</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Present lends</th>
                    <th>Total lends</th>
                    <th>Total Return</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>created date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                            <div class="dropdown-menu">
                                <a class="text-info dropdown-item" href='{{route("$route.show", $item->id)}}'><i class="bi bi-eye"></i> View</a>
                                <a class="text-primary dropdown-item" href='{{route("$route.edit", $item->id)}}'><i class="bi bi-pencil-square"></i> Edit</a>
                                <form action='{{ route("$route.destroy",$item->id)}}' method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure to delete')" type="submit" class="dropdown-item btn-danger text-danger"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <img width="40px" class="rounded-circle" src="{{asset($item->avater)}}" alt="">
                            {{$item->name}}
                        </div>
                    </td>
                    <td>{{$item->phone}}</td>
                    <td>{{$item->present_lends_amount}}</td>
                    <td>{{$item->total_lends}}</td>
                    <td>{{$item->total_return}}</td>
                    <td>{{$item->gender}}</td>
                    <td>{{$item->address}}</td>
                    <td>{{ $item->created_at->format('d-m-Y h:i a') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="ml-4 data_table_pagination">{{ $data->links() }}</div>
    </div>
</div>
@endsection