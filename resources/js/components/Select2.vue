<template>
    <select ref='select'>
        <slot></slot>
    </select>
</template>

<script>
  export default {
    props: ['options', 'value'],
    mounted: function () {
      var vm = this;
      $(this.$refs.select)
        .select2({
          data: this.options,
          placeholder: 'Select..',
          allowClear: true
        })
        .on('change', function (ev, args) {
            if (!(args && "ignore" in args)) {
                vm.$emit('input', $(this).val())
            }
        });

      Vue.nextTick(() => {
        $(this.$refs.select)
            .val(this.value)
            .trigger('change', { ignore: true })
      });
    },
    watch: {
      value: function (value, oldValue) {
        // update value
        $(this.$refs.select)
            .val(this.value)
            .trigger('change', { ignore: true });
      },
      options: function (options) {
        // update options
        $(this.$refs.select).select2({ data: options })
      }
    },
    destroyed: function () {
      $(this.$refs.select).off().select2('destroy')
    }
  }
</script>

<style scoped>
  select {
    min-width: 300px;
  }
</style>