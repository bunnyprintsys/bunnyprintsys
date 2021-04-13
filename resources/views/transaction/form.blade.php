<template id="form-transaction-template">
    <div class="modal" id="transaction_modal">
        <div class="modal-dialog modal-xl">
            {{-- <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off"> --}}
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        <span>
                            Edit Invoice: @{{transactionForm.invoice_id}}
                        </span>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row">
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label required">Source</label>
                                    <multiselect
                                        v-model="transactionForm.sales_channel"
                                        :options="sales_channels"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelName"
                                        track-by="id"
                                        ></multiselect>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['sales_channel']">
                                    <strong>@{{ formErrors['sales_channel'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label required">
                                    Order Date
                                </label>
                                {{-- @{{transactionForm.order_date}} --}}
                                <datepicker
                                    v-model="transactionForm.order_date"
                                    :format="dateFormatter"
                                    :bootstrap-styling="true"
                                    :highlighted="{dates: [new Date()]}"
                                    {{-- @input="dateFormatter(transactionForm.order_date)" --}}
                                >
                                </datepicker>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['order_date']">
                                    <strong>@{{ formErrors['order_date'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label required">Status</label>
                                    <multiselect
                                        v-model="transactionForm.status"
                                        :options="statuses"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelName"
                                        track-by="id"
                                        ></multiselect>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['status_id']">
                                    <strong>@{{ formErrors['status'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label required">
                                    Dispatch Date
                                </label>
                                {{-- @{{transactionForm.dispatch_date}} --}}
                                <datepicker
                                    v-model="transactionForm.dispatch_date"
                                    :format="dateFormatter"
                                    :bootstrap-styling="true"
                                    :highlighted="{dates: [new Date()]}"
                                    {{-- @input="dateFormatter('dispatch_date')" --}}
                                >
                                </datepicker>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['dispatch_date']">
                                    <strong>@{{ formErrors['dispatch_date'][0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label">Design Needed?</label>
                                    <multiselect
                                        v-model="transactionForm.is_design_required"
                                        :options="booleans"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelName"
                                        track-by="id"
                                        ></multiselect>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['is_design_required']">
                                    <strong>@{{ formErrors['is_design_required'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label">Designer</label>
                                    <multiselect
                                        v-model="transactionForm.designed_by"
                                        :options="designers"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelName"
                                        track-by="id"
                                        ></multiselect>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['designed_by']">
                                    <strong>@{{ formErrors['designed_by'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label">
                                    Tracking Num
                                </label>
                                <input type="text" name="tracking_number" class="form-control" v-model="transactionForm.tracking_number" :class="{ 'is-invalid' : formErrors['tracking_number'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['tracking_number']">
                                    <strong>@{{ formErrors['tracking_number'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                <label class="control-label">Delivery Method</label>
                                    <multiselect
                                        v-model="transactionForm.delivery_method"
                                        :options="delivery_methods"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelName"
                                        track-by="id"
                                        ></multiselect>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_method']">
                                    <strong>@{{ formErrors['delivery_method'][0] }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row text-center">
                            <h4>
                                <span class="badge badge-primary">
                                    Customer
                                </span>
                            </h4>
                        </div>
                        <div class="form-row pt-2">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingCustomer" @change="resetObject('existingCustomer')">
                                    <label class="form-check-label">Existing Customer</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingCustomer" @change="resetObject('existingCustomer')">
                                    <label class="form-check-label">Create New Customer</label>
                                </div>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingCustomer === 'true'">
                                <multiselect
                                v-model="customerForm.customer"
                                :options="customers"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelCustomer"
                                track-by="id"
                                @input="onExistingCustomerChosen(customerForm.customer)"
                                ></multiselect>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingCustomer === 'false'">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="false" v-model="radioOption.isCompanyCustomer" @change="resetObject('isCompanyCustomer')">
                                    <label class="form-check-label">Individual</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="true" v-model="radioOption.isCompanyCustomer" @change="resetObject('isCompanyCustomer')">
                                    <label class="form-check-label">Company</label>
                                </div>

                                <div class="pt-3" v-show="radioOption.isCompanyCustomer == 'true'">
                                    <div class="form-row">
                                        <div class="form-group col-md-8 col-xs-8 col-xs-12">
                                            <label class="control-label required">
                                                Company Name
                                            </label>
                                            <input type="text" name="company_name" class="form-control" v-model="customerForm.company_name" :class="{ 'is-invalid' : formErrors['company_name'] }">
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['company_name']">
                                                <strong>@{{ formErrors['company_name'][0] }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-md-4 col-xs-4 col-xs-12">
                                            <label class="control-label">
                                                ROC
                                            </label>
                                            <input type="text" name="roc" class="form-control" v-model="customerForm.roc" :class="{ 'is-invalid' : formErrors['roc'] }">
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['roc']">
                                                <strong>@{{ formErrors['roc'][0] }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3">
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label required">
                                                Name
                                            </label>
                                            <input type="text" name="name" class="form-control" v-model="customerForm.name" :class="{ 'is-invalid' : formErrors['name'] }">
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                                <strong>@{{ formErrors['name'][0] }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label required">
                                                Email
                                            </label>
                                            <input type="text" name="email" class="form-control" v-model="customerForm.email" :class="{ 'is-invalid' : formErrors['email'] }">
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['email']">
                                                <strong>@{{ formErrors['email'][0] }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label required">
                                                Country Code
                                            </label>
                                            <multiselect
                                            v-model="customerForm.phone_country"
                                            :options="countries"
                                            :close-on-select="true"
                                            placeholder="Select..."
                                            :custom-label="customLabelCountriesOption"
                                            track-by="id"
                                            @input="onPhoneNumberEntered"
                                          ></multiselect>
                                        </div>
                                        <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                            <label class="control-label required">
                                                Phone Number
                                            </label>
                                            <input type="text" name="phone_number" class="form-control" v-model="customerForm.phone_number" :class="{ 'is-invalid' : formErrors['phone_number'] }" @input="onPhoneNumberEntered">
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['phone_number']">
                                                <strong>@{{ formErrors['phone_number'][0] }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label class="control-label required">
                                            Payment Term
                                        </label>
                                        <select2 class="form-group" name="country" v-model="form.payment_term_id">
                                            <option value="">Nope</option>
                                            <option v-for="option in paymentTermOptions" :value="option.id">
                                                @{{option.name}}
                                            </option>
                                        </select2>
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['payment_term_id']">
                                            <strong>@{{ formErrors['payment_term_id'][0] }}</strong>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row text-center">
                            <h4>
                                <span class="badge badge-primary">
                                    Address
                                </span>
                            </h4>
                        </div>
                        <div class="card form-row">
                            <div class="card-body">
                                <h4>
                                    <span class="badge badge-success">
                                        Delivery Address
                                    </span>
                                </h4>

                                <div class="form-row pt-2">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingDeliveryAddress" @change="resetObject('existingDeliveryAddress')" :disabled="addressForm.addresses.length === 0">
                                            <label class="form-check-label">Existing Address</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingDeliveryAddress" @change="resetObject('existingDeliveryAddress')">
                                            <label class="form-check-label">Create New Address</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="radioOption.existingDeliveryAddress === 'true'">
                                        <multiselect
                                        v-model="deliveryAddressForm.address"
                                        :options="addressForm.addresses"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelFullAddress"
                                        track-by="id"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div v-if="radioOption.existingDeliveryAddress === 'false'">
                                        <div class="form-row">
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <label class="control-label">
                                                    Name OR Company OR Nickname (Optional)
                                                </label>
                                                <input type="text" name="name" class="form-control" v-model="deliveryAddressForm.name" :class="{ 'is-invalid' : formErrors['name'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                                    <strong>@{{ formErrors['name'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                <label class="control-label required">
                                                    Unit #
                                                </label>
                                                <input type="text" name="unit" class="form-control" v-model="deliveryAddressForm.unit" :class="{ 'is-invalid' : formErrors['unit'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['unit']">
                                                    <strong>@{{ formErrors['unit'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                <label class="control-label">
                                                    Block #
                                                </label>
                                                <input type="text" name="block" class="form-control" v-model="deliveryAddressForm.block" :class="{ 'is-invalid' : formErrors['block'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['block']">
                                                    <strong>@{{ formErrors['block'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                <label class="control-label">
                                                    Building Name
                                                </label>
                                                <input type="text" name="building_name" class="form-control" v-model="deliveryAddressForm.building_name" :class="{ 'is-invalid' : formErrors['building_name'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['building_name']">
                                                    <strong>@{{ formErrors['building_name'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                                <label class="control-label required">
                                                    Street Name
                                                </label>
                                                <input type="text" name="road_name" class="form-control" v-model="deliveryAddressForm.road_name" :class="{ 'is-invalid' : formErrors['road_name'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['road_name']">
                                                    <strong>@{{ formErrors['road_name'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                <label class="control-label required">
                                                    Area
                                                </label>
                                                <input type="text" name="area" class="form-control" v-model="deliveryAddressForm.area" :class="{ 'is-invalid' : formErrors['area'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['area']">
                                                    <strong>@{{ formErrors['area'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label required">
                                                    Postcode
                                                </label>
                                                <input type="text" name="postcode" class="form-control" v-model="deliveryAddressForm.postcode" :class="{ 'is-invalid' : formErrors['postcode'] }">
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['postcode']">
                                                    <strong>@{{ formErrors['postcode'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label required">
                                                    State
                                                </label>
                                                <multiselect
                                                    v-model="deliveryAddressForm.state"
                                                    :options="states"
                                                    :close-on-select="true"
                                                    placeholder="Select..."
                                                    :custom-label="customLabelName"
                                                    track-by="id"
                                                ></multiselect>
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['state']">
                                                    <strong>@{{ formErrors['state'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="deliveryAddressForm.address || (deliveryAddressForm.unit && deliveryAddressForm.postcode)">
                        {{-- <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="addressForm.delivery_address && !form.id"> --}}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="true" v-model="radioOption.sameBillingDeliveryAddress" @change="resetObject('sameBillingDeliveryAddress')">
                                <label class="form-check-label">Same Billing Address</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="false" v-model="radioOption.sameBillingDeliveryAddress" @change="resetObject('sameBillingDeliveryAddress')">
                                <label class="form-check-label">Different Billing Address</label>
                            </div>
                        </div>

                        <div class="card form-row" v-if="radioOption.sameBillingDeliveryAddress === 'false' && (deliveryAddressForm.address || (deliveryAddressForm.unit && deliveryAddressForm.postcode))">
                            <div class="card-body">
                                <h4>
                                    <span class="badge badge-success">
                                        Billing Address
                                    </span>
                                </h4>

                                <div class="form-row pt-2">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingBillingAddress" @change="resetObject('existingBillingAddress')" :disabled="addressForm.addresses.length === 0">
                                            <label class="form-check-label">Existing Address</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingBillingAddress" @change="resetObject('existingBillingAddress')">
                                            <label class="form-check-label">Create New Address</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="radioOption.existingBillingAddress === 'true'">
                                        <multiselect
                                        v-model="billingAddressForm.address"
                                        :options="addressForm.addresses"
                                        :close-on-select="true"
                                        placeholder="Select..."
                                        :custom-label="customLabelFullAddress"
                                        track-by="id"
                                        ></multiselect>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div v-if="radioOption.existingBillingAddress === 'false'">
                                            <div class="form-row">
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <label class="control-label">
                                                        Name OR Company OR Nickname (Optional)
                                                    </label>
                                                    <input type="text" name="name" class="form-control" v-model="billingAddressForm.name" :class="{ 'is-invalid' : formErrors['name'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                                        <strong>@{{ formErrors['name'][0] }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label class="control-label required">
                                                        Unit #
                                                    </label>
                                                    <input type="text" name="unit" class="form-control" v-model="billingAddressForm.unit" :class="{ 'is-invalid' : formErrors['unit'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['unit']">
                                                        <strong>@{{ formErrors['unit'][0] }}</strong>
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label class="control-label">
                                                        Block #
                                                    </label>
                                                    <input type="text" name="block" class="form-control" v-model="billingAddressForm.block" :class="{ 'is-invalid' : formErrors['block'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['block']">
                                                        <strong>@{{ formErrors['block'][0] }}</strong>
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label class="control-label">
                                                        Building Name
                                                    </label>
                                                    <input type="text" name="building_name" class="form-control" v-model="billingAddressForm.building_name" :class="{ 'is-invalid' : formErrors['building_name'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['building_name']">
                                                        <strong>@{{ formErrors['building_name'][0] }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                                    <label class="control-label required">
                                                        Street Name
                                                    </label>
                                                    <input type="text" name="road_name" class="form-control" v-model="billingAddressForm.road_name" :class="{ 'is-invalid' : formErrors['road_name'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['road_name']">
                                                        <strong>@{{ formErrors['road_name'][0] }}</strong>
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                                    <label class="control-label required">
                                                        Area
                                                    </label>
                                                    <input type="text" name="area" class="form-control" v-model="billingAddressForm.area" :class="{ 'is-invalid' : formErrors['area'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['area']">
                                                        <strong>@{{ formErrors['area'][0] }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                    <label class="control-label required">
                                                        Postcode
                                                    </label>
                                                    <input type="text" name="postcode" class="form-control" v-model="billingAddressForm.postcode" :class="{ 'is-invalid' : formErrors['postcode'] }">
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['postcode']">
                                                        <strong>@{{ formErrors['postcode'][0] }}</strong>
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                    <label class="control-label required">
                                                        State
                                                    </label>
                                                    <multiselect
                                                        v-model="billingAddressForm.state"
                                                        :options="states"
                                                        :close-on-select="true"
                                                        placeholder="Select..."
                                                        :custom-label="customLabelName"
                                                        track-by="id"
                                                    ></multiselect>
                                                    <span class="invalid-feedback" role="alert" v-if="formErrors['state']">
                                                        <strong>@{{ formErrors['state'][0] }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary" @click.prevent="show_add_item = !show_add_item">
                                    <span v-if="!show_add_item">
                                        Click to
                                    </span>
                                    <span v-else>
                                        (Hide)
                                    </span>
                                    Add Item
                                    <i class="fas fa-caret-down" v-if="!show_add_item"></i>
                                    <i class="fas fa-caret-right" v-else></i>
                                </button>
                            </div>
                    </div>
                    <div class="border border-info" v-if="show_add_item">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2">
                            <label class="control-label required">Product</label>
                                <multiselect
                                v-model="itemForm.product"
                                :options="itemOptions"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                @input="onProductSelected()"
                                ></multiselect>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['items']">
                                <strong>@{{ formErrors['items'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="itemForm.product && itemForm.product.is_material">
                            <label class="control-label required">Material</label>
                                <multiselect
                                v-model="itemForm.material"
                                :options="bindedMaterials"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="itemForm.product && itemForm.product.is_shape">
                            <label class="control-label required">Shape</label>
                                <multiselect
                                v-model="itemForm.shape"
                                :options="bindedShapes"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="itemForm.product && itemForm.product.is_lamination">
                            <label class="control-label required">Lamination</label>
                                <multiselect
                                v-model="itemForm.lamination"
                                :options="bindedLaminations"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="itemForm.product && itemForm.product.is_frame">
                            <label class="control-label required">Frame</label>
                                <multiselect
                                v-model="itemForm.frame"
                                :options="bindedFrames"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2" v-if="itemForm.product && itemForm.product.is_finishing">
                            <label class="control-label required">Finishing</label>
                                <multiselect
                                v-model="itemForm.finishing"
                                :options="bindedFinishings"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                Description (Display on PDF)
                            </label>
                            <textarea class="form-control" name="description" rows="2" v-model="itemForm.description"></textarea>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-row">
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label required">Qty</label>
                                    <input type="text" name="qty" class="form-control text-right" v-model="itemForm.qty" @input="calculateAmount()"
                                           :class="{ 'is-invalid' : formErrors['items'] }"
                                    >
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['items']">
                                        <strong>@{{ formErrors['items'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label required">Price (RM)</label>
                                    <input type="text" name="price" class="form-control text-right" v-model="itemForm.price" @input="calculateAmount()"
                                           :class="{ 'is-invalid' : formErrors['items'] }"
                                    >
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['items']">
                                        <strong>@{{ formErrors['items'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">Amount (RM)</label>
                                    <input type="text" name="amount" class="form-control text-right" v-model="itemForm.amount" @input="calculatePrice()">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['amount']">
                                        <strong>@{{ formErrors['amount'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" :disabled="!itemForm.product || !itemForm.qty || !itemForm.price" @click.prevent="addItem()">
                                    <i class="far fa-check-circle"></i>
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 pt-2">
                        <div class="text-center">
                            <h4>
                                <span class="badge badge-light">
                                    Item(s) List
                                </span>
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr class="back-happyrent-light-green text-white">
                                <th class="text-center">
                                    #
                                </th>
                                <th class="text-center">
                                Items
                                </th>
                                <th class="text-center">
                                Quantity
                                </th>
                                <th class="text-center">
                                Price (RM)
                                </th>
                                <th class="text-center">
                                Amount (RM)
                                </th>
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                            <tr v-for="(data, index) in itemForm.items">
                                <td class="text-center">
                                    @{{index + 1}}
                                </td>
                                <td class="text-left">
                                    <span class="font-weight-bold text-uppercase" v-if="data.item">
                                        @{{data.item.name}}
                                    </span>
                                    <div class="pl-2 pt-0">
                                        <span style="font-weight: bold; font-size: 12px;" v-if="data.material">
                                            <br><strong>Material:</strong> @{{ data.material.name }}
                                        </span>
                                        <span style="font-weight: bold; font-size: 12px;" v-if="data.shape">
                                            <br><strong>Shape:</strong> @{{ data.shape.name }}
                                        </span>
                                        <span style="font-weight: bold; font-size: 12px;" v-if="data.lamination">
                                            <br><strong>Lamination:</strong> @{{ data.lamination.name }}
                                        </span>
                                        <span style="font-weight: bold; font-size: 12px;" v-if="data.frame">
                                            <br><strong>Frame:</strong> @{{ data.frame.name }}
                                        </span>
                                        <span style="font-weight: bold; font-size: 12px;" v-if="data.finishing">
                                            <br><strong>Finishing:</strong> @{{ data.finishing.name }}
                                        </span>
                                        <div class="pre-formatted">
                                            <small>@{{data.description}}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    @{{data.qty}}
                                </td>
                                <td class="text-right">
                                    @{{data.price}}
                                </td>
                                <td class="text-right">
                                    @{{data.amount}}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeItem(index)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="form.items && form.items.length > 0">
                                <th colspan="4" class="text-center font-weight-bold">
                                    Total
                                </th>
                                <th class="text-right">
                                    @{{ form.id ? form.grandtotal : total }}
                                </th>
                            </tr>
                            <tr v-if="form.items && form.items.length == 0">
                                <td colspan="18" class="text-center"> No Results Found </td>
                            </tr>
                        </table>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Remarks
                        </label>
                        <textarea name="remarks" class="form-control" rows="3" v-model="transactionForm.remarks"></textarea>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Internal Remarks (Hidden)
                        </label>
                        <textarea name="hidden_remarks" class="form-control" rows="3" v-model="transactionForm.hidden_remarks"></textarea>
                    </div>
{{--
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Attachments
                        </label>
                        <input ref="logoInput" type="file" name="logo" class="form-control-file" v-on:change="onFileChange">
                    </div>

                    <div v-if="form.attachment" class="form-group col-md-12 col-sm-12 col-xs-12" style="position: relative;">
                        <img :src="form.attachment" alt="No photo found" height="100%">
                        <button type="button" class='btn btn-sm btn-danger' style="position: absolute; top: 0" @click="removeLogo()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                      <button type="button" class="btn btn-success" v-if="!transactionForm.id" @click.prevent="onSubmit()">Create</button>
                      <button type="button" class="btn btn-outline-success" v-if="!transactionForm.id" @click.prevent="onSubmit(true)">Create & Convert Invoice</button>
                      <button type="button" class="btn btn-primary" v-if="transactionForm.id" @click.prevent="onSubmit(true)">Convert Invoice</button>

                    </div>
                    <div class="btn-group">
                        <a :href="'/transaction/invoice/' + transactionForm.id"  target="_blank" class="btn btn-outline-primary" v-if="transactionForm.id">
                            <i class="far fa-file-pdf"></i>
                            Invoice PDF
                        </a>
                        <button type="button" class="btn btn-success" v-if="transactionForm.id" @click.prevent="onSubmit()">Save</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                      </div>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
  </template>
