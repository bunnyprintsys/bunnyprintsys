<template id="quantity-multiplier-template">
  <div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <tr class="table-primary">
                <th class="text-center" colspan="10">
                    Quantity Multiplier
                </th>
                <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#quantity_multiplier_modal" @click="createSingleEntry()">
                  <i class="fas fa-plus"></i>
              </button>
            </tr>
            <tr class="table-secondary">
                <th class="text-center">
                    #
                </th>
                <th class="text-center">
                    Min
                </th>
                <th class="text-center">
                    Max
                </th>
                <th class="text-center">
                    Multiplier
                </th>
            </tr>
            <tr v-for="(data, index) in quantitymultipliers" class="row_edit">
                <td class="text-center">
                    @{{ index + 1 }}
                </td>
                <td class="text-right">
                    <input type="text" name="min" class="form-control" v-model="data.min" @keyup="onQtyMultiplierChanged(data)">
                </td>
                <td class="text-right">
                    <input type="text" name="max" class="form-control text-right" v-model="data.max" @keyup="onQtyMultiplierChanged(data)">
                </td>
                <td class="text-right">
                    <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onQtyMultiplierChanged(data)">
                </td>
            </tr>
        </table>
    </div>
    <div ref="modal" class="modal" id="quantity_multiplier" v-if="form">
        <div class="modal-dialog modal-lg hp-form">
            <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header back-happyrent-light-green text-white">
                    <div class="modal-title">
                        @{{ action === 'update' ? 'Edit Quantity Multiplier' : 'New Quantity Multiplier' }}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label required">
                            Min
                        </label>
                        <input type="text" name="min" class="form-control" v-model="form.min" :class="{ 'is-invalid' : formErrors['min'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['min']">
                            <strong>@{{ formErrors['min'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label required">
                            Max
                        </label>
                        <input type="text" name="max" class="form-control" v-model="form.max" :class="{ 'is-invalid' : formErrors['max'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['max']">
                            <strong>@{{ formErrors['max'][0] }}</strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Multiplier
                        </label>
                        <input type="text" name="multiplier" class="form-control" v-model="form.multiplier" :class="{ 'is-invalid' : formErrors['multiplier'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['multiplier']">
                            <strong>@{{ formErrors['multiplier'][0] }}</strong>
                        </span>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                    <button type="submit" class="btn btn-success">@{{ action === 'update' ? 'Save' : 'Create'  }}</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
  </div>
</template>