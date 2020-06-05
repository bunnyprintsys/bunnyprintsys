@extends('layouts.app')
@section('header')
    <a href="/order" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-cart-plus"></i>
        Order Panel
    </a>
@stop
@section('content')
<div class="card">
  <div class="card-header">
      <div class="form-row">
      <span class="mr-auto">
          <i class="fas fa-cart-plus"></i>
          Label Sticker
      </span>
      </div>
  </div>
  <div class="card-body">
    <div id="indexOrderController">
      <div>
        <label-sticker></label-sticker>
      </div>
    </div>
  </div>
</div>

@include('order.label-sticker')
@endsection
