<template id="material-template">
  <div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <tr class="table-primary">
                <th class="text-center" colspan="10">
                    Material
                </th>
                <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#material_modal" @click="createSingleEntry()">
                  <i class="fas fa-plus"></i>
                </button>
            </tr>
            <tr class="table-secondary">
                <th class="text-center">
                    #
                </th>
                <th class="text-center">
                    Name
                </th>
                <th class="text-center">
                    Multiplier
                </th>
            </tr>
            <tr v-for="(data, index) in materials" class="row_edit">
                <td class="text-center">
                    @{{ index + 1}}
                </td>
                <td class="text-left">
                    @{{ data.name }}
                </td>
                <td class="text-right">
                    <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onProductMaterialMultiplierChanged(data.id, data.multiplier)">
                </td>
            </tr>
        </table>
    </div>
    <div ref="modal" class="modal" id="material_modal" v-if="form">
        <div class="modal-dialog modal-lg hp-form">
            <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header back-happyrent-light-green text-white">
                    <div class="modal-title">
                        @{{ action === 'update' ? 'Edit Material' : 'New Material' }}
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label required">
                            Name
                        </label>
                        <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                        <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                            <strong>@{{ formErrors['name'][0] }}</strong>
                        </span>
                    </div>
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