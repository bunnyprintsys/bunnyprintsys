@extends('layouts.app')
@section('header')
    <a href="/profile" class="btn btn-sm back-happyrent-light-green">
        <i class="far fa-user-circle"></i>
        Profile
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexProfileController">
                <index-profile></index-profile>
            </div>
        </div>
    </div>
</div>

<template id="index-profile-template">
  <div>
    <div class="card">
      <div class="card-header">
          <div class="form-row">
          <span class="mr-auto">
            <i class="far fa-user-circle"></i>
              Profile
          </span>
          <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#profile_modal" @click="createSingleProfile">
              <i class="fas fa-plus"></i>
          </button>
          </div>
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <form action="#" @submit.prevent="searchData" method="GET" autocomplete="off">
        <div class="form-row">
            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                <label for="name" class="control-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" v-model="search.company_name" placeholder="Company Name" autocomplete="off" @keyup="onFilterChanged">
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
                            Company Name
                        </th>
                        <th class="text-center">
                            RoC
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('address')">Address</a>
                            <span v-if="sortkey == 'address' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'address' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th class="text-center">
                            <a href="#" @click="sortBy('country_name')">Country</a>
                            <span v-if="sortkey == 'country_name' && !reverse" class="fa fa-caret-down"></span>
                            <span v-if="sortkey == 'country_name' && reverse" class="fa fa-caret-up"></span>
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in list" class="row_edit">
                        <td class="text-center">
                            @{{ index + pagination.from }}
                        </td>
                        <td class="text-center">
                            @{{ data.company_name }}
                        </td>
                        <td class="text-center">
                            @{{ data.roc }}
                        </td>
                        <td class="text-left">
                            @{{ data.address ? data.address.full_address : null }}
                        </td>
                        <td class="text-center">
                            @{{ data.country_name }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#profile_modal" @click="editSingleProfile(data)">
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
    <form-profile @updatetable="searchData" :clearform="clearform" :data="formdata"></form-profile>
  </div>
</template>

@include('profile.form')
@endsection
