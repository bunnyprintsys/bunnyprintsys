<template id="form-voucher-template">
    <div class="modal" id="voucher_modal">
        <div class="modal-dialog modal-lg">
            <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <div class="modal-title">
                        @{{form.id ? 'Edit Voucher' : 'New Voucher'}}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label required">
                            Name
                        </label>
                        <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                            <strong>@{{ formErrors['name'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label required">
                            Desc
                        </label>
                        <textarea name="desc" rows="3" class="form-control" v-model="form.desc" :class="{ 'is-invalid' : formErrors['desc'] }"></textarea>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['desc']">
                            <strong>@{{ formErrors['desc'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Is Active?
                        </label>
                        <select class="custom-select" v-model="form.is_active">
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['is_active']">
                            <strong>@{{ formErrors['is_active'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Is Only Claim Once?
                        </label>
                        <select class="custom-select" v-model="form.is_unique_customer">
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['is_unique_customer']">
                            <strong>@{{ formErrors['is_unique_customer'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Is Percentage?
                        </label>
                        <select class="custom-select" v-model="form.is_percentage">
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['is_percentage']">
                            <strong>@{{ formErrors['is_percentage'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Value
                            (
                                <span v-if="form.is_percentage == 'true'">Percentage</span>
                                <span v-if="form.is_percentage == 'false'">Absolute Amount</span>
                            )
                        </label>
                        <input type="text" name="value" class="form-control" v-model="form.value" :class="{ 'is-invalid' : formErrors['value'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['value']">
                            <strong>@{{ formErrors['value'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Is Count Limit?
                        </label>
                        <select class="custom-select" v-model="form.is_count_limit">
                            <option value="true">Yes</option>
                            <option value="false">No</option>
                        </select>
                        <span class="invalid-feedback" role="alert" v-if="formErrors['is_count_limit']">
                            <strong>@{{ formErrors['is_count_limit'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Count Limit
                        </label>
                        <input type="text" name="count_limit" class="form-control" v-model="form.count_limit" :class="{ 'is-invalid' : formErrors['value'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['value']">
                            <strong>@{{ formErrors['count_limit'][0] }}</strong>
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
