@extends('master')
@section('content')
<?php
$route = 'borrowers';
?>
<div class="row">
  <div class="col-md-12">
    <div class="row mb-3">
      <form action="{{route('dashboard.index')}}">
        <div class="col-sm-12 col-md-6 m-auto">
          <div class="row">
            <div class="col-8">
              <!-- <input type="search" name="search_query" class="form-control" value="{{request()->search_query}}" placeholder="Search"> -->
              <input type="text" name="date_range" value="{{request('date_range')}}" class="form-control w-100" style="min-width: 318px !important;" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
            </div>
            <div class="col-2">
              <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
            <div class="col-2">
              <a class="btn btn-warning px-2" href='{{route("dashboard.index")}}'>Reset</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Sales last year -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Products</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{$data['products']->count()}}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Customers</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{$data['customers']->count()}}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Suppliers</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{$data['suppliers']->count()}}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Purchase</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{$data['purchase']->count()}}</h4>
          <small class="text-success"><?= $data['purchase']->sum('total')  ?> TK</small>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Sales</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">{{$data['sales']->count()}}</h4>
          <small class="text-success"><?= $data['sales']->sum('total')  ?> TK</small>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Net</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0"><?= $data['purchase']->sum('total')  - $data['sales']->sum('total') ?> TK</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Borrowers</h5>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0"><?= $data['borrowers']->count()  ?></h4>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Transections</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2"><?= $data['total_transections']->count() ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>lends</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2"><?= $data['lends']->count() ?></h3>
              <p class="text-success mb-0">
                <?= $data['lends']->sum('transection_amount') ?>TK</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Return lends</span>
            <div class="d-flex align-items-center my-2">
              <h3 class="mb-0 me-2"><?= $data['return_lends']->count() ?></h3>
              <p class="text-success mb-0">
                <?= $data['return_lends']->sum('transection_amount') ?>TK</p>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Net Amount</span>
            <div class="d-flex align-items-center my-2">
              <p class="text-success mb-0"><?= $data['return_lends']->sum('transection_amount') - $data['lends']->sum('transection_amount')  ?? 0 ?>TK</p>
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
  </div> -->
</div>


<!-- Last Transaction -->
<div class="col-lg-12 mb-4 mb-lg-0">
  <div class="card h-100">
    <div class="card-header d-flex justify-content-between">
      <h5 class="card-title m-0 me-2">Recent lends Transaction</h5>
    </div>
    <div class="table-responsive">
      <table class="table table-borderless border-top">
        <thead class="border-bottom">
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
          @foreach($data['recent_transections'] as $item)
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
@endsection