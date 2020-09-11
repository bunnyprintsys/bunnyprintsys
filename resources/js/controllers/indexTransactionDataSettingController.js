if(document.querySelector('#indexTransactionDataSettingController')) {
  Vue.component('sales-channel', {
    template: '#sales-channel-template',
    data() {
      return {
        sales_channels: [],
        form: this.getFormDefault(),
        action: 'create',
        formErrors: []
      }
  },
    mounted() {
      this.getAllSalesChannel()
    },
    methods: {
      getAllSalesChannel() {
        axios.get('/api/sales-channel/all').then((response) => {
          this.sales_channels = response.data.data
        })
      },
      createSingleEntry() {
        this.form = this.getFormDefault()
        this.action = 'create'
      },
      onSubmit() {
        axios.post('/api/sales-channel', this.form).then((response) => {
          this.getAllSalesChannel()
          $('.modal').modal('hide')
          this.$emit('updatetable')
          this.formErrors = []
        })
      },
      removeSingleEntry(index) {
        axios.delete('/api/sales-channel/' + this.sales_channels[index]['id']).then((response) => {
          this.sales_channels.splice(index, 1)
        })
      },
      getFormDefault() {
        return {
          name: '',
          desc: ''
        }
      }
    }
  });

  Vue.component('status', {
    template: '#status-template',
    data() {
      return {
        statuses: [],
        form: this.getFormDefault(),
        action: 'create',
        formErrors: []
      }
  },
    mounted() {
      this.getAllStatus()
    },
    methods: {
      getAllStatus() {
        axios.get('/api/status/all').then((response) => {
          this.statuses = response.data.data
        })
      },
      createSingleEntry() {
        this.form = this.getFormDefault()
        this.action = 'create'
      },
      onSubmit() {
        axios.post('/api/status', this.form).then((response) => {
          this.getAllStatus()
          $('.modal').modal('hide')
          this.$emit('updatetable')
          this.formErrors = []
        })
      },
      removeSingleEntry(index) {
        axios.delete('/api/status/' + this.statuses[index]['id']).then((response) => {
          this.statuses.splice(index, 1)
        })
      },
      getFormDefault() {
        return {
          name: '',
          desc: ''
        }
      }
    }
  });

  Vue.component('delivery-method', {
    template: '#delivery-method-template',
    data() {
      return {
        delivery_methods: [],
        form: this.getFormDefault(),
        action: 'create',
        formErrors: []
      }
  },
    mounted() {
      this.getAllDeliveryMethod()
    },
    methods: {
      getAllDeliveryMethod() {
        axios.get('/api/delivery-method/all').then((response) => {
          this.delivery_methods = response.data.data
        })
      },
      createSingleEntry() {
        this.form = this.getFormDefault()
        this.action = 'create'
      },
      onSubmit() {
        axios.post('/api/delivery-method', this.form).then((response) => {
          this.getAllDeliveryMethod()
          $('.modal').modal('hide')
          this.$emit('updatetable')
          this.formErrors = []
        })
      },
      removeSingleEntry(index) {
        axios.delete('/api/delivery-method/' + this.delivery_methods[index]['id']).then((response) => {
          this.delivery_methods.splice(index, 1)
        })
      },
      getFormDefault() {
        return {
          name: '',
          desc: ''
        }
      }
    }
  });

  new Vue({
    el: '#indexTransactionDataSettingController',
  });

}