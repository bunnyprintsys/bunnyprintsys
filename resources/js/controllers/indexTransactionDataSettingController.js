if(document.querySelector('#indexTransactionDataSettingController')) {
  Vue.component('sales-channel', {
    template: '#sales-channel-template',
    data() {
      return {
        sales_channels: []
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
      removeItem(index) {
        axios.delete('/api/sales-channel/' + this.sales_channels[index]['id']).then((response) => {
          this.sales_channels.splice(index, 1);
        })
      },
    }
  });

  new Vue({
    el: '#indexTransactionDataSettingController',
  });
}