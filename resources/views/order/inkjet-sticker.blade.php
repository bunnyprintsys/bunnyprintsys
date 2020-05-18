<template id="inkjet-sticker-template">
    <div class="panel panel-default pt-5">
        <div class="panel-body screen-panel">
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Materials
                      <label for="required" class="control-label" style="color:red;">*</label>
                    </label>

                    <multiselect v-model="form.material_id" placeholder="Select Material" label="name" track-by="id" :options="materials" :internal-search="true" :custom-label="customLabel" @input="getQuotation()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>

                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['material_id'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['material_id']">
                        <strong>@{{ formErrors['material_id'][0] }}</strong>
                    </span>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Shape
                      <label for="required" class="control-label" style="color:red;">*</label>
                    </label>
    {{--
                    <select2 v-model="form.shape_id" class="form-control" @input="shapeChosen()">
                        <option value="">All</option>
                        <option v-for="shape in shapes" :value="shape.id">
                          @{{shape.name}}
                        </option>
                    </select2> --}}
                    <multiselect v-model="form.shape_id" placeholder="Select Shape" label="name" track-by="id" :options="shapes" :internal-search="true" :custom-label="customLabel" @input="shapeChosen()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>
                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['shape_id'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['shape_id']">
                        <strong>@{{ formErrors['shape_id'][0] }}</strong>
                    </span>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Size
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label class="control-label">
                            Width (cm)
                          </label>
                          <input type="text" name="width" class="form-control" v-model="form.width" :class="{ 'is-invalid' : formErrors['width'] }" :placeholder="'Maximum '+formsetup.max_width+'cm'" @input="enterSize()">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['width']">
                              <strong>@{{ formErrors['width'][0] }}</strong>
                          </span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label class="control-label">
                            Height (cm)
                          </label>
                          <input type="text" name="height" class="form-control" v-model="form.height" :class="{ 'is-invalid' : formErrors['height'] }" :placeholder="'Maximum '+formsetup.max_height+'cm'" @input="enterSize()">
                          <span class="invalid-feedback" role="alert" v-if="formErrors['height']">
                              <strong>@{{ formErrors['height'][0] }}</strong>
                          </span>
                        </div>
                      </div>
                      <span style="color:red;">
                        @{{combine_sticker_str}}
                      </span>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Laminations
                    </label>

                    <multiselect v-model="form.lamination_id" placeholder="Select Lamination" label="name" track-by="id" :options="laminations" :internal-search="true" :custom-label="customLabel" @input="getQuotation()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>
                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['lamination_id'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['lamination_id']">
                        <strong>@{{ formErrors['lamination_id'][0] }}</strong>
                    </span>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="is_finishing_enable">
                    <label class="control-label">
                      Finishing
                    </label>

                    <multiselect v-model="form.finishing_id" placeholder="Select Finishing" label="name" track-by="id" :options="finishings" :internal-search="true" :custom-label="customLabel" @input="getQuotation()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>
                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['finishing_id'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['finishing_id']">
                        <strong>@{{ formErrors['finishing_id'][0] }}</strong>
                    </span>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="form.finishing_id.id">
                    <label class="control-label">
                      Frame
                    </label>

                    <multiselect v-model="form.frame_id" placeholder="Select Frame" label="name" track-by="id" :options="frames" :internal-search="true" :custom-label="customLabel" @input="getQuotation()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>
                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['frame_id'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['frame_id']">
                        <strong>@{{ formErrors['frame_id'][0] }}</strong>
                    </span>
                  </div>
                  <hr>

                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Quantities
                      <label for="required" class="control-label" style="color:red;">*</label>
                    </label>
                    <input type="text" name="quantities" class="form-control" v-model="form.quantities" :class="{ 'is-invalid' : formErrors['quantities'] }" @input="getQuotation()">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['quantities']">
                        <strong>@{{ formErrors['quantities'][0] }}</strong>
                    </span>
                  </div>
                  <hr>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                      Delivery
                    </label>

                    <multiselect v-model="form.delivery_id" placeholder="Select Delivery" label="name" track-by="id" :options="deliveries" :internal-search="true" :custom-label="customLabel" @input="getQuotation()">
                      <template slot="option" slot-scope="props">
                          <span class="row col-md-12">
                            @{{props.option.name}}
                          </span>
                      </template>
                    </multiselect>
                    <input type="hidden" class="form-control" :class="{ 'is-invalid' : formErrors['delivery_fee'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['delivery_fee']">
                        <strong>@{{ formErrors['delivery_fee'][0] }}</strong>
                    </span>
                  </div>

                  <div class="form-group col-md-12 col-sm-12 col-xs-12 row pt-3 ml-1">
                    <h4>
                      Total:
                      <span>
                        RM @{{form.total.toFixed(2)}}
                      </span>
                    </h4>
                  </div>
                </div>
            </div>
        </div>
    </div>
    </template>