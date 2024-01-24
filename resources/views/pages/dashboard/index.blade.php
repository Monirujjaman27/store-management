@extends('master')
@section('content')
<?php
$route = 'borrowers';
?>
<div class="row">
  <!-- Sales last year -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Sales</h5>
        <small class="text-muted">Last Year</small>
      </div>
      <div id="salesLastYear"></div>
      <div class="card-body pt-0">
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">175k</h4>
          <small class="text-danger">-16.2%</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Sessions Last month -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h5 class="card-title mb-0">Sessions</h5>
        <small class="text-muted">Last Month</small>
      </div>
      <div class="card-body">
        <div id="sessionsLastMonth"></div>
        <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
          <h4 class="mb-0">45.1k</h4>
          <small class="text-success">+12.6%</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Profit -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="badge p-2 bg-label-danger mb-2 rounded">
          <i class="ti ti-currency-dollar ti-md"></i>
        </div>
        <h5 class="card-title mb-1 pt-2">Total Profit</h5>
        <small class="text-muted">Last week</small>
        <p class="mb-2 mt-1">1.28k</p>
        <div class="pt-1">
          <span class="badge bg-label-secondary">-12.2%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Sales -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="badge p-2 bg-label-info mb-2 rounded"><i class="ti ti-chart-bar ti-md"></i></div>
        <h5 class="card-title mb-1 pt-2">Total Sales</h5>
        <small class="text-muted">Last week</small>
        <p class="mb-2 mt-1">$4,673</p>
        <div class="pt-1">
          <span class="badge bg-label-secondary">+25.2%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Last Transaction -->
  <div class="col-lg-12 mb-4 mb-lg-0">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title m-0 me-2">Last Transaction</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="teamMemberList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-dots-vertical ti-sm text-muted"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
            <a class="dropdown-item" href="javascript:void(0);">Download</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-borderless border-top">
          <thead class="border-bottom">
            <tr>
              <th>CARD</th>
              <th>DATE</th>
              <th>STATUS</th>
              <th>TREND</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="me-3">
                    <img src="../../assets/img/icons/payments/visa-img.png" alt="Visa" height="30" />
                  </div>
                  <div class="d-flex flex-column">
                    <p class="mb-0 fw-medium">*4230</p>
                    <small class="text-muted">Credit</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <p class="mb-0 fw-medium">Sent</p>
                  <small class="text-muted text-nowrap">17 Mar 2022</small>
                </div>
              </td>
              <td><span class="badge bg-label-success">Verified</span></td>
              <td>
                <p class="mb-0 fw-medium">+$1,678</p>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="me-3">
                    <img src="../../assets/img/icons/payments/master-card-img.png" alt="Visa" height="30" />
                  </div>
                  <div class="d-flex flex-column">
                    <p class="mb-0 fw-medium">*5578</p>
                    <small class="text-muted">Credit</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <p class="mb-0 fw-medium">Sent</p>
                  <small class="text-muted text-nowrap">12 Feb 2022</small>
                </div>
              </td>
              <td><span class="badge bg-label-danger">Rejected</span></td>
              <td>
                <p class="mb-0 fw-medium">-$839</p>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="me-3">
                    <img src="../../assets/img/icons/payments/american-express-img.png" alt="Visa" height="30" />
                  </div>
                  <div class="d-flex flex-column">
                    <p class="mb-0 fw-medium">*4567</p>
                    <small class="text-muted">Credit</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <p class="mb-0 fw-medium">Sent</p>
                  <small class="text-muted text-nowrap">28 Feb 2022</small>
                </div>
              </td>
              <td><span class="badge bg-label-success">Verified</span></td>
              <td>
                <p class="mb-0 fw-medium">+$435</p>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="me-3">
                    <img src="../../assets/img/icons/payments/visa-img.png" alt="Visa" height="30" />
                  </div>
                  <div class="d-flex flex-column">
                    <p class="mb-0 fw-medium">*5699</p>
                    <small class="text-muted">Credit</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <p class="mb-0 fw-medium">Sent</p>
                  <small class="text-muted text-nowrap">8 Jan 2022</small>
                </div>
              </td>
              <td><span class="badge bg-label-secondary">Pending</span></td>
              <td>
                <p class="mb-0 fw-medium">+$2,345</p>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="me-3">
                    <img src="../../assets/img/icons/payments/visa-img.png" alt="Visa" height="30" />
                  </div>
                  <div class="d-flex flex-column">
                    <p class="mb-0 fw-medium">*5699</p>
                    <small class="text-muted">Credit</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <p class="mb-0 fw-medium">Sent</p>
                  <small class="text-muted text-nowrap">8 Jan 2022</small>
                </div>
              </td>
              <td><span class="badge bg-label-danger">Rejected</span></td>
              <td>
                <p class="mb-0 fw-medium">-$234</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection