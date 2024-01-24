@extends('master')
@section('content')
<?php
$route = 'purchase';
?>
<div class="card">
    <div class="card-header">
        <h1 class="card-title text-capitalize text-2xl">{{$route}}</h1>
        <div class="d-flex">
            <form action='{{route("$route.index")}}'>
                <select class="form-control" name="item" onchange="this.form.submit()" id="">
                    <option @if ($data->count() == '10') selected @endif value="10">10</option>
                    <option @if ($data->count() == '50') selected @endif value="50">50</option>
                    <option @if ($data->count() == '100' ) selected @endif value="100">100</option>
                    <option @if ($data->count() == $data->total() ) selected @endif value="{{$data->total()}}">All</option>
                </select>
            </form>
            <a href='{{route("$route.create")}}' class="ml-4 btn btn-primary">Add item</a>
        </div>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table border-top">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>
                        <div class="d-flex">
                            <img width="40px" class="rounded-circle" src="{{asset($item->avater)}}" alt="">
                            {{$item->name}}
                        </div>
                    </td>
                    <td>{{$item->phone}}</td>
                    <td>{{$item->gender}}</td>
                    <td>{{$item->address}}</td>
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