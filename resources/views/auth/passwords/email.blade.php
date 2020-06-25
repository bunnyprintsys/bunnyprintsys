@extends('layouts.app')

@section('content')
<div class="container">

  <div>
    <div class="panel panel-default">
        <div class="panel-body screen-panel">
            <div id="indexPasswordResetController">
                <index-password-reset></index-password-reset>
            </div>
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


  <template id="index-password-reset-template">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>
                <div class="card-body">
                  <loading-overlay :active.sync="loading"></loading-overlay>
                  <flash message="{{ session('flash') }}"></flash>
                  <div v-show="steps.step_1">
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

                  <div v-show="steps.step_2">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="password">Enter New Password</label>
                            <label for="art" style="color:red;">*</label>
                            <input type="password" class="form-control" v-model="form.password" :class="{'is-invalid': formErrors['password']}" @input="validatePassword">
                            <small class="text-danger">@{{formErrors['password'] ? formErrors['password'][0] : ''}}</small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <label for="art" style="color:red;">*</label>
                            <input type="password" class="form-control" v-model="form.password_confirmation" :class="{'is-invalid': formErrors['password']}" @input="validatePassword">
                            <small class="text-danger">@{{formErrors['password'] ? formErrors['password'][0] : ''}}</small>
                        </div>
                        <div class="float-right">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" @click="onSubmit()">
                                    <i class="fas fa-check"></i>
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
  </div>
</template>

@endsection
