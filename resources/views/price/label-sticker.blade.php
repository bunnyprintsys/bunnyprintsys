<template id="price-labelsticker-template">
    <div>
    <div class="row justify-content-center">
      <div class="col-md-11">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Shape
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('shape')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
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
                <tr v-for="(data, index) in shapes" class="row_edit">
                    <td class="text-center">
                        @{{ index + 1}}
                    </td>
                    <td class="text-left">
                        @{{ data.shape.name }}
                    </td>
                    <td class="text-right">
                        <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onProductShapeMultiplierChanged(data.id, data.multiplier)">
                    </td>
                </tr>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Material
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('material')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
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
                        @{{ data.material.name }}
                    </td>
                    <td class="text-right">
                        <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onProductMaterialMultiplierChanged(data.id, data.multiplier)">
                    </td>
                </tr>
{{--
                <tr v-if="! pagination.total">
                    <td colspan="18" class="text-center"> No Results Found </td>
                </tr> --}}
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Lamination
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('lamination')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
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
                <tr v-for="(data, index) in laminations" class="row_edit">
                    <td class="text-center">
                        @{{ index + 1}}
                    </td>
                    <td class="text-left">
                        @{{ data.lamination.name }}
                    </td>
                    <td class="text-right">
                        <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onProductLaminationMultiplierChanged(data.id, data.multiplier)">
                    </td>
                </tr>
{{--
                <tr v-if="! pagination.total">
                    <td colspan="18" class="text-center"> No Results Found </td>
                </tr> --}}
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Delivery
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('deliveries')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
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
                <tr v-for="(data, index) in deliveries" class="row_edit">
                    <td class="text-center">
                        @{{ index + 1 }}
                    </td>
                    <td class="text-left">
                        @{{ data.delivery.name }}
                    </td>
                    <td class="text-right">
                        <input type="text" name="multiplier" class="form-control text-right" v-model="data.multiplier" @keyup="onDeliveryMultiplierChanged(data.id, data.multiplier)">
                    </td>
                </tr>
{{--
                <tr v-if="! pagination.total">
                    <td colspan="18" class="text-center"> No Results Found </td>
                </tr> --}}
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Quantity Multiplier
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('quantitymultipliers')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
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

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <tr class="table-primary">
                    <th class="text-center" colspan="10">
                        Quantities
                        <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#data_modal" @click="createSingleEntry('orderquantities')">
                            <i class="fas fa-plus"></i>
                          </button>
                    </th>
                </tr>
                <tr class="table-secondary">
                    <th class="text-center">
                        #
                    </th>
                    <th class="text-center">
                        Name
                    </th>
                    <th class="text-center">
                        Qty
                    </th>
                </tr>
                <tr v-for="(data, index) in orderquantities" class="row_edit">
                    <td class="text-center">
                        @{{ index + 1 }}
                    </td>
                    <td class="text-right">
                        <input type="text" name="name" class="form-control" v-model="data.name" @keyup="onQtyNameChanged(data.id, data.name)">
                    </td>
                    <td class="text-right">
                        <input type="text" name="qty" class="form-control text-right" v-model="data.qty" @keyup="onQtyChanged(data.id, data.qty)">
                    </td>
                </tr>
            </table>
        </div>

      </div>
    </div>
        @include('price.form')
    </div>

  </template>
