<template id="form-transaction-template">
    <div class="modal" id="transaction_modal">
        <div class="modal-dialog modal-xl">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{transactionForm.id ? 'Edit Transaction ' + transactionForm.job_id : 'New Transaction'}}
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
                                @{{transactionForm.order_date}}
                                <datepicker
                                    v-model="transactionForm.order_date"
                                    :format="dateFormatter"
                                    :bootstrap-styling="true"
                                    :highlighted="{dates: [new Date()]}"
                                    @input="onDateChanged('order_date')"
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
                                @{{transactionForm.dispatch_date}}
                                <datepicker
                                    v-model="transactionForm.dispatch_date"
                                    :format="dateFormatter"
                                    :bootstrap-styling="true"
                                    :highlighted="{dates: [new Date()]}"
                                    @input="onDateChanged('dispatch_date')"
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

                        <div class="form-row pt-2">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingAddress" @change="resetObject('existingAddress')">
                                    <label class="form-check-label">Existing Address</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingAddress" @change="resetObject('existingAddress')">
                                    <label class="form-check-label">Create New Address</label>
                                </div>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="radioOption.existingAddress === 'true'">
                                <multiselect
                                v-model="addressForm.address"
                                :options="addressForm.addresses"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelFullAddress"
                                track-by="id"
                                ></multiselect>
                            </div>
    {{--
                                <div v-for="address in addresses">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :value="address.id">
                                        <label class="form-check-label">
                                            @{{address.full_address}}
                                        </label>
                                    </div>
                                </div> --}}
                            {{-- </div> --}}

                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div v-if="radioOption.existingAddress === 'false'">
                                <div class="form-row pt-2">
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label required">
                                            Unit #
                                        </label>
                                        <input type="text" name="unit" class="form-control" v-model="addressForm.unit" :class="{ 'is-invalid' : formErrors['unit'] }">
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['unit']">
                                            <strong>@{{ formErrors['unit'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label required">
                                            Block #
                                        </label>
                                        <input type="text" name="block" class="form-control" v-model="addressForm.block" :class="{ 'is-invalid' : formErrors['block'] }">
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['block']">
                                            <strong>@{{ formErrors['block'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label required">
                                            Building Name
                                        </label>
                                        <input type="text" name="building_name" class="form-control" v-model="addressForm.building_name" :class="{ 'is-invalid' : formErrors['building_name'] }">
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['building_name']">
                                            <strong>@{{ formErrors['building_name'][0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label required">
                                            Street Name
                                        </label>
                                        <input type="text" name="road_name" class="form-control" v-model="addressForm.road_name" :class="{ 'is-invalid' : formErrors['road_name'] }">
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['road_name']">
                                            <strong>@{{ formErrors['road_name'][0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label required">
                                            Postcode
                                        </label>
                                        <input type="text" name="postcode" class="form-control" v-model="addressForm.postcode" :class="{ 'is-invalid' : formErrors['postcode'] }">
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['postcode']">
                                            <strong>@{{ formErrors['postcode'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label">
                                            State
                                        </label>
                                        <select2-must class="form-group" name="state" v-model="addressForm.state">
                                            <option value="">Nope</option>
                                            <option v-for="state in states" :value="state.id">
                                                @{{state.name}}
                                            </option>
                                        </select2-must>
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['state']">
                                            <strong>@{{ formErrors['state'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label required">
                                            Country
                                        </label>
                                        <select2-must class="form-group" name="country" v-model="addressForm.country">
                                            <option value="">Nope</option>
                                            <option v-for="country in countries" :value="country.id">
                                                @{{country.name}}
                                            </option>
                                        </select2-must>
                                        <span class="invalid-feedback" role="alert" v-if="formErrors['country']">
                                            <strong>@{{ formErrors['country'][0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        {{-- <div class="text-left"> --}}
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
                        {{-- </div> --}}
                    </div>
                    <div class="border border-info" v-if="show_add_item">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pt-2">
                            <label class="control-label required">Item</label>
{{--
                            <select class="custom-select" v-model="item.item_id"
                                    :class="{ 'is-invalid' : formErrors['items'] }"
                            >
                                <option value=""></option>
                                <option v-for="item in itemOptions" :value="item.id">
                                    @{{item.name}}
                                </option>
                            </select> --}}
                                <multiselect
                                v-model="itemForm.product"
                                :options="itemOptions"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                                ></multiselect>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['items']">
                                <strong>@{{ formErrors['items'][0] }}</strong>
                            </span>
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
                                    <input type="text" name="amount" class="form-control text-right" v-model="itemForm.amount" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                Description (Display on PDF)
                            </label>
                            <textarea class="form-control" name="description" rows="2" v-model="itemForm.description"></textarea>
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
                                    <span style="font-weight: bold;">
                                        @{{data.item.name}}
                                    </span>
                                    <br>
                                    <small>
                                        @{{data.description}}
                                    </small>
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


                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                      <button type="submit" class="btn btn-success" v-if="!transactionForm.id">Create</button>
                      <a :href="'/transaction/invoice/' + transactionForm.id"  target="_blank" class="btn btn-outline-primary" v-if="transactionForm.id">Generate Invoice</a>
                      <button type="submit" class="btn btn-success" v-if="transactionForm.id">Save</button>
                      <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
  </template>
