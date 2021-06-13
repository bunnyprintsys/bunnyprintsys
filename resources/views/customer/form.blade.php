<template id="form-customer-template">
    <div class="modal" id="customer_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Customer' : 'New Customer'}}
                    </div>
                    <button type="button" class="close" @click="closeModal('customer_modal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label required">
                                Is Company
                            </label>
                            <select2-must v-model="form.is_company">
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select2-must>
                            <span class="invalid-feedback" role="alert" v-if="formErrors['is_company']">
                                <strong>@{{ formErrors['is_company'][0] }}</strong>
                            </span>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" v-if="form.is_company == 'true'">
                            <div class="form-row">
                                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                    <label class="control-label required">
                                        Company Name
                                    </label>
                                    <input type="text" name="company_name" class="form-control" v-model="form.company_name" :class="{ 'is-invalid' : formErrors['company_name'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['company_name']">
                                        <strong>@{{ formErrors['company_name'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                    <label class="control-label required">
                                        ROC
                                    </label>
                                    <input type="text" name="roc" class="form-control" v-model="form.roc" :class="{ 'is-invalid' : formErrors['roc'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['roc']">
                                        <strong>@{{ formErrors['roc'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label required">
                                Attention
                            </label>
                            <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                <strong>@{{ formErrors['name'][0] }}</strong>
                            </span>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row">
                                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                                    <label class="control-label required">
                                        Country Code
                                    </label>
                                    <select2-must v-model="form.phone_country_id">
                                        <option v-for="country in countries" :value="country.id">
                                            +@{{country.code}}
                                        </option>
                                    </select2-must>
                                </div>
                                <div class="form-group col-md-5 col-sm-8 col-xs-12">
                                    <label class="control-label required">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone_number" class="form-control" v-model="form.phone_number" :class="{ 'is-invalid' : formErrors['phone_number'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['phone_number']">
                                        <strong>@{{ formErrors['phone_number'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Email
                                    </label>
                                    <input type="text" name="email" class="form-control" v-model="form.email" :class="{ 'is-invalid' : formErrors['email'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['email']">
                                        <strong>@{{ formErrors['email'][0] }}</strong>
                                    </span>
                                </div>
                        </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
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
                    <hr>
                    <div>
                        @include('customer.form-address-table')
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
