<template id="sales-channel-template">
  <div>
  <div class="row justify-content-center">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <tr class="table-primary">
                <th class="text-center" colspan="12">
                  Sales Sources
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
                  Desc
                </th>
                <th class="text-center">
                </th>
            </tr>
            <tr v-for="(data, index) in sales_channels" class="row_edit">
                <td class="text-center">
                    @{{ index + 1}}
                </td>
                <td class="text-left">
                    @{{ data.name }}
                </td>
                <td class="text-right">
                    @{{ data.desc }}
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeSingleEntry(index)">
                    <i class="fas fa-times"></i>
                </button>
                </td>
            </tr>
{{--
            <tr v-if="! pagination.total">
                <td colspan="18" class="text-center"> No Results Found </td>
            </tr> --}}
        </table>
    </div>
  </div>
  </div>
</template>