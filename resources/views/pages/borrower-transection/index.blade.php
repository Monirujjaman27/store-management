@extends('master')
@section('content')
<?php
$route = 'borrower-transection';
?>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Total Transection</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2"><?php echo App\Models\BorrowerTransectionHistorey::select('id')->count() ?></h3>
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
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Credit Transection</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">(<?php echo App\Models\BorrowerTransectionHistorey::select('type')->where('type', 'credit')->count() ?>)</h3>
                            <p class="text-success mb-0">
                                <?php $credit = App\Models\BorrowerTransectionHistorey::select('type', 'transection_amount')->where('type', 'credit')->sum('transection_amount');
                                echo $credit;
                                ?>TK</p>

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
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Debit Transection</span>
                        <div class="d-flex align-items-center my-2">
                            <h3 class="mb-0 me-2">(<?php echo App\Models\BorrowerTransectionHistorey::select('type')->where('type', 'debit')->count() ?>)</h3>
                            <p class="text-success mb-0">
                                <?php $debit = App\Models\BorrowerTransectionHistorey::select('type', 'transection_amount')->where('type', 'debit')->sum('transection_amount');
                                echo $debit;
                                ?>TK</p>

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
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Net Amount</span>
                        <div class="d-flex align-items-center my-2">
                            <p class="text-success mb-0"><?php echo $debit - $credit  ?? 0 ?>TK</p>
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
                    <th>Borrower</th>
                    <th>type</th>
                    <th>transection id</th>
                    <th>amount</th>
                    <th>note</th>
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
        <div class="ml-4 data_table_pagination">{{ $data->links() }}</div>
    </div>
</div>
@endsection