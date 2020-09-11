@extends('layouts.app')
@section('header')
    <a href="/transaction/data" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-database"></i>
        Transaction Data Setting
    </a>
@stop
@section('content')
<div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="fas fa-database"></i>
              Transaction Data Setting
          </span>
          </div>
      </div>
      <div class="card-body">
        <div id="indexTransactionDataSettingController">
          <div>
            <ul class="nav nav-pills nav-justified" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active" data-toggle="pill" href="#sales_channel">Sales Sources</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#status">Status</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#delivery_method">Delivery Method</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane container active" id="sales_channel">
                  <div class="form-group pt-5">
                      <sales-channel></sales-channel>
                  </div>
              </div>
              <div class="tab-pane container fade" id="status">
                <div class="form-group pt-5">
                    <status></status>
                </div>
              </div>
              <div class="tab-pane container fade" id="delivery_method">
                <div class="form-group pt-5">
                    <delivery-method></delivery-method>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('transaction.data.sales_channel')
  @include('transaction.data.status')
  @include('transaction.data.delivery_method')
@endsection