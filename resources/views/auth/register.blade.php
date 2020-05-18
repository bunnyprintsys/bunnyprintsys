@extends('layouts.app')

@section('content')
<div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexRegistrationController">
                <index-registration></index-registration>
            </div>
        </div>
    </div>
</div>

<template id="index-registration-template">
    <div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color:black;">{{ __('Signup become our member!! (Enjoy Exclusive Rate)') }}</div>

                <div class="card-body">
                    <flash message="{{ session('flash') }}"></flash>
                    {{-- <form method="POST" action="{{ route('register') }}"> --}}
                    <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Individual OR Company?') }}</label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline pt-2">
                                    <input class="form-check-input" type="radio" value="false" v-model="form.is_company" @change="onIsCompanyChosen('false')">
                                    <label class="form-check-label">Individual</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="true" v-model="form.is_company" @change="onIsCompanyChosen('true')">
                                    <label class="form-check-label">Company</label>
                                </div>
                            </div>
{{--
                            <div class="col-md-6">
                                <select2-must name="" id="" class="form-control" v-model="form.is_cooperate" @input="">
                                    <option value="false">
                                        Individual
                                    </option>
                                    <option value="true">
                                        Coorperate
                                    </option>
                                </select2-must>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                        </div>

                        <div v-show="form.is_company == 'true'">
                            <div class="form-group row">
                                <label for="company_name" class="col-md-4 col-form-label text-md-right required">{{ __('Company Name') }}</label>
                                <div class="col-md-6">
                                    <input id="company_name" type="text" class="form-control" :class="{'is-invalid': formErrors['company_name']}" name="company_name" value="{{ old('company_name') }}" autocomplete="company_name" v-model="form.company_name" autofocus>

                                    <small class="text-danger">@{{formErrors['company_name'] ? formErrors['company_name'][0] : ''}}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="roc" class="col-md-4 col-form-label text-md-right required">{{ __('ROC') }}</label>

                                <div class="col-md-6">
                                    <input id="roc" type="text" class="form-control" :class="{'is-invalid': formErrors['roc']}" name="roc" value="{{ old('roc') }}" autocomplete="roc" v-model="form.roc" autofocus>

                                    <small class="text-danger">@{{formErrors['roc'] ? formErrors['roc'][0] : ''}}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right required">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" :class="{'is-invalid': formErrors['name']}" name="name" value="{{ old('name') }}" autocomplete="name" v-model="form.name" autofocus>

                                <small class="text-danger">@{{formErrors['name'] ? formErrors['name'][0] : ''}}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right required">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" :class="{'is-invalid': formErrors['email']}" name="email" value="{{ old('email') }}" autocomplete="email" v-model="form.email" autofocus>

                                <small class="text-danger">@{{formErrors['email'] ? formErrors['email'][0] : ''}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right required">{{ __('Contact Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control" :class="{'is-invalid': formErrors['phone_number']}" name="phone_number" value="{{ old('phone_number') }}" autocomplete="phone_number" v-model="form.phone_number" autofocus>

                                <small class="text-danger">@{{formErrors['phone_number'] ? formErrors['phone_number'][0] : ''}}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <p>
                                    <small>
                                        Please confirm again the email address you have entered. We will send your registration confirmation to your email address as soon as we have received your payment.
                                    </small>
                                </p>
                                <p>
                                    <small>
                                    By clicking "SIGN UP MEMBER", I am agreed to the Terms & Conditions
                                    </small>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" @click="onSubmit()">
                                    {{ __('Sign up member') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
@endsection
