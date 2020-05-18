@extends('layouts.app')
@section('header')
    <a href="/voucher" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-user-circle"></i>
        Voucher
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexVoucherController">
                <index-voucher></index-voucher>
            </div>
        </div>
    </div>
</div>

<template id="index-voucher-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="far fa-user-circle"></i>
              Voucher
          </span>
          <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#voucher_modal" @click="createSingleVoucher">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="valid_from" class="control-label">Valid From</label>
                <div class="input-group">
                <date-picker id="valid_from" name="valid_from" v-model="search.valid_from" placeholder="Valid From" autocomplete="off"></date-picker>
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="valid_to" class="control-label">Valid To</label>
                <div class="input-group">
                <date-picker id="valid_to" name="valid_to" v-model="search.valid_to" placeholder="Valid To" autocomplete="off"></date-picker>
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                </div>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="is_active" class="control-label">Is Active</label>
                <select2 v-model="search.is_active">
                    <option value="">All</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="is_percentage" class="control-label">Is Percentage</label>
                <select2 v-model="search.is_percentage">
                    <option value="">All</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
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
                            Name
                        </th>
                        <th class="text-center">
                            Desc
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('valid_from')">Valid From</a>
                            <span v-if="sortkey == 'valid_from' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'valid_from' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('valid_to')">Valid To</a>
                            <span v-if="sortkey == 'valid_to' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'valid_to' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_active')">Is Active</a>
                            <span v-if="sortkey == 'is_active' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_active' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_percentage')">Is Percentage</a>
                            <span v-if="sortkey == 'is_percentage' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_percentage' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_unique_customer')">Is Only One Time</a>
                            <span v-if="sortkey == 'is_unique_customer' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_unique_customer' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_count_limit')">Is Count Limit</a>
                            <span v-if="sortkey == 'is_count_limit' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_count_limit' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Value
                        </th>
                        <th class="text-center">
                            Count Limit
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.name }}
                        </td>
                        <td class="text-left">
                            @{{ data.desc }}
                        </td>
                        <td class="text-center">
                            @{{ data.valid_from }}
                        </td>
                        <td class="text-center">
                            @{{ data.valid_to }}
                        </td>
                        <td class="text-center">
                            <span v-if="data.is_active" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span v-if="!data.is_active" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            <span v-if="data.is_percentage" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span v-if="!data.is_percentage" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            <span v-if="data.is_unique_customer" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span v-if="!data.is_unique_customer" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            <span v-if="data.is_count_limit" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span v-if="!data.is_count_limit" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#voucher_modal" @click="editSingleVoucher(data)">
                                <i class="fas fa-edit"></i>
                            </button>
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
    <form-voucher @updatetable="searchData" :clearform="clearform" :data="formdata"></form-voucher>
  </div>
</template>

@include('voucher.form')
@endsection
