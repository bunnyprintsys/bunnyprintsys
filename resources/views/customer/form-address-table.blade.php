<div v-show="form.id">
    <div class="section-title">Address</div>
    <div class="card">
        <div class="card-header">
            <div class="form-row">
                <span class="mr-auto">
                    <i class="fas fa-house-user"></i>
                    Address(es)
                </span>
            </div>
        </div>
        <div class="card-body">
            <button type="button" class="btn bg-primary text-white btn-sm ml-auto" @click="onShowAddressClicked()">
                Add address <i class="fas fa-chevron-down" v-if="!showAddAddress"></i>
                            <i class="fas fa-chevron-right" v-if="showAddAddress"></i>
            </button>

            <div class="pt-3" v-show="showAddAddress">
                <div class="form-row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Name OR Company OR Nickname (Optional)
                        </label>
                        <input type="text" name="name" class="form-control" v-model="addressForm.name" :class="{ 'is-invalid' : formErrors['name'] }">
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
                        <input type="text" name="unit" class="form-control" v-model="addressForm.unit" :class="{ 'is-invalid' : formErrors['unit'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['unit']">
                            <strong>@{{ formErrors['unit'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                        <label class="control-label">
                            Block #
                        </label>
                        <input type="text" name="block" class="form-control" v-model="addressForm.block" :class="{ 'is-invalid' : formErrors['block'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['block']">
                            <strong>@{{ formErrors['block'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                        <label class="control-label">
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
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
                            Postcode
                        </label>
                        <input type="text" name="postcode" class="form-control" v-model="addressForm.postcode" :class="{ 'is-invalid' : formErrors['postcode'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['postcode']">
                            <strong>@{{ formErrors['postcode'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                        <label class="control-label required">
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
                    {{-- <div class="form-group col-md-4 col-sm-4 col-xs-12">
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
                    </div> --}}
                </div>
                <div class="form-row">
                    {{-- <div class="form-group col-md-12 col-sm-12 col-xs-12"> --}}
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="checkbox" v-model="addressForm.is_primary">
                            <label class="control-label">
                                Is Primary?
                            </label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="checkbox" v-model="addressForm.is_billing">
                            <label class="control-label">
                                Is Billing?
                            </label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="checkbox" v-model="addressForm.is_delivery">
                            <label class="control-label">
                                Is Delivery?
                            </label>
                        </div>
                    {{-- </div> --}}
                </div>
                <button type="button" class="btn bg-success text-white btn-md ml-auto mt-2" @click.prevent="addSingleAddress()">
                    Add <i class="fas fa-plus-circle"></i>
                </button>
                <hr>
            </div>

            <div class="table-responsive pt-2">
                <table class="table table-bordered table-hover">
                    <tr class="table-secondary">
                        <th class="text-center">
                            #
                        </th>
                        <th class="text-center">
                            Name
                        </th>
                        <th class="text-center">
                            Address
                        </th>
                        <th class="text-center">
                            Postcode
                        </th>
                        <th class="text-center">
                            Tags
                        </th>
                        <th></th>
                    </tr>

                    <tr v-for="(data, index) in form.addresses" class="row_edit" :key="index">
                        <td class="text-center">
                            @{{ index + 1 }}
                        </td>
                        <td class="text-left">
                            @{{ data.name }}
                        </td>
                        <td class="text-left">
                            @{{ data.full_address }}
                        </td>
                        <td class="text-center">
                            @{{ data.postcode }}
                        </td>
                        <td class="text-left">
                            <span class="badge badge-primary" v-if="data.is_primary">
                                Primary
                            </span>
                            <span class="badge badge-secondary" v-if="data.is_billing">
                                Billing
                            </span>
                            <span class="badge badge-success" v-if="data.is_delivery">
                                Delivery
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger btn-sm" @click="removeSingleAddress(data, index)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="form.addresses && form.addresses.length === 0">
                        <td colspan="18" class="text-center"> No Results Found </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>



