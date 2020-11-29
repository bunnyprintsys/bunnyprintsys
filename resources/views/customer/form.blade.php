<template id="form-customer-template">
    <div class="modal" id="customer_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Customer' : 'New Customer'}}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                                Name
                            </label>
                            <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                <strong>@{{ formErrors['name'][0] }}</strong>
                            </span>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row">
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label required">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone_number" class="form-control" v-model="form.phone_number" :class="{ 'is-invalid' : formErrors['phone_number'] }">
                                    <span class="invalid-feedback" role="alert" v-if="formErrors['phone_number']">
                                        <strong>@{{ formErrors['phone_number'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        Alt Phone Number
                                    </label>
                                    <input type="text" name="alt_phone_number" class="form-control" v-model="form.alt_phone_number">
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
                    <div class="section-title">Address</div>
                    <div class="form-row">
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
                        <div class="form-group col-md-8 col-sm-8 col-xs-12">
                            <label class="control-label required">
                                Street Name
                            </label>
                            <input type="text" name="road_name" class="form-control" v-model="addressForm.road_name" :class="{ 'is-invalid' : formErrors['road_name'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['road_name']">
                                <strong>@{{ formErrors['road_name'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label class="control-label required">
                                Area
                            </label>
                            <input type="text" name="area" class="form-control" v-model="addressForm.area" :class="{ 'is-invalid' : formErrors['area'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['area']">
                                <strong>@{{ formErrors['area'][0] }}</strong>
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
