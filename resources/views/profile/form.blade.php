<template id="form-profile-template">
    <div class="modal" id="profile_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Profile' : 'New Profile'}}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
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
                            <label class="control-label">
                                ROC
                            </label>
                            <input type="text" name="roc" class="form-control" v-model="form.roc" :class="{ 'is-invalid' : formErrors['roc'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['roc']">
                                <strong>@{{ formErrors['roc'][0] }}</strong>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="form-row pt-2">
                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label required">
                                    Unit #
                                </label>
                                <input type="text" name="unit" class="form-control" v-model="form.unit" :class="{ 'is-invalid' : formErrors['unit'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['unit']">
                                    <strong>@{{ formErrors['unit'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label ">
                                    Block #
                                </label>
                                <input type="text" name="block" class="form-control" v-model="form.block" :class="{ 'is-invalid' : formErrors['block'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['block']">
                                    <strong>@{{ formErrors['block'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label">
                                    Building Name
                                </label>
                                <input type="text" name="building_name" class="form-control" v-model="form.building_name" :class="{ 'is-invalid' : formErrors['building_name'] }">
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
                                <input type="text" name="road_name" class="form-control" v-model="form.road_name" :class="{ 'is-invalid' : formErrors['road_name'] }">
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
                                <input type="text" name="postcode" class="form-control" v-model="form.postcode" :class="{ 'is-invalid' : formErrors['postcode'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['postcode']">
                                    <strong>@{{ formErrors['postcode'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label">
                                    State
                                </label>
                                <select2-must class="form-group" name="state" v-model="form.state_id">
                                    <option value="">Nope</option>
                                    <option v-for="state in states" :value="state.id">
                                        @{{state.name}}
                                    </option>
                                </select2-must>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['state_id']">
                                    <strong>@{{ formErrors['state_id'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label required">
                                    Country
                                </label>
                                <select2-must class="form-group" name="country" v-model="form.country_id">
                                    <option value="">Nope</option>
                                    <option v-for="country in countries" :value="country.id">
                                        @{{country.name}}
                                    </option>
                                </select2-must>
                                <span class="invalid-feedback" role="alert" v-if="formErrors['country_id']">
                                    <strong>@{{ formErrors['country_id'][0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label required">
                                    Job ID Prefix
                                </label>
                                <input type="text" name="job_prefix" class="form-control" v-model="form.job_prefix" :class="{ 'is-invalid' : formErrors['job_prefix'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['job_prefix']">
                                    <strong>@{{ formErrors['job_prefix'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label required">
                                    Invoice Num Prefix
                                </label>
                                <input type="text" name="invoice_prefix" class="form-control" v-model="form.invoice_prefix" :class="{ 'is-invalid' : formErrors['invoice_prefix'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['invoice_prefix']">
                                    <strong>@{{ formErrors['invoice_prefix'][0] }}</strong>
                                </span>
                            </div>
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
