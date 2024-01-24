@extends('master')
@section('content')
<?php
$route = 'borrower-transection';
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
                            <label for="">Borrower</label>
                            @if($errors->has('borrower'))<br /> <span class="text-danger"> {{$errors->first('borrower')}}</span> @endif
                            <select {{isset($data) ? 'disabled' :''}} name="borrower" id="" class="select2 form-select form-select-lg">
                                <option value="">Select</option>
                                @foreach($borrowers as $b_item)
                                <option {{isset($data->borrower_id) && $data->borrower_id == $b_item->id ? 'selected':'' }} value="{{$b_item->id}}">{{$b_item->name}} | {{$b_item->phone}} | present lends: {{$b_item->present_lends_amount}} Tk</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-2">
                            <label class="form-label w-100" for="type">Type </label>
                            @if($errors->has('type'))<span class="text-danger"> {{$errors->first('type')}}</span> <br /> @endif
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="inlineRadio1" {{isset($data) && $data->type == 'credit'? 'checked' :''}} value="credit" />
                                <label class="form-check-label" for="inlineRadio1">credit <small class="text-success">(pay lend)</small></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" {{isset($data) && $data->type == 'debit'? 'checked' :''}} name="type" id="debit" value="debit" />
                                <label class="form-check-label" for="debit">debit <small class="text-success">(Return lend)</small></label>
                            </div>
                        </div>

                        <div class="form-group pt-2">
                            <label class="form-label w-100" for="amount">amount
                                @if($errors->has('amount')) <span class="text-danger"> {{$errors->first('amount')}}</span> @endif
                            </label>
                            <input id="amount" name="amount" @if(isset($data)) value="{{ $data->transection_amount }}" @else value="{{ old('amount') }}" @endif placeholder="amount" class="form-control" type="number" />
                        </div>
                        <div class="form-group">
                            <label class="form-label w-100" for="note">note
                                @if($errors->has('note')) <span class="text-danger"> {{$errors->first('note')}}</span> @endif
                            </label>
                            <textarea id="note" name="note" placeholder="note" class="form-control">@if(isset($data)) {{ $data->note }} @else {{ old('note') }} @endif</textarea>
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