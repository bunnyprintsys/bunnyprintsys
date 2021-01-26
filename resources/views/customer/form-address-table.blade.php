<div class="card">
    <div class="card-header">
        <div class="form-row">
        <span class="mr-auto">
            <i class="fas fa-house-user"></i>
            Address(es)
        </span>
        <button type="button" class="btn bg-primary text-white btn-sm ml-auto" data-toggle="modal" data-target="#address_modal" @click="createSingleEntry">
            <i class="fas fa-plus"></i>
        </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
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

                <tr v-for="(data, index) in list" class="row_edit">
                    <td class="text-center">
                        @{{ index + pagination.from }}
                    </td>
                    <td class="text-left">
                        @{{ data.name }}
                    </td>
                    <td class="text-left">
                        @{{ data.address }}
                    </td>
                    <td class="text-center">
                        @{{ data.postcode }}
                    </td>
                    <td class="text-left">
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-light btn-outline-secondary btn-sm" data-toggle="modal" data-target="#address_modal" @click="editSingleEntry(data)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeSingleEntry(data)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="! pagination.total">
                    <td colspan="18" class="text-center"> No Results Found </td>
                </tr>
            </table>
        </div>
        <div class="pull-left">
            <pagination :pagination="pagination" :callback="fetchTable" :offset="4"></pagination>
        </div>
    </div>
</div>