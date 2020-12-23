<template id="form-shape-template">
    <div ref="modal" class="modal" id="shape_modal">
      <div class="modal-dialog modal-lg hp-form">
          <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{ form.id ? 'Edit Shape' : 'New Shape' }}
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
                  </div>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                    <button type="submit" class="btn btn-success">@{{ form.id ? 'Save' : 'Create'  }}</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </template>