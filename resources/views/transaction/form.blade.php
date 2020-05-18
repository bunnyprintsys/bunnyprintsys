<template id="form-transaction-template">
    <div class="modal" id="transaction_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Transaction' : 'New Transaction'}}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
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
