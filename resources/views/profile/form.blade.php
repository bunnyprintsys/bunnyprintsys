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
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-row">
                            <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                <label class="control-label required">
                                    Company Name
                                </label>
                                <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                    <strong>@{{ formErrors['name'][0] }}</strong>
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
                            Address
                        </label>
                        <textarea name="address" rows="3" class="form-control" v-model="form.address" :class="{ 'is-invalid' : formErrors['address'] }"></textarea>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['address']">
                            <strong>@{{ formErrors['address'][0] }}</strong>
                        </span>
                    </div>

                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label required">
                            Country
                        </label>
                        <select2-must v-model="form.country_id">
                            <option v-for="country in countries" :value="country.id">
                                @{{country.name}}
                            </option>
                        </select2-must>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['country_id']">
                            <strong>@{{ formErrors['country_id'][0] }}</strong>
                        </span>
                    </div>
{{--
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label required">
                            ID Type
                        </label>
                        <select class="custom-select" v-model="form.id_type">
                            <option value="">All</option>
                            @foreach($idTypes as $idType)
                                <option value="{{ $idType['code'] }}">
                                    {{ $idType['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['id_type']">
                            <strong>@{{ formErrors['id_type'][0] }}</strong>
                        </span>
                    </div>                     --}}
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
