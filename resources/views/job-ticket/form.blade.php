<template id="form-job-ticket-template">
    <div class="modal" id="single_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Job Ticket' : 'New Job Ticket'}}
                    </div>
                    <button type="button" class="close" @click="closeModal('single_modal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label required">
                                Status
                            </label>
                            <multiselect
                                v-model="form.status"
                                :options="statuses"
                                :close-on-select="true"
                                placeholder="Select..."
                                :custom-label="customLabelName"
                                track-by="id"
                            ></multiselect>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['status']">
                                <strong>@{{ formErrors['status'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="form-row">
                                @can('job-tickets-exec-read')
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Doc No
                                    </label>
                                    <input type="text" name="doc_no" class="form-control" v-model="form.doc_no" :class="{ 'is-invalid' : formErrors['doc_no'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['doc_no']">
                                        <strong>@{{ formErrors['doc_no'][0] }}</strong>
                                    </span>
                                </div>
                                @endcan
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Doc Date
                                    </label>
                                    <datepicker
                                        name="doc_date"
                                        v-model="form.doc_date"
                                        format="yyyy-MM-dd"
                                        :monday-first="true"
                                        :bootstrap-styling="true"
                                        placeholder="Date From"
                                        autocomplete="off"
                                        @input=onDateChanged('doc_date')
                                        @role('production') disabled @endrole
                                        >
                                    </datepicker>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['doc_date']">
                                        <strong>@{{ formErrors['doc_date'][0] }}</strong>
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
                                        <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingCustomer" @change="resetObject('existingCustomer')" @role('production') disabled @endrole>
                                        <label class="form-check-label">Existing Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingCustomer" @change="resetObject('existingCustomer')" @role('production') disabled @endrole>
                                        <label class="form-check-label">Create New Customer</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingCustomer === 'true'">
                                    <multiselect
                                    v-model="form.customer"
                                    :options="customers"
                                    :close-on-select="true"
                                    placeholder="Select..."
                                    :custom-label="customLabelCodeName"
                                    track-by="id"
                                    @role('production') disabled @endrole
                                    ></multiselect>
                                </div>

                                <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingCustomer === 'false'">
                                    <div class="pt-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-xs-6 col-xs-12">
                                                <label class="control-label required">
                                                    Code
                                                </label>
                                                <input type="text" name="customer_code" class="form-control" v-model="form.customer_code" :class="{ 'is-invalid' : formErrors['customer_code'] }" @role('production') disabled @endrole>
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['customer_code']">
                                                    <strong>@{{ formErrors['customer_code'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6 col-xs-12">
                                                <label class="control-label required">
                                                    Name
                                                </label>
                                                <input type="text" name="customer_name" class="form-control" v-model="form.customer_name" :class="{ 'is-invalid' : formErrors['customer_name'] }" @role('production') disabled @endrole>
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['customer_name']">
                                                    <strong>@{{ formErrors['customer_name'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.address">
                            <div class="form-row text-center">
                                <h4>
                                    <span class="badge badge-primary">
                                        Address
                                    </span>
                                </h4>
                            </div>
                            <div class="form-row pt-2">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    @can('job-tickets-exec-read')
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">
                                                Attn Name
                                            </label>
                                            <input type="text" name="address_name" class="form-control" v-model="form.address.name" :class="{ 'is-invalid' : formErrors['address_name'] }" @role('production') disabled @endrole>
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['address_name']">
                                                <strong>@{{ formErrors['address_name'][0] }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">
                                                Contact Num
                                            </label>
                                            <input type="text" name="address_contact" class="form-control" v-model="form.address.contact" :class="{ 'is-invalid' : formErrors['address_contact'] }" @role('production') disabled @endrole>
                                            <span class="invalid-feedback" role="alert" v-if="formErrors['address_contact']">
                                                <strong>@{{ formErrors['address_contact'][0] }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    @endcan
                                    <label class="control-label">
                                        Address
                                    </label>
                                    <textarea name="slug_address" rows="3" class="form-control" v-model="form.address.slug_address" :class="{ 'is-invalid' : formErrors['slug_address'] }" @role('production') disabled @endrole></textarea>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['slug_address']">
                                        <strong>@{{ formErrors['slug_address'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-row text-center">
                                <h4>
                                    <span class="badge badge-primary">
                                        Product
                                    </span>
                                </h4>
                            </div>
                            <div class="form-row pt-2">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="true" v-model="radioOption.existingProduct" @change="resetObject('existingProduct')" @role('production') disabled @endrole>
                                        <label class="form-check-label">Existing Product</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="false" v-model="radioOption.existingProduct" @change="resetObject('existingProduct')" @role('production') disabled @endrole>
                                        <label class="form-check-label">Create New Product</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingProduct === 'true'">
                                    <multiselect
                                    v-model="form.product"
                                    :options="products"
                                    :close-on-select="true"
                                    placeholder="Select..."
                                    :custom-label="customLabelCodeName"
                                    track-by="id"
                                    @role('production') disabled @endrole
                                    ></multiselect>
                                </div>

                                <div class="form-group col-md-12 col-sm-12 col-xs-12" v-show="radioOption.existingProduct === 'false'">
                                    <div class="pt-3">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-xs-6 col-xs-12">
                                                <label class="control-label required">
                                                    Code
                                                </label>
                                                <input type="text" name="product_code" class="form-control" v-model="form.product_code" :class="{ 'is-invalid' : formErrors['product_code'] }" @role('production') disabled @endrole>
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['product_code']">
                                                    <strong>@{{ formErrors['product_code'][0] }}</strong>
                                                </span>
                                            </div>
                                            <div class="form-group col-md-6 col-xs-6 col-xs-12">
                                                <label class="control-label required">
                                                    Name
                                                </label>
                                                <input type="text" name="product_name" class="form-control" v-model="form.product_name" :class="{ 'is-invalid' : formErrors['product_name'] }" @role('production') disabled @endrole>
                                                <span class="invalid-feedback" role="alert" v-if="formErrors['product_name']">
                                                    <strong>@{{ formErrors['product_name'][0] }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                Remarks
                            </label>
                            <textarea name="remarks" class="form-control" v-model="form.remarks" rows="4" @role('production') disabled @endrole></textarea>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['qty']">
                                <strong>@{{ formErrors['qty'][0] }}</strong>
                            </span>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Qty
                                    </label>
                                    <input type="text" name="qty" class="form-control" v-model="form.qty" :class="{ 'is-invalid' : formErrors['qty'] }" @role('production') disabled @endrole>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['qty']">
                                        <strong>@{{ formErrors['qty'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12" v-if="form.uom">
                                    <label class="control-label">
                                        UOM
                                    </label>
                                    <input type="text" name="uom" class="form-control" v-model="form.uom.name" :class="{ 'is-invalid' : formErrors['uom'] }" disabled>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['uom']">
                                        <strong>@{{ formErrors['uom'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Delivery Method
                                    </label>
                                    <multiselect
                                    v-model="form.delivery_method"
                                    :options="deliveryMethods"
                                    :close-on-select="true"
                                    placeholder="Select..."
                                    :custom-label="customLabelName"
                                    track-by="id"
                                    @role('production') disabled @endrole
                                    ></multiselect>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_method']">
                                        <strong>@{{ formErrors['delivery_method'][0] }}</strong>
                                    </span>
                                </div>
                                <!-- <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        Delivery Remarks
                                    </label>
                                    <textarea name="delivery_remarks" rows="2" class="form-control" v-model="form.delivery_remarks" :class="{ 'is-invalid' : formErrors['delivery_remarks'] }"></textarea>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_remarks']">
                                        <strong>@{{ formErrors['delivery_remarks'][0] }}</strong>
                                    </span>
                                </div> -->
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Delivery Date
                                    </label>
                                    <datepicker
                                        name="delivery_date"
                                        v-model="form.delivery_date"
                                        format="yyyy-MM-dd"
                                        :monday-first="true"
                                        :bootstrap-styling="true"
                                        placeholder="Date From"
                                        autocomplete="off"
                                        @input=onDateChanged('delivery_date')
                                        @role('production') disabled @endrole
                                        >
                                    </datepicker>
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_date']">
                                        <strong>@{{ formErrors['delivery_date'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                Artwork URL
                            </label>
                            <input type="text" name="url_link" class="form-control" v-model="form.url_link" :class="{ 'is-invalid' : formErrors['url_link'] }" @role('production') disabled @endrole>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['url_link']">
                                <strong>@{{ formErrors['url_link'][0] }}</strong>
                            </span>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                Agent Name
                            </label>
                            <input type="text" name="agent_name" class="form-control" v-model="form.agent_name" :class="{ 'is-invalid' : formErrors['agent_name'] }" @role('production') disabled @endrole>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['agent_name']">
                                <strong>@{{ formErrors['agent_name'][0] }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                      <button type="submit" class="btn btn-success" v-if="!form.id">Create</button>
                      <button type="submit" class="btn btn-success" v-if="form.id">Save</button>
                      <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
  </template>
