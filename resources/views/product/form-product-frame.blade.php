<div class="form-group">
    <div class="section-title text-center">Frame</div>
    <div class="form-row">
      <div class="col-md-11 col-sm-11 col-xs-12">
        <select2-must class="form-group" name="frame" v-model="form.frame_id">
            <option value="">Nope</option>
            <option v-for="frame in nonBindedFrames" v-bind:key="frame.id" :value="frame.id">
                @{{frame.name}}
            </option>
        </select2-must>
      </div>
      <div class="col-md-1 col-sm-1 col-xs-12">
        <button class="btn btn-md btn-success" @click.prevent="addProductBindingEntry('frame')">
          <i class="fas fa-plus-circle"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="table-responsive">
      <table class="table table-bordered table-sm">
          <tr class="table-primary">
              <th class="text-center" colspan="12">
                Binded Frame(s)
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
              </th>
          </tr>
          <tr v-for="(data, index) in bindedFrames" v-bind:key="data.id" class="row_edit">
              <td class="text-center">
                  @{{ index + 1}}
              </td>
              <td class="text-left">
                  @{{ data.name ? data.name : '' }}
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" @click.prevent="removeProductBinding('frame', data)">
                  <i class="fas fa-times"></i>
              </button>
              </td>
          </tr>
          <tr v-if="bindedFrames.length === 0">
              <td colspan="18" class="text-center"> No Results Found </td>
          </tr>
      </table>
  </div>