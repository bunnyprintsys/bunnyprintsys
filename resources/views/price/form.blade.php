<div class="modal" id="data_modal">
  <div class="modal-dialog modal-xl">
      <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
      <div class="modal-content">
          <div class="modal-header text-white">
              <div class="modal-title">
                Create @{{formTitle}}
              </div>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
              {{-- @{{formOptions.name}} --}}
              <div class="col-md-12 col-sm-12 col-xs-12">
                  {{--
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value=1 v-model="radioOptions.existing" @change="resetExisting()">
                            <label class="form-check-label">Existing</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value=2 v-model="radioOptions.existing" @change="resetExisting()">
                            <label class="form-check-label">Create New</label>
                        </div>
                    </div>--}}
                    <div class="form-group" v-show="formOptions.name && formOptions.is_select_name">
                        <label for="name" class="control-label">Name</label>
                        <label for="art" style="color: red;">*</label>
                        {{-- <select2-must v-model="form.model">
                            <option value=""></option>
                            <option v-for="model in modelOptions" v-bind:key="model.id" :value="model">
                                @{{model.name}}
                            </option>
                        </select2-must> --}}
                        <multiselect
                            v-model="form.model"
                            :options="modelOptions"
                            :close-on-select="true"
                            placeholder="Select..."
                            :custom-label="customLabelName"
                            track-by="id"
                        ></multiselect>

                        <hr>
                    </div>

                      <div class="form-group" v-show="formOptions.name && !formOptions.is_select_name">
                          <label class="control-label">
                            Name
                          </label>
                          <label for="art" style="color: red;">*</label>
                          <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                              <strong>@{{ formErrors['name'][0] }}</strong>
                          </span>
                          <hr>
                      </div>
                      <div class="form-group" v-if="formOptions.min">
                          <label class="control-label">
                            Min
                          </label>
                          <label for="art" style="color: red;">*</label>
                          <input type="text" name="min" class="form-control" v-model="form.min" :class="{ 'is-invalid' : formErrors['min'] }">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['min']">
                              <strong>@{{ formErrors['min'][0] }}</strong>
                          </span>
                      </div>
                      <div class="form-group" v-if="formOptions.max">
                          <label class="control-label">
                            Max
                          </label>
                          <label for="art" style="color: red;">*</label>
                          <input type="text" name="max" class="form-control" v-model="form.max" :class="{ 'is-invalid' : formErrors['max'] }">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['max']">
                              <strong>@{{ formErrors['max'][0] }}</strong>
                          </span>
                      </div>
                      <div class="form-group" v-if="formOptions.multiplier">
                          <label class="control-label">
                            Multiplier
                          </label>
                          <label for="art" style="color: red;">*</label>
                          <input type="text" name="multiplier" class="form-control" v-model="form.multiplier" :class="{ 'is-invalid' : formErrors['multiplier'] }">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['multiplier']">
                              <strong>@{{ formErrors['multiplier'][0] }}</strong>
                          </span>
                      </div>
                      <div class="form-group" v-if="formOptions.qty">
                          <label class="control-label">
                            Qty
                          </label>
                          <label for="art" style="color: red;">*</label>
                          <input type="text" name="qty" class="form-control" v-model="form.qty" :class="{ 'is-invalid' : formErrors['qty'] }">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['qty']">
                              <strong>@{{ formErrors['qty'][0] }}</strong>
                          </span>
                      </div>
                  {{-- </div> --}}
              </div>
          </div>
          <div class="modal-footer">
              <div class="btn-group">
                <button type="submit" class="btn btn-success">Create</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
              </div>
          </div>
          </form>
      </div>
  </div>
</div>