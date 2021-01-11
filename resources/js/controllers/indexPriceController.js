if(document.querySelector('#indexPriceController')) {

  Vue.component('label-shape', {
    template: '#shape-template',
    data() {
      return {
        shapes: [],
        form: this.getFormDefault(),
        action: 'create',
        formErrors: [],
        formOptions: this.getFormOptionsDefault(),
        formUrl: ''
      }
  },
    mounted() {
      this.getAllShapes()
    },
    methods: {
      getAllShapes() {
        axios.post('/api/shapes/all', {product_id: 1}).then((response) => {
          this.shapes = response.data
        })
      },
      createSingleEntry(type) {
        this.formOptions = this.getFormOptionsDefault()
        this.formUrl = ''
        switch(type) {
          case 'shape':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/shapes/create/product/1'
            break;
          case 'material':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/shapes/create/product/1'
            break;
        }

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
      },
      getFormOptionsDefault() {
        return {
          name: false,
          multiplier: false,
          min: false,
          max: false,
          qty: false
        }
      }
    }
  });

  Vue.component('price-labelsticker', {
    template: '#price-labelsticker-template',
    props: [
      'type'
    ],
    data() {
      return {
          laminations: [],
          materials: [],
          orderquantities: [],
          shapes: [],
          deliveries: [],
          quantitymultipliers: [],
          form: this.getFormDefault(),
          formOptions: this.getFormOptionsDefault(),
          formUrl: '',
          formTitle: '',
          formErrors: []
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
        axios.post('/api/laminations/product-lamination/all', {product_id: 1, type: this.type}).then((response) => {
          this.laminations = response.data.data
        })
      },
      getAllMaterials() {
        axios.post('/api/materials/product-material/all', {product_id: 1, type: this.type}).then((response) => {
          this.materials = response.data.data
        })
      },
      getAllOrderquantities() {
        axios.post('/api/orderquantities/all', {}).then((response) => {
          this.orderquantities = response.data.data
        })
      },
      getAllShapes() {
        axios.post('/api/shapes/product-shape/all', {product_id: 1, type: this.type}).then((response) => {
          this.shapes = response.data.data
        })
      },
      getAllDeliveries() {
        axios.post('/api/deliveries/product-delivery/all', {product_id: 1, type: this.type}).then((response) => {
          this.deliveries = response.data.data
        })
      },
      getQuantitymultipliers() {
        axios.post('/api/quantitymultipliers/all', {product_id: 1, type: this.type}).then((response) => {
          this.quantitymultipliers = response.data.data
        })
      },
      onProductLaminationMultiplierChanged(id, value) {
          axios.post('/api/laminations/product-lamination/edit', {id: id, value: value, type: this.type}).then((response) => {
          })
      },
      onProductShapeMultiplierChanged(id, value) {
          axios.post('/api/shapes/product-shape/edit', {id: id, value: value, type: this.type}).then((response) => {
          })
      },
      onProductMaterialMultiplierChanged(id, value) {
          axios.post('/api/materials/product-material/edit', {id: id, value: value, type: this.type}).then((response) => {
          })
      },
      onDeliveryMultiplierChanged(id, value) {
          axios.post('/api/deliveries/product-delivery/edit', {id: id, value: value, type: this.type}).then((response) => {
          })
      },
      onQtyNameChanged(id, name) {
          axios.post('/api/orderquantities/edit', {id: id, name: name}).then((response) => {
          })
      },
      onQtyChanged(id, qty) {
          axios.post('/api/orderquantities/edit', {id: id, qty: qty}).then((response) => {
          })
      },
      onQtyMultiplierChanged(data) {
        axios.post('/api/quantitymultipliers/edit', {data: {...data, value: data.multiplier, type: this.type}}).then((response) => {
        })
      },
      getFormOptionsDefault() {
        return {
          name: false,
          multiplier: false,
          min: false,
          max: false,
          qty: false
        }
      },
      getFormDefault() {
        return {
          product_id: 1,
          name: '',
          multiplier: '',
          min: '',
          max: '',
          qty: ''
        }
      },
      createSingleEntry(type) {
        this.formOptions = this.getFormOptionsDefault()
        this.form = this.getFormDefault()
        this.formUrl = ''
        this.formTitle = type
        switch(type) {
          case 'shape':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/shapes/product-shape/create'
            break;
          case 'material':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/materials/product-material/create'
            break;
          case 'lamination':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/laminations/product-lamination/create'
            break;
          case 'deliveries':
            this.formOptions.name = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/deliveries/product-delivery/create'
            break;
          case 'quantitymultipliers':
            this.formOptions.min = true
            this.formOptions.max = true
            this.formOptions.multiplier = true
            this.formUrl = '/api/quantitymultipliers/create'
            break;
          case 'orderquantities':
            this.formOptions.name = true
            this.formOptions.qty = true
            this.formUrl = '/api/orderquantities/create'
            break;
        }
        this.action = 'create'
      },
      onSubmit() {
        axios.post(this.formUrl, {...this.form, value: this.form.multiplier, type: this.type}).then((response) => {
          this.getAllLaminations()
          this.getAllMaterials()
          this.getAllOrderquantities()
          this.getQuantitymultipliers()
          this.getAllShapes()
          this.getAllDeliveries()
          $('.modal').modal('hide')
          this.formErrors = []
        })
      },
    }
  });

    new Vue({
      el: '#indexPriceController',
    });
  }