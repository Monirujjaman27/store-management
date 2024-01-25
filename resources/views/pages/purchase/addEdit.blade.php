@extends('master')
@section('content')
<?php
$route = 'purchase';
$title = (isset($data) ? 'Edit ' : 'Add ') .  $route;
?>
@if(isset($data)) @php $form_action = route("$route.update", $data->id); @endphp @else @php $form_action = route("$route.store"); @endphp @endif
<div class="card">
    <div class="card-body">
        <div class="text-center mb-1">
            <h3 class="mb-2 text-capitalize">{{$title}}</h3>
        </div>
        <div class="col-sm-12 col-md-12 m-auto">
            @if($errors->any())
            <div class="text-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row">

                <form action="{{$form_action}}" class="row g-3" method="POST" enctype="multipart/form-data">
                    @if(isset($data))
                    @method('put')
                    @endif
                    @csrf

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label w-100" for="supplier">Supplier @if($errors->has('supplier'))<span class="text-danger"> {{$errors->first('supplier')}}</span> @endif</label>
                                <select required name="supplier" id="supplier" class="select2 form-select form-select-lg" data-allow-clear="true">
                                    <option value="">Select</option>
                                    @foreach($supplier as $suplier_item)
                                    <option {{isset($data) && $data->supplier_id == $suplier_item->id ? 'selected':''}} value="{{$suplier_item->id}}">{{$suplier_item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mt-4">
                            <label class="form-label w-100" for="Product">Products @if($errors->has('Product'))<span class="text-danger"> {{$errors->first('Product')}}</span> @endif</label>
                            <select name="product" id="product" class="select2 form-select form-select-lg" data-allow-clear="true">
                                <option value="">Select</option>
                                @foreach($product as $p_item)
                                <option value="{{$p_item}}">{{$p_item->sku}} | {{$p_item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table border" id="productTable">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>batch no</th>
                                    <th>purchase price</th>
                                    <th>Sale price</th>
                                    <th>Quentaty</th>
                                    <th>total</th>
                                    <th> <i class="menu-icon tf-icons ti ti-settings"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($data))
                                @foreach($data->purchase_items as $purchanse_item)

                                <tr>
                                    <td class="productName"> {{$purchanse_item->product->name}} <input class="productName form-control" hidden required name="product_id[]" placeholder="Product Name" value="{{$purchanse_item->product_id}}" /></td>

                                    <td><input class="batch_no form-control" required name="batch_no[]" placeholder="batch no" value="{{$purchanse_item->batch}}" /></td>

                                    <td><input class="purchase_price form-control" required name="purchase_price[]" placeholder="purchase_price" value="{{$purchanse_item->purchase_price}}" type="number" /></td>

                                    <td><input class="sales_price form-control" required name="sales_price[]" placeholder="sales_price" value="{{$purchanse_item->sale_price}}" type="number" /></td>

                                    <td><input class="qty form-control" required name="qty[]" placeholder="qty" value="{{$purchanse_item->qty}}" type="number" /></td>

                                    <td><input class="subtotal form-control" readonly name="subtotal[]" placeholder="subtotal" value="{{$purchanse_item->total_price}}" type="number" /></td>
                                    <td><i class="tf-icons ti ti-xbox-x text-danger cursor-pointer removeRow"></i></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td>Total: <input type="hidden" name="total" id="hiddenTotal" value="0.00"></td>
                                    <td id="total">{{ isset($data) ? $data->total :'0.00'}}</td>
                                </tr>
                            </tfoot>
                        </table>

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
@push('page_script')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#product').select2();

        // Add row on product selection
        $('#product').on('change', function() {
            var slected_product = $(this).val();

            if (!slected_product) return;
            slected_product = JSON.parse(slected_product);

            var productId = slected_product.id;

            // Check if the product with the same ID already exists in the table
            var productExists = false;
            $('#productTable tbody tr').each(function() {
                var existingProductId = $(this).find('input[name^="product_id"]').val();
                if (existingProductId == productId) {
                    productExists = true;
                    return false; // Exit the loop early if the product is found
                }
            });

            if (productExists) {
                alert('This product already exists.');
                $('#product').val('').trigger('change'); // Clear the product selection
                return;
            }

            var newRow = $('<tr>');
            newRow.append(`<td class="productName"> ${slected_product.name} <input class="productName form-control" hidden required name="product_id[]" placeholder="Product Name" value="${slected_product.id }"/></td>`);
            newRow.append(`<td><input class="batch_no form-control" required name="batch_no[]" placeholder="batch no" /></td>`);
            newRow.append(`<td><input class="purchase_price form-control" required name="purchase_price[]" placeholder="purchase_price" value="${slected_product.purchase_price}" type="number" /></td>`);
            newRow.append(`<td><input class="sales_price form-control" required name="sales_price[]" placeholder="sales_price" value="${slected_product.sales_price}"  type="number" /></td>`);
            newRow.append(`<td><input class="qty form-control" required name="qty[]" placeholder="qty" type="number" /></td>`);
            newRow.append(`<td><input class="subtotal form-control" readonly name="subtotal[]" placeholder="subtotal" type="number" /></td>`);
            newRow.append(`<td><i class="tf-icons ti ti-xbox-x text-danger cursor-pointer removeRow"></i></td>`);
            newRow.appendTo('#productTable tbody');
            $('#product').val('').trigger('change'); // Clear the product selection

        });

        // Remove row on icon click
        $('#productTable').on('click', '.removeRow', function() {
            if (!confirm('are you sure to remove')) return
            $(this).closest('tr').remove();
            updateTotal(); // Update total after changing input values
        });

        // Update subtotal on input change
        $('#productTable').on('input', 'input', function() {
            updateSubtotal($(this).closest('tr'));
            updateTotal(); // Update total after changing input values
        });

        // Function to update subtotal
        function updateSubtotal(row) {
            var purchasePrice = parseFloat(row.find('.purchase_price').val()) || 0;
            var qty = parseInt(row.find('.qty').val()) || 0;
            var subtotal = purchasePrice * qty;
            row.find('.subtotal').val(subtotal.toFixed(2));
        }

        // Function to update total
        function updateTotal() {
            var total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').text(total.toFixed(2));
            $('#hiddenTotal').val(total.toFixed(2));
        }
    });
</script>
@endpush