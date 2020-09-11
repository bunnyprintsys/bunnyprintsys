if(document.querySelector('#indexPriceController')) {

  Vue.component('label-shape', {
    template: '#shape-template',
    data() {
      return {
        shapes: [],
        form: this.getFormDefault(),
        action: 'create',
        formErrors: []
      }
  },
    mounted() {
      this.getAllShapes()
    },
    methods: {
      getAllShapes() {
        axios.get('/api/shapes/product/1').then((response) => {
          this.shapes = response.data
        })
      },
      createSingleEntry() {
        this.form = this.getFormDefault()
        this.action = 'create'
      },
      onSubmit() {
        axios.post('/api/shape', this.form).then((response) => {
          this.getAllSalesChannel()
          $('.modal').modal('hide')
          this.$emit('updatetable')
          this.formErrors = []
        })
      },
      removeSingleEntry(index) {
        axios.delete('/api/shape/' + this.shapes[index]['id']).then((response) => {
          this.shapes.splice(index, 1)
        })
      },
      getFormDefault() {
        return {
          name: '',
          multiplier: ''
        }
      }
    }
  });

  Vue.component('price-labelsticker', {
    template: '#price-labelsticker-template',
    data() {
      return {
          laminations: [],
          materials: [],
          orderquantities: [],
          shapes: [],
          deliveries: [],
          quantitymultipliers: []
      }
  },
    mounted() {
      this.getAllLaminations()
      this.getAllMaterials()
      this.getAllOrderquantities()
      this.getQuantitymultipliers()
      this.getAllShapes()
      this.getAllDeliveries()
    },
    methods: {
      getAllLaminations() {
        axios.get('/api/laminations/product/1').then((response) => {
          this.laminations = response.data
        })
      },
      getAllMaterials() {
        axios.get('/api/materials/product/1').then((response) => {
          this.materials = response.data
        })
      },
      getAllOrderquantities() {
        axios.get('/api/orderquantities/all').then((response) => {
          this.orderquantities = response.data
        })
      },
      getAllShapes() {
        axios.get('/api/shapes/product/1').then((response) => {
          this.shapes = response.data
        })
      },
      getAllDeliveries() {
        axios.get('/api/deliveries/product/1').then((response) => {
          this.deliveries = response.data
        })
      },
      getQuantitymultipliers() {
        axios.get('/api/quantitymultipliers/product/1').then((response) => {
          this.quantitymultipliers = response.data
        })
      },
      onProductLaminationMultiplierChanged(id, value) {
          axios.post('/api/laminations/' + id, {multiplier: value}).then((response) => {
          })
      },
      onProductShapeMultiplierChanged(id, value) {
          axios.post('/api/shapes/' + id, {multiplier: value}).then((response) => {
          })
      },
      onProductMaterialMultiplierChanged(id, value) {
          axios.post('/api/materials/' + id, {multiplier: value}).then((response) => {
          })
      },
      onDeliveryMultiplierChanged(id, value) {
          axios.post('/api/deliveries/' + id, {multiplier: value}).then((response) => {
          })
      },
      onQtyNameChanged(id, name) {
          axios.post('/api/orderquantities/' + id, {name: name}).then((response) => {
          })
      },
      onQtyChanged(id, qty) {
          axios.post('/api/orderquantities/' + id, {qty: qty}).then((response) => {
          })
      },
      onQtyMultiplierChanged(data) {
        axios.post('/api/quantitymultipliers/' + data.id, data).then((response) => {
        })
      }
    }
  });

    new Vue({
      el: '#indexPriceController',
    });
  }