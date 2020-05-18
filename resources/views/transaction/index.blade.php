@extends('layouts.app')
@section('header')
    <a href="/transaction" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-credit-card"></i>
        Transaction
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexTransactionController">
                <index-transaction></index-transaction>
            </div>
        </div>
    </div>
</div>

<template id="index-transaction-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="far fa-credit-card"></i>
              Transaction
          </span>
          <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#transaction_modal" @click="createSingleTransaction">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Job ID</label>
                <input type="text" name="name" class="form-control" v-model="search.job_id" placeholder="Job ID" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="customer_name" class="control-label">Name</label>
                <input type="text" name="customer_name" class="form-control" v-model="search.customer_name" placeholder="Customer Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="customer_phone_number" class="control-label">Phone Number</label>
                <input type="text" name="customer_phone_number" class="form-control" v-model="search.customer_phone_number" placeholder="Customer Phone Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Status</label>
                <select2 v-model="search.status" @input="onFilterChanged">
                    <option value="">All</option>
                    @foreach(\App\Models\Transaction::STATUSES as $status)
                        <option value="{{$status['id']}}">
                            {{$status['desc']}}
                        </option>
                    @endforeach
                </select2>
            </div>
        </div>
        <div class="form-row">
            <div class="mr-auto">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-light btn-outline-dark">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                </div>
                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="mr-auto">
                            <span class="font-weight-light" v-if="filterchanged">
                                <small>You have changed the filter, Search?</small>
                            </span>
                        </div>
                    </div>
                </div>
                <pulse-loader :loading="searching" :height="50" :width="100" style="padding-top:5px;"></pulse-loader>
            </div>
            <div class="ml-auto">
                <div>
                    <label for="display_num">Display</label>
                    <select v-model="selected_page" name="pageNum" @change="searchData">
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                    </select>
                    <label for="display_num2" style="padding-right: 20px">per Page</label>
                </div>
                <div>
                    <label class="" style="padding-right:18px;" for="totalnum">Showing @{{list.length}} of @{{pagination.total}} entries</label>
                </div>
            </div>
        </div>
        </form>

        <div class="form-row" style="padding-top: 20px;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tr class="table-secondary">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('order_date')">Order Date</a>
                            <span v-if="sortkey == 'order_date' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'order_date' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('job_id')">Job ID</a>
                            <span v-if="sortkey == 'job_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'job_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('job')">Job</a>
                            <span v-if="sortkey == 'job' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'job' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('customer_name')">Customer Name</a>
                            <span v-if="sortkey == 'customer_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'customer_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('phone_number')">Phone Number</a>
                            <span v-if="sortkey == 'phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('alt_phone_number')">Alt Phone Number</a>
                            <span v-if="sortkey == 'alt_phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'alt_phone_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('delivery_address')">Delivery Address</a>
                            <span v-if="sortkey == 'delivery_address' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'delivery_address' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('amount')">Amount</a>
                            <span v-if="sortkey == 'amount' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'amount' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_artwork_provided')">Artwork</a>
                            <span v-if="sortkey == 'is_artwork_provided' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_artwork_provided' && reverse" class="fa fa-caret-up"></span>
                        </th>
{{--
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_design_required')">Design</a>
                            <span v-if="sortkey == 'is_design_required' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_design_required' && reverse" class="fa fa-caret-up"></span>
                        </th> --}}
                        <th class="text-center">
                            <a href="#" @click="sortBy('invoice_id')">Invoice#</a>
                            <span v-if="sortkey == 'invoice_id' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'invoice_id' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('dispatch_date')">Dispatch Date</a>
                            <span v-if="sortkey == 'dispatch_date' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'dispatch_date' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('status')">Status</a>
                            <span v-if="sortkey == 'status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('tracking_number')">Tracking Number</a>
                            <span v-if="sortkey == 'tracking_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'tracking_number' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('user_name')">Handle By</a>
                            <span v-if="sortkey == 'user_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'user_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.job_id }}
                        </td>
                        <td class="text-center">
                            @{{ data.job }}
                        </td>
                        <td class="text-center">
                            @{{ data.customer_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.phone_number }}
                            <br>
                            @{{ data.alt_phone_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.delivery_address }}
                        </td>
                        <td class="text-right">
                            @{{ data.amount }}
                        </td>
                        <td class="text-center">
                            <span v-if="data.is_artwork_provided" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span v-if="!data.is_artwork_provided" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            @{{ data.invoice_id }}
                        </td>
                        <td class="text-center">
                            @{{ data.dispatch_date }}
                        </td>
                        <td class="text-center">
                            @{{ data.status }}
                        </td>
                        <td class="text-center">
                            @{{ data.tracking_number }}
                        </td>
                        <td class="text-center">
                            @{{ data.user_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            </div>
                        </td>
                    </tr>
                    <tr v-if="! pagination.total">
                        <td colspan="18" class="text-center"> No Results Found </td>
                    </tr>
                </table>
            </div>
            <div class="pull-left">
                <pagination :pagination="pagination" :callback="fetchTable" :offset="4"></pagination>
            </div>
        </div>
      </div>
    </div>
    <form-transaction @updatetable="searchData" :clearform="clearform" :data="formdata"></form-transaction>
  </div>
</template>

@include('transaction.form')
@endsection
