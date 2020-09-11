<template id="sales-channel-template">
  <div>
  <div class="row justify-content-center">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <tr class="table-primary">
                <th class="text-center" colspan="12">
                  Sales Sources
                  <button class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#sales_source_modal" @click="createSingleEntry()">
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
                <td class="text-left">
                    @{{ data.desc }}
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeSingleEntry(index)">
                    <i class="fas fa-times"></i>
                </button>
                </td>
            </tr>
        </table>
    </div>
  </div>

  <div ref="modal" class="modal" id="sales_source_modal" v-if="form">
      <div class="modal-dialog modal-lg hp-form">
          <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{ action === 'update' ? 'Edit Sales Source' : 'New Sales Source' }}
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
                    <div class="form-group">
                      <label class="control-label">
                          Desc
                      </label>
                      <textarea name="desc" class="form-control" rows="3" v-model="form.desc"></textarea>
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