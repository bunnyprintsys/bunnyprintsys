@extends('layouts.app')
@section('header')
    <a href="/job-ticket" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-list-alt"></i>
        Job Ticket
    </a>
@stop
@section('content')
    @push('custom-style')
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
                font-size: 13px;
            }
            th {
                background-color:black;
                color:white;
            }
            th:first-child, td:first-child {
                position:sticky;
                left:0px;
            }
            td:first-child {
                background-color:lightgrey;
            }
        </style>
    @endpush
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexJobTicketController">
                <index-job-ticket></index-job-ticket>
            </div>
        </div>
    </div>
</div>

<template id="index-job-ticket-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="far fa-list-alt"></i>
              Job Ticket
          </span>
        <div class="btn-group" role="group">
            {{-- <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#single_modal" @click="createSingleEntry">
                <i class="fas fa-plus"></i>
            </button> --}}
            <button type="button" class="btn bg-success text-white btn-sm ml-auto" data-toggle="modal" data-target="#excel_modal" @click="onExcelModalClicked">
                Excel
                <i class="far fa-file-excel"></i>
            </button>
        </div>

          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Job Id</label>
                <input type="text" name="code" class="form-control" v-model="search.code" placeholder="Job Id" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Doc No</label>
                <input type="text" name="code" class="form-control" v-model="search.doc_no" placeholder="Doc No" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Status</label>
                <select2 v-model="search.status" @input="onFilterChanged">
                    <option value="">All</option>
                    <option v-for="status in statuses" :value="status.id">
                        @{{status.name}}
                    </option>
                </select2>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="date_from" class="control-label">Date From</label>
                <datepicker
                    id="date_from"
                    name="date_from"
                    v-model="search.date_from"
                    format="yyyy-MM-dd"
                    :monday-first="true"
                    :bootstrap-styling="true"
                    placeholder="Date From"
                    autocomplete="off"
                    @input=onDateChanged('date_from')
                >
                </datepicker>
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="date_to" class="control-label">Date To</label>
                <datepicker
                    id="date_to"
                    name="date_to"
                    v-model="search.date_to"
                    format="yyyy-MM-dd"
                    :monday-first="true"
                    :bootstrap-styling="true"
                    placeholder="Date To"
                    autocomplete="off"
                    @input=onDateChanged('date_to')
                >
                </datepicker>
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
                            <a href="#" @click="sortBy('code')">Job ID</a>
                            <span v-if="sortkey == 'code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'code' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        @can('job-tickets-exec-read')
                        <th class="text-center">
                            <a href="#" @click="sortBy('doc_no')">Doc No</a>
                            <span v-if="sortkey == 'doc_no' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'doc_no' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        @endcan
                        <th class="text-center">
                            <a href="#" @click="sortBy('doc_date')">Doc Date</a>
                            <span v-if="sortkey == 'doc_date' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'doc_date' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('status')">Status</a>
                            <span v-if="sortkey == 'status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'status' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <!-- <th class="text-center">
                            <a href="#" @click="sortBy('customer_code')">Cust Code</a>
                            <span v-if="sortkey == 'customer_code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'customer_code' && reverse" class="fa fa-caret-up"></span>
                        </th> -->
                        <th class="text-center">
                            <a href="#" @click="sortBy('customer_name')">Cust Name</a>
                            <span v-if="sortkey == 'customer_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'customer_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <!-- <th class="text-center">
                            <a href="#" @click="sortBy('item_code')">Item Code</a>
                            <span v-if="sortkey == 'item_code' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'item_code' && reverse" class="fa fa-caret-up"></span>
                        </th> -->
                        <th class="text-center">
                            <a href="#" @click="sortBy('item_name')">Item Name</a>
                            <span v-if="sortkey == 'item_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'item_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('remarks')">Remarks</a>
                            <span v-if="sortkey == 'remarks' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'remarks' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('qty')">Qty</a>
                            <span v-if="sortkey == 'qty' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'qty' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('uom')">UOM</a>
                            <span v-if="sortkey == 'uom' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'uom' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('delivery_method')">Delivery Method</a>
                            <span v-if="sortkey == 'delivery_method' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'delivery_method' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('delivery_date')">Delivery Date</a>
                            <span v-if="sortkey == 'delivery_date' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'delivery_date' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('address')">Delivery Address</a>
                            <span v-if="sortkey == 'address' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'address' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        @can('job-tickets-exec-read')
                        <th class="text-center">
                            <a href="#" @click="sortBy('address_name')">Attn Name</a>
                            <span v-if="sortkey == 'address_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'address_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('address_contact')">Contact Num</a>
                            <span v-if="sortkey == 'address_contact' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'address_contact' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        @endcan
                        <th class="text-center">
                            <a href="#" @click="sortBy('agent_name')">Agent</a>
                            <span v-if="sortkey == 'agent_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'agent_name' && reverse" class="fa fa-caret-up"></span>
                        </th>

                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.code }}
                        </td>
                        @can('job-tickets-exec-read')
                        <td class="text-center">
                            @{{ data.doc_no }}
                        </td>
                        @endcan
                        <td class="text-center">
                            @{{ data.doc_date }}
                        </td>
                        <td class="text-center">
                            @{{ data.status.name }}
                        </td>
                        <!-- <td class="text-left">
                            @{{ data.customer.code }}
                        </td> -->
                        <td class="text-left">
                            @{{data.customer ? data.customer.name : null}}
                            {{-- @{{ data.customer.name }} --}}
                        </td>
                        <!-- <td class="text-left">
                            @{{ data.product.code }}
                        </td> -->
                        <td class="text-left">
                            @{{ data.product.name }}
                        </td>
                        <td class="text-left">
                            @{{ data.remarks }}
                        </td>
                        <td class="text-right">
                            @{{ data.qty }}
                        </td>
                        <td class="text-right">
                            @{{ data.uom ? data.uom.name : '' }}
                        </td>
                        <td class="text-left">
                            @{{ data.delivery_method ? data.delivery_method.name : '' }}
                        </td>
                        <td class="text-left">
                            @{{ data.delivery_date }}
                        </td>
                        <td class="text-left">
                            @{{ data.address ? data.address.slug_address : ''}}
                        </td>
                        @can('job-tickets-exec-read')
                        <td class="text-center">
                            @{{ data.address ? data.address.name : ''}}
                        </td>
                        <td class="text-center">
                            @{{ data.address ? data.address.contact : ''}}
                        </td>
                        @endcan
                        <td class="text-center">
                            @{{ data.agent_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#single_modal" @click="editSingleEntry(data)">
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
    <form-job-ticket @updatetable="searchData" :clearform="clearform" :data="formdata" :action="action"></form-job-ticket>
    <div class="modal" id="excel_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        Upload Excel
                    </div>
                    <button type="button" class="close" @click="closeModal('excel_modal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="modal-content">
                        <ul>
                            <li v-for="excelHistory in excelHistories">
                                @{{excelHistory.name}} (<small>@{{excelHistory.created_at}}</small>)
                            </li>
                        </ul>

                        <span class="border text-center pt-3 pb-3" v-if="excelHistories.length == 0">
                            No Records Found
                        </span>

                        <div class="col-md-12 col-sm-12 col-xs-12 pt-5">
                            <div class="form-group">
                                <form @submit.prevent="onFilesUpload(1)" method="POST" enctype="multipart/form-data">
                                    <div class="input-group input-group-sm">
                                        <input type="file" ref="files" name="file" @change="onFilesChosen($event)">
                                        <div class="input-group-append">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="far fa-check-circle" ></i>
                                            <span>
                                                Upload
                                            </span>
                                        </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

</template>

@include('job-ticket.form')
@endsection
