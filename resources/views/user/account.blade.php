@extends('layouts.app')
@section('header')
    <a href="/user-account" class="btn btn-sm back-happyrent-light-green">
        <i class="fas fa-user-circle"></i>
        User Account
    </a>
@stop
@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="accountUserController">
                <account-user></account-user>
            </div>
        </div>
    </div>
</div>

<template id="account-user-template">
  <div class="card card-default">
    <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
      <div class="card-header">
        Change User Password
      </div>
      <div class="card-body">
        <flash message="{{ session('flash') }}"></flash>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Name
          </label>
          <input type="text" name="name" class="form-control" v-model="form.name" disabled>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Email
          </label>
          <input type="text" name="email" class="form-control" v-model="form.email" disabled>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Phone Number
          </label>
          <input type="text" name="phone_number" class="form-control" v-model="form.phone_number" disabled>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Password
          </label>
          <input type="password" name="password" class="form-control" v-model="form.password" :class="{ 'is-invalid' : formErrors['password'] }" v-bind:placeholder="form.id ? 'Remain blank to use the same password' : ''">
          <span class="invalid-feedback" role="alert" v-if="formErrors['password']">
              <strong>@{{ formErrors['password'][0] }}</strong>
          </span>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Password Confirmation
          </label>
          <input type="password" name="password_confirmation" class="form-control" v-model="form.password_confirmation" :class="{ 'is-invalid' : formErrors['password_confirmation'] }" v-bind:placeholder="form.id ? 'Remain blank to use the same password' : ''">
          <span class="invalid-feedback" role="alert" v-if="formErrors['password_confirmation']">
              <strong>@{{ formErrors['password_confirmation'][0] }}</strong>
          </span>
        </div>
      </div>
      <div class="card-footer">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <div class="btn-group">
            <button type="submit" class="btn btn-success" v-if="form.id">Save</button>
            <a href="javascript:history.back()" class="btn btn-outline-dark">Back</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

@endsection
