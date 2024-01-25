@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-header">
                   <h1 class="text-center"> {{$data->inv_no}}</h1>
                </div>
                <div class="card-body">

                    <div class="table-responsive border-top">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Product name</th>
                                    <th>Batch No</th>
                                    <th>Purchase Price</th>
                                    <th>Qty</th>
                                    <th>Sub Total</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->purchase_items as $index=> $p_item)
                                <tr>
                                    <td class="text-center ps-2 py-4">{{$index+1}}</td>
                                    <td class="text-center ps-2 py-4">{{$p_item->product->name}}</td>
                                    <td class="text-center ps-2 py-4">{{$p_item->batch}}</td>
                                    <td class="text-center ps-2 py-4">{{$p_item->purchase_price}}</td>
                                    <td class="text-center ps-2 py-4">{{$p_item->qty}}</td>
                                    <td class="text-center ps-2 py-4">{{$p_item->total_price}}</td>
                                    <td class="text-center ps-2 py-4">
                                        <a onclick="return confirm('Are you sure to delete')" href="{{route('delete_purchase_item', $p_item->id)}}" class="btn btn-sm btn-danger text-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="align-top px-4 py-4">
                                        <p class="mb-2 mt-3">
                                            <span class="ms-3 fw-medium">Salesperson:</span>
                                            <span>{{$data->supplier->name}}</span>
                                        </p>
                                        <span class="ms-3">Thanks for your business</span>
                                    </td>
                                    <td class="text-end pe-3 py-4">
                                        <p class="mb-0 pb-3">Total:</p>
                                    </td>
                                    <td class="ps-2 py-3">
                                        <p class="fw-medium mb-2">{{$data->total}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-body mx-3">
                        <div class="row">
                            <div class="col-12">
                                <span class="fw-medium">Note:</span>
                                <span>It was a pleasure working with you and your team. We hope you will keep us in mind for
                                    future freelance projects. Thank You!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Invoice -->

        <!-- Invoice Actions -->
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-label-secondary d-grid w-100 mb-2" target="_blank" href='{{route("purchase_print_invoice", $data->id)}}'>
                        Print
                    </a>

                </div>
            </div>
        </div>
        <!-- /Invoice Actions -->
    </div>

    <!-- /Offcanvas -->
</div>
@endsection