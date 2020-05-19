<template>
  <input :id="id" type="text" class="form-control" :class="_class">
</template>

<script>
  export default {
    props: ['options', 'value', 'id', 'format', '_class'],
    mounted: function () {
      const self = this;
      const format = this.format ? this.format : 'yyyy-mm-dd';
      const options = {
          format: format,
          weekStart: 1,
          autoclose: true,
          clearBtn: true,
          todayHighlight: true,
          orientation: 'bottom auto',
          keepInvalid: true,
          ...this.options,
      };

      $('#' + this.id).datepicker(options)
        .on('changeDate', function (e) {
          self.$emit('input', this.value)
        });
        if (this.value) {
            $(this.$el).datepicker('setDate', this.value);
        }
    },
      watch: {
          'value': function (newValue, oldValue) {
              if (newValue !== oldValue) {
                  if (newValue) {
                    $(`#${this.id}`).datepicker('setDate', newValue);
                  } else {
                    $(`#${this.id}`).datepicker('setDate', null);
                  }
              }
          }
      },
  }
</script>
