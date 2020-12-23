@extends('layouts.app')
@section('header')
    <a href="/product" class="btn btn-sm back-happyrent-light-green">
      <i class="fas fa-layer-group"></i>
        Product Data
    </a>
@stop
@section('content')
<div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="fas fa-layer-group"></i>
              Product Data
          </span>
          </div>
      </div>
      <div class="card-body">
        <div id="indexProductController">
          <div>
            <ul class="nav nav-pills nav-justified" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active" data-toggle="pill" href="#main-product">Main Products</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#main-material">Materials</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#main-shape">Shapes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#main-lamination">Laminations</a>
              </li>             
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#main-frame">Frames</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#main-finishing">Finishings</a>
              </li>                                           
{{--
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#status">Status</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#delivery_method">Delivery Method</a>
              </li> --}}
            </ul>
            <div class="tab-content">
              <div class="tab-pane container active" id="main-product">
                  <div class="form-group pt-5">
                      <index-product></index-product>
                  </div>
              </div>
              <div class="tab-pane container" id="main-material">
                <div class="form-group pt-5">
                    <index-material></index-material>
                </div>
              </div>
              <div class="tab-pane container" id="main-shape">
                <div class="form-group pt-5">
                    <index-shape></index-shape>
                </div>
              </div>
              <div class="tab-pane container" id="main-lamination">
                <div class="form-group pt-5">
                    <index-lamination></index-lamination>
                </div>
              </div>
              <div class="tab-pane container" id="main-frame">
                <div class="form-group pt-5">
                    <index-frame></index-frame>
                </div>
              </div>
              <div class="tab-pane container" id="main-finishing">
                <div class="form-group pt-5">
                    <index-finishing></index-finishing>
                </div>
              </div>                                                        
{{--
              <div class="tab-pane container fade" id="status">
                <div class="form-group pt-5">
                    <status></status>
                </div>
              </div>
              <div class="tab-pane container fade" id="delivery_method">
                <div class="form-group pt-5">
                    <delivery-method></delivery-method>
                </div>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('product.index-product')
  @include('product.index-material')
  @include('product.index-shape')
  @include('product.index-lamination')
  @include('product.index-frame')
  @include('product.index-finishing')
@endsection