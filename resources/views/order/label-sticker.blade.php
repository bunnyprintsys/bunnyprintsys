@inject('profiles', 'App\Models\Profile')
<template id="label-sticker-template">
    <div>
        {{-- order panel step 1 --}}
    <div class="row justify-content-center" v-if="panel.order">
      <div class="col-md-11">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Materials
            <label for="required" class="control-label" style="color:red;">*</label>
            </label>
            <select2 v-model="orderForm.material_id" class="form-control" @input="materialIdSelected()">
                <option value="">All</option>
                <option v-for="material in materials" :value="material.id">
                @{{material.name}}
                </option>
            </select2>
            <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['material_id'] }">
            <span class="invalid-feedback" role="alert" v-if="formErrors['material_id']">
                <strong>@{{ formErrors['material_id'][0] }}</strong>
            </span>
        </div>
        <hr>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Shape
            <label for="required" class="control-label" style="color:red;">*</label>
            </label>
            <select2 v-model="orderForm.shape_id" class="form-control" @input="getQuotation()">
                <option value="">All</option>
                <option v-for="shape in shapes" :value="shape.id">
                @{{shape.name}}
                </option>
            </select2>
            <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['shape_id'] }">
            <span class="invalid-feedback" role="alert" v-if="formErrors['shape_id']">
                <strong>@{{ formErrors['shape_id'][0] }}</strong>
            </span>
        </div>
        <hr>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Lamination
            {{-- <label for="required" class="control-label" style="color:red;">*</label> --}}
            </label>
            <select2 v-model="orderForm.lamination_id" class="form-control" @input="getQuotation()">
                <option value="null">Nope</option>
                <option v-for="lamination in laminations" :value="lamination.id">
                @{{lamination.name}}
                </option>
            </select2>
            <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['lamination_id'] }">
            <span class="invalid-feedback" role="alert" v-if="formErrors['lamination_id']">
                <strong>@{{ formErrors['lamination_id'][0] }}</strong>
            </span>
        </div>
        <hr>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Size
            </label>
            <label for="required" class="control-label" style="color:red;">*</label>
            <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                <label class="control-label">
                    Width (mm)
                </label>
                <input type="text" name="width" class="form-control" v-model="orderForm.width" :class="{ 'is-invalid' : formErrors['width'] }" :placeholder="'Maximum '+orderFormSetup.max_width+'mm'" @input="getQuotation()">
                <span class="invalid-feedback" role="alert" v-if="formErrors['width']">
                    <strong>@{{ formErrors['width'][0] }}</strong>
                </span>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <label class="control-label">
                    Height (mm)
                </label>
                <input type="text" name="height" class="form-control" v-model="orderForm.height" :class="{ 'is-invalid' : formErrors['height'] }" :placeholder="'Maximum '+orderFormSetup.max_height+'mm'" @input="getQuotation()">
                <span class="invalid-feedback" role="alert" v-if="formErrors['height']">
                    <strong>@{{ formErrors['height'][0] }}</strong>
                </span>
                </div>
            </div>
            </div>
        </div>
        <hr>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Quantities
            <label for="required" class="control-label" style="color:red;">*</label>
            </label>
            <select2 v-model="orderForm.orderquantity_id" class="form-control" @input="getQuotation()">
                <option value="">All</option>
                <option v-for="orderquantity in orderquantities" :value="orderquantity.id">
                @{{orderquantity.name}}
                </option>
            </select2>
            <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['orderquantity_id'] }">
            <span class="invalid-feedback" role="alert" v-if="formErrors['orderquantity_id']">
                <strong>@{{ formErrors['orderquantity_id'][0] }}</strong>
            </span>
        </div>
        <hr>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label class="control-label">
            Delivery
            </label>
            <select2 v-model="orderForm.delivery_id" class="form-control" @input="getQuotation()">
            <option value="">All</option>
            <option v-for="delivery in deliveries" :value="delivery.id">
                @{{delivery.name}}
            </option>
            </select2>
{{--
            <select2 v-model="form.delivery_fee" class="form-control" @input="getQuotation()">
                <option value="">None</option>
                <option value="0">JB (Free)</option>
                <option value="30">Singapore (+ RM 30)</option>
            </select2> --}}
            <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['delivery_fee'] }">
            <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_fee']">
                <strong>@{{ formErrors['delivery_fee'][0] }}</strong>
            </span>
        </div>
{{--
        <button type="button" class="btn btn-success btn-lg btn-block" :disabled="!form.material_id || !form.shape_id || !form.orderquantity_id" @click.prevent="getQuotation()">
            Get Quotation
        </button> --}}

        <div class="form-group col-md-12 col-sm-12 col-xs-12 row pt-3 ml-1">
            <h4>
            Total:
            <span>
                {{$profiles::first()->country ? $profiles::first()->country->currency_symbol : 'RM'}}
                @{{orderForm.total.toFixed(2)}}
            </span>
            </h4>
        </div>
{{--
        <button class="btn btn-success btn-block" @click="onOrderNextButtonClicked()" :disabled="orderForm.total == 0">
            <i class="fas fa-forward"></i>
            Place Order
        </button> --}}
      </div>
    </div>

    {{-- customer auth step 2 --}}
    <div v-if="panel.customerAuth">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
        <label class="control-label required">
          Is Company?
        </label>
        <div class="col-md-12">
            <div class="form-check form-check-inline pt-2">
                <input class="form-check-input" type="radio" value="false" v-model="customerForm.is_company" @change="onIsCompanyChosen(false)">
                <label class="form-check-label">Individual</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="true" v-model="customerForm.is_company" @change="onIsCompanyChosen(true)">
                <label class="form-check-label">Company</label>
            </div>
        </div>
      </div>

      <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="customerForm.is_company">
        <label class="control-label required">
          Company Name
        </label>
        <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" autocomplete="company_name" v-model="customerForm.company_name" autofocus>
        <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['company_name'] }">
      </div>

      <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="customerForm.is_company">
        <label class="control-label required">
          ROC
        </label>
        <input type="text" class="form-control" name="roc" value="{{ old('roc') }}" autocomplete="roc" v-model="customerForm.roc" autofocus>
        <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['roc'] }">
      </div>

      <div class="form-group col-md-12 col-sm-12 col-xs-12">
        <label class="control-label required">
          Name
        </label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}" autocomplete="name" v-model="customerForm.name" autofocus>
        <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['name'] }">
      </div>

      <div class="form-group col-md-12 col-sm-12 col-xs-12">
        <label class="control-label required">
          Email
        </label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" autocomplete="phone_number" v-model="customerForm.email" autofocus>
        <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['email'] }">
      </div>

      <div class="form-group col-md-12 col-sm-12 col-xs-12">
        <label class="control-label required">
          Mobile Number
        </label>
        <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" autocomplete="phone_number" v-model="customerForm.phone_number" autofocus>
        <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['phone_number'] }">
      </div>

      <div class="form-check col-md-12 col-sm-12 col-xs-12">
        <input type="checkbox" id="checkbox" v-model="customerForm.confirm_true">
        <label for="checkbox">I confirm the given information is true</label>
      </div>


      <button class="btn btn-primary btn-block" @click="onBackOrderButtonClicked()">
          <i class="fas fa-backward"></i>
          Back
      </button>
      <button class="btn btn-success btn-block" @click="onCustomerFormNextButtonClicked()" :disabled="!customerForm.phone_number || !customerForm.confirm_true">
          <i class="fas fa-forward"></i>
          Next
      </button>
    </div>

    <div v-if="panel.customerValidation">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
        <label class="control-label">
          Please verify your mobile number (OTP will be sent to @{{customerForm.phone_number}})
        </label>
        <div class="input-group">
          <input type="text" class="form-control" name="sms_otp" v-model="customerForm.sms_otp">
          <div class="input-group-append">
            <span class="input-group-text">
              Resend
            </span>
          </div>
        </div>
      </div>
    </div>


{{--
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label class="control-label">
              Atttachment
              <i class="fas fa-paperclip"></i>
          </label>
          <div class="input-group">
              <div class="custom-file">
              <input type="file" name="logo_url" class="custom-file-input" id="attachment" v-on:change="onFileChange" :class="{ 'is-invalid' : formErrors['attachment'] }">
              <label class="custom-file-label" for="attachment">Choose file</label>
              </div>
          </div>
          @{{file_name}}
          <span class="invalid-feedback" role="alert" v-if="formErrors['attachment']">
              <strong>@{{ formErrors['attachment'][0] }}</strong>
          </span>
          </div>
      </div> --}}
    </div>
  </div>
  </template>