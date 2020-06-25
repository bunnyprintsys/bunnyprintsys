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

<style>
  .otp-input {
    width: 45px;
    height: 60px;
    font-size: 20px;
    /* margin-top: 5px; */
    margin: 5px 0px 5px 0px;
    text-align: center;
  }

  @media only screen and (min-device-width: 321px) and (max-device-width: 480px) {
    .otp-input {
      margin-left: 10px;
    }
  }
</style>


<template id="index-registration-template">
    <div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" style="color:black;">{{ __('Signup become our member!! (Enjoy Exclusive Rate)') }}</div>

                <div class="card-body">
                    <loading-overlay :active.sync="loading"></loading-overlay>
                    <flash message="{{ session('flash') }}"></flash>
                    {{-- <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off"> --}}
                        @csrf

                        <div v-show="steps.step_1">
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
                                <label for="name" class="col-md-4 col-form-label text-md-right required">{{ __('Your Name') }}</label>

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
                                <div class="col-md-6 offset-md-4">
                                    <p>
                                        <small>
                                            Please confirm again the email address you have entered. We will send your registration confirmation to your email address as soon as we have received your payment.
                                        </small>
                                    </p>
                                    <p>
                                        <small>
                                        By clicking "Next", I am agreed to the Terms & Conditions
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="button" class="btn btn-primary" @click="onApplicantInfoFilled('step_1', 'step_2')">
                                        <i class="fas fa-forward"></i>
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-show="steps.step_2">
                            <div class="form-row form-group col-md-12 col-sm-12 col-xs-12">
                              <div class="float-left">
                                <button type="button" class="btn btn-primary" @click="onProceedButtonClicked('step_2', 'step_1')">
                                  <i class="fas fa-backward"></i>
                                  Back
                                </button>
                              </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label required">
                                    Please confirm your phone number and press Send OTP
                                </label>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <multiselect
                                          v-model="form.phone_country_id"
                                          :options="countries"
                                          :close-on-select="true"
                                          placeholder="Select..."
                                          :custom-label="customLabelCountriesOption"
                                          track-by="id"
                                          :disabled="form.otp_countdown"
                                          @input="onPhoneNumberEntered"
                                        ></multiselect>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                          <input id="phone_number" type="text" class="form-control" :class="{'is-invalid': formErrors['phone_number']}" name="phone_number" value="{{ old('phone_number') }}" autocomplete="phone_number" v-model="form.phone_number" autofocus @input="onPhoneNumberEntered" :disabled="form.otp_countdown">
                                          <div class="input-group-append">
                                            <span class="input-group-text">
                                              <i style="color: green;" class="fas fa-check-circle" v-if="form.phone_number_format_valid"></i>
                                              <i style="color: red;" class="fas fa-times-circle" v-if="!form.phone_number_format_valid"></i>
                                            </span>
                                            <button class="btn btn-sm btn-success" @click="onSendOTPClicked" v-if="!form.otp_countdown && form.otp_request_count === 0" :disabled="!form.phone_number_format_valid">
                                              Send OTP
                                            </button>
                                            <button class="btn btn-sm btn-success" @click="onSendOTPClicked" v-if="!form.otp_countdown && form.otp_request_count > 0" :disabled="!form.phone_number_format_valid">
                                              Send OTP Again
                                            </button>
                                            <span class="input-group-text" v-if="form.otp_countdown">
                                              <span>@{{form.countdown}} sec</span>
                                            </span>
                                          </div>
                                        </div>
                                        <small class="text-danger">@{{formErrors['phone_number'] ? formErrors['phone_number'][0] : ''}}</small>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.otp_request_count > 0">
                              <div class="d-flex justify-content-center" style="padding-top: 30px;">
                                  <v-otp-input
                                  ref="otpInput"
                                  input-classes="otp-input"
                                  separator="-"
                                  :num-inputs="5"
                                  :should-auto-focus="true"
                                  :is-input-num="false"
                                  @on-complete="onOTPCompleted"
                                />
                              </div>
                              <div class="d-flex justify-content-center form-group">
                                  <p class="text-center">
                                    <small>
                                      Please enter 5 digits OTP sent to your phone number
                                    </small>
                                  </p>
                              </div>
                            </div>
                        </div>
                        <div v-show="steps.step_3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <label for="art" style="color:red;">*</label>
                                    <input type="password" class="form-control" v-model="form.password" :class="{'is-invalid': formErrors['password']}" @input="validatePassword">
                                    <small class="text-danger">@{{formErrors['password'] ? formErrors['password'][0] : ''}}</small>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <label for="art" style="color:red;">*</label>
                                    <input type="password" class="form-control" v-model="form.password_confirmation" :class="{'is-invalid': formErrors['password']}" @input="validatePassword">
                                    <small class="text-danger">@{{formErrors['password'] ? formErrors['password'][0] : ''}}</small>
                                </div>
                                <div class="float-right">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" @click="onSubmit()">
                                            <i class="fas fa-check"></i>
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
@endsection
