<template id="index-lamination-template">
  <div>
  <div class="row justify-content-center">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <tr class="table-primary">
                <th class="text-center" colspan="12">
                  Main Lamination
                  <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#lamination_modal" @click="createSingleEntry()">
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
                  Binded Product(s)
                </th>
                <th class="text-center">
                </th>
            </tr>
            <tr v-for="(data, index) in list" class="row_edit">
                <td class="text-center">
                    @{{ index + 1}}
                </td>
                <td class="text-left">
                    @{{ data.name }}
                </td>
                <td class="text-left">
                  <ul v-if="data.productLaminations">
                    <li v-for="product in data.productLaminations">
                      @{{product.product ? product.product.name : ''}}
                    </li>
                  </ul>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#lamination_modal" @click="editSingleEntry(data)">
                      <i class="fas fa-edit"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeSingleEntry(data)">
                    <i class="fas fa-times"></i>
                  </button>
                </td>
            </tr>
            <tr v-if="list.length === 0">
                <td colspan="18" class="text-center"> No Results Found </td>
            </tr>
        </table>
    </div>
  </div>
  <form-lamination @updatetable="searchData" :clearform="clearform" :data="formdata"></form-lamination>
  </div>
</template>

@include('product.form-lamination')