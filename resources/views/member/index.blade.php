@extends('layouts.app')
@section('header')
    <a href="/member" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-users"></i>
        Member
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexMemberController">
                <index-member></index-member>
            </div>
        </div>
    </div>
</div>

<template id="index-member-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
              <i class="fas fa-users"></i>
              Member
          </span>
          <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#member_modal" @click="createSingleMember">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Member Name</label>
                <input type="text" name="name" class="form-control" v-model="search.name" placeholder="Member Name" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="phone_number" class="control-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" v-model="search.phone_number" placeholder="Phone Number" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="email" class="control-label">Email</label>
                <input type="text" name="email" class="form-control" v-model="search.email" placeholder="Email" autocomplete="off" @keyup="onFilterChanged">
            </div>
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="city" class="control-label">Status</label>
                <select2 v-model="search.status" @input="onFilterChanged">
                    <option value="">All</option>
                    <option value="1">Pending</option>
                    <option value="2">Active</option>
                    <option value="3">Inactive</option>
                    <option value="99">Rejected</option>
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
{{--
                            <a href="#" @click="sortBy('users.name')">Name</a>
                            <span v-if="sortkey == 'users.name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'users.name' && reverse" class="fa fa-caret-up"></span> --}}
                        </th>
                        <th class="text-center">
                            Phone Number
{{--
                            <a href="#" @click="sortBy('phone_number')">Phone Number</a>
                            <span v-if="sortkey == 'phone_number' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'phone_number' && reverse" class="fa fa-caret-up"></span> --}}
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('is_company')">Is Company</a>
                            <span v-if="sortkey == 'is_company' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'is_company' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('company_name')">Company Name</a>
                            <span v-if="sortkey == 'company_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'company_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            Email
{{--
                            <a href="#" @click="sortBy('email')">Email</a>
                            <span v-if="sortkey == 'email' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'email' && reverse" class="fa fa-caret-up"></span> --}}
                        </th>
                        <th class="text-center">
                            Status
{{--
                            <a href="#" @click="sortBy('status')">Status</a>
                            <span v-if="sortkey == 'status' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'status' && reverse" class="fa fa-caret-up"></span> --}}
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('credit')">Credit</a>
                            <span v-if="sortkey == 'credit' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'credit' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-left">
                            @{{ data.name }}
                        </td>
                        <td class="text-left">
                            @{{ data.phone_number }}
                        </td>
                        <td class="text-center">
                            <i class="fas fa-check-circle" style="color: green" v-if="data.is_company == 'true'"></i>
                            <i class="fas fa-times-circle" style="color: red" v-else></i>
                        </td>
                        <td class="text-left">
                            @{{ data.company_name }}
                            <span v-if="data.roc">
                                <br>
                                <small>
                                    (@{{ data.roc }})
                                </small>
                            </span>
                        </td>
                        <td class="text-left">
                            @{{ data.email }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-warning" v-if="data.status == '1'">
                                Pending
                            </span>
                            <span class="badge badge-success" v-if="data.status == '2'">
                                Active
                            </span>
                            <div class="badge badge-secondary" v-if="data.status == '3'">
                                Inactive
                            </div>
                            <span class="badge badge-danger" v-if="data.status == '99'">
                                Rejected
                            </span>
                        </td>
                        <td class="text-right">
                            @{{ data.credit }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-success btn-sm" @click="toggleUserStatus(data,2)">
                                <i class="fas fa-check-square"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="toggleUserStatus(data,99)">
                                <i class="fas fa-ban"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#member_modal" @click="editSingleMember(data)">
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
    <form-member @updatetable="searchData" :clearform="clearform" :data="formdata"></form-member>
  </div>
</template>

@include('member.form')
@endsection
