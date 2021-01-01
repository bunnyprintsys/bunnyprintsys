if (document.querySelector('#indexTransactionController')) {
    Vue.component('index-transaction', {
      template: '#index-transaction-template',
      data() {
        return {
          list: [],
          unit_options: [],
          statuses: [],
          search: {
            job_id: '',
            customer_name: '',
            customer_phone_number: '',
            status: ''
          },
          searching: false,
          sortkey: '',
          reverse: false,
          selected_page: '100',
          pagination: {
            total: 0,
            from: 1,
            per_page: 1,
            current_page: 1,
            last_page: 0,
            to: 5
          },
          formdata: {},
          clearform: {},
          loading: false,
          filterChanged: false,
          action: null
        }
      },
      mounted() {
        this.fetchTable()
        this.fetchStatusOptions()
      },
      methods: {
        fetchTable() {
          this.searching = true;
          let data = {
            // subject to change (search list and pagination)
            paginate: this.pagination.per_page,
            page: this.pagination.current_page,
            sortkey: this.sortkey,
            reverse: this.reverse,
            per_page: this.selected_page,
            name: this.search.name,
            phone_number: this.search.phone_number,
            email: this.search.email,
            status: this.search.status
          };
          axios.get(
            // subject to change (search list and pagination)
            '/api/transaction?page=' + data.page +
            '&per_page=' + data.per_page +
            '&sortkey=' + data.sortkey +
            '&reverse=' + data.reverse +
            '&name=' + data.name +
            '&phone_number=' + data.phone_number +
            '&email=' + data.email +
            '&status=' + data.status
          ).then((response) => {
            const result = response.data;
            if (result) {
              this.list = result.data;
              this.pagination = result.pagination;
            }
          });
          this.searching = false;
        },
        searchData() {
          this.pagination.current_page = 1;
          this.fetchTable();
          this.filterchanged = false;
        },
        sortBy(sortkey) {
          this.pagination.current_page = 1;
          this.reverse = (this.sortkey == sortkey) ? !this.reverse : false;
          this.sortkey = sortkey;
          this.fetchTable();
        },
        createSingleEntry() {
          this.clearform = {}
          this.formdata = {};
          this.action = 'create'
        },
        editSingleEntry(data) {
          this.clearform = {}
          this.formdata = {};
          this.formdata = {
            ...data
          }
          this.action = 'update'
          // console.log(JSON.parse(JSON.stringify(this.formdata)))
        },
        toggleUserStatus(data, status_code) {
          var approval = confirm('Are you sure to update the status ' + data.name + '?')
          if (approval) {
            axios.post('/api/user/' + data.user_id + '/status-toggle', {'status_code': status_code}).then((response) => {
              this.searchData();
            });
          } else {
            return false;
          }
        },
        onFilterChanged() {
          this.filterchanged = true;
        },
        fetchStatusOptions() {
          axios.get('/api/status/all').then((response) => {
            this.statuses = response.data.data
          })
        },
      },
      watch: {
        'selected_page'(val) {
          this.selected_page = val;
          this.pagination.current_page = 1;
          this.fetchTable();
        }
      }
    });

    Vue.component('form-transaction', {
      template: '#form-transaction-template',
      props: ['data', 'clearform', 'action'],
      data() {
        return {
          order_date: new Date(),
          dispatch_date: this.addDays(new Date(), 2),
          customers: [],
          states: [],
          countries: [],
          sales_channels: [],
          statuses: [],
          delivery_methods: [],
          materials: [],
          designers: [],
          paymentTermOptions: [],
          form: this.getFormDefault(),
          itemForm: this.getItemFormDefault(),
          transactionForm: this.getTransactionFormDefault(),
          customerForm: this.getCustomerFormDefault(),
          addressForm: this.getAddressFormDefault(),
          booleans: [],
          radioOption: {
            existingCustomer: 'true',
            isCompanyCustomer: 'false',
            createNewAddress: 'true',
            existingAddress: 'false',
          },
          formErrors: {},
          itemOptions: [],
          bindedMaterials: [],
          bindedShapes: [],
          bindedLaminations: [],
          bindedFrames: [],
          bindedFinishings: [],
          addresses: [],
          loading: false,
          show_add_item: true,
          flagConfirm: false,
        }
      },
      mounted() {
        this.getCustomerOptions()
        this.getStateOptions()
        this.getCountryOptions()
        this.getProductOptions()
        this.getSalesChannelOptions()
        this.getStatusOptions()
        this.setBooleanOptions()
        this.getDeliveryMethodOptions()
        this.getMaterialOptions()
        this.getDesignerOptions()
        this.getPaymentTermOptions()
      },
      methods: {
        onSubmit() {
          this.transactionForm.order_date = moment(this.transactionForm.order_date).format('YYYY-MM-DD')
          this.transactionForm.dispatch_date = moment(this.transactionForm.dispatch_date).format('YYYY-MM-DD')
          if(this.action === 'create') {
            axios.post('/api/transaction/create', {
              transaction_form: this.transactionForm,
              customer_form: this.customerForm,
              address_form: this.addressForm,
              item_form: this.itemForm
            })
            .then((response) => {
              $('.modal').modal('hide');
              this.getFormDefault()
              this.$emit('updatetable')
              flash('Entry has successfully created', 'success');
            })
              .catch((error) => {
                flash(_.get(error, 'response.data.message', 'Something goes wrong, please try again later.'), 'danger');
                this.formErrors = _.get(error, 'response.data.errors', {});
            });
          }else if(this.action === 'update') {
              this.itemForm.items = this.itemForm.items.map((item) => {
                  return {
                      ...item,
                      item_id: _.get(item, 'item.id', null),
                      material_id: _.get(item, 'material.id', null),
                      shape_id: _.get(item, 'shape.id', null),
                      lamination_id: _.get(item, 'lamination.id', null),
                      frame_id: _.get(item, 'frame.id', null),
                      finishing_id: _.get(item, 'finishing.id', null),
                  }
              });

              axios.post('/api/transaction/update/' + this.transactionForm.id, {
                transaction_form: this.transactionForm,
                customer_form: this.customerForm,
                address_form: this.addressForm,
                item_form: this.itemForm
              })
              .then((response) => {
                $('.modal').modal('hide');
                this.getFormDefault()
                this.$emit('updatetable')
                flash('Entry has successfully updated', 'success');
              })
                .catch((error) => {
                  flash(_.get(error, 'response.data.message', 'Something goes wrong, please try again later.'), 'danger');
                  this.formErrors = _.get(error, 'response.data.errors', {});
              });
          }
        },
        getCustomerOptions() {
          axios.get('/api/customer').then((response) => {
            this.customers = response.data.data
          })
        },
        getStateOptions() {
          axios.get('/api/state/country/1').then((response) => {
            this.states = response.data.data
          })
        },
        getCountryOptions() {
          axios.get('/api/country/all').then((response) => {
            this.countries = response.data.data
            // console.log(JSON.parse(JSON.stringify(this.countries)))
            this.form.phone_country_id = this.countries[0]
          })
        },
        onDateChanged(modelName) {
          this.form[modelName] = moment(this[modelName]).format('YYYY-MM-DD')
        },
        dateFormatter(date) {
          return moment(date).format('YYYY-MM-DD');
        },
        onOptionChosen() {
          // console.log(this.name)
        },
        getProductOptions() {
            axios.get('/api/product/all')
            .then((response) => {
                return response.data;
            })
            .catch((error) => {
                flash('Something wrong...', 'danger');
                return error;
            })
            .then((result) => {
                this.itemOptions = result.data;
                // console.log(JSON.parse(JSON.stringify(this.itemOptions)))
            });
        },
        getSalesChannelOptions() {
          axios.get('/api/sales-channel/all').then((response) => {
            this.sales_channels = response.data.data
          })
        },
        getStatusOptions() {
          axios.get('/api/status/all').then((response) => {
            this.statuses = response.data.data
          })
        },
        getDesignerOptions() {
          axios.get('/api/admin').then((response) => {
            this.designers = response.data.data
            // console.log(JSON.parse(JSON.stringify(this.designers)))
          })
        },
        getDeliveryMethodOptions() {
          axios.get('/api/delivery-method/all').then((response) => {
            this.delivery_methods = response.data.data
          })
        },
        getPaymentTermOptions() {
          axios.get('/api/payment-term/all').then((response) => {
            this.paymentTermOptions = response.data.data
          })
        },
        getAddressesOptions(customer_id) {
          axios.post('/api/addresses/customer/' + customer_id).then((response) => {
            this.addresses = response.data.data
          })
        },
        setBooleanOptions() {
          this.booleans = [
            {id: 1, name: 'Yes'},
            {id: 0, name: 'No'}
          ]
        },
        getMaterialOptions() {
          axios.post('/api/materials/all').then((response) => {
            this.materials = response.data.data
            // console.log(JSON.parse(JSON.stringify(this.materials)))
          })
        },
        calculateAmount() {
          if(!isNaN(this.itemForm.qty) && !isNaN(this.itemForm.price)) {
            this.itemForm.amount = (this.itemForm.qty * this.itemForm.price).toFixed(2)
          }
        },
        calculatePrice() {
          if(!isNaN(this.itemForm.qty) && !isNaN(this.itemForm.amount)) {
            this.itemForm.price = (this.itemForm.amount / this.itemForm.qty).toFixed(3)
          }
        },
        addItem() {
            this.formErrors = {};
            if (_.isEmpty(this.itemForm.items)) {
                this.itemForm.items = [];
            }
            // console.log(JSON.parse(JSON.stringify(this.materials)));
            const itemObj = this.itemOptions.find(x => Number(x.id) === Number(this.itemForm.product.id));
            const materialObj = this.bindedMaterials.find(x => Number(x.id) === Number(this.itemForm.material.id));
            const shapeObj = this.bindedShapes.find(x => Number(x.id) === Number(this.itemForm.shape.id));
            const laminationObj = this.bindedLaminations.find(x => Number(x.id) === Number(this.itemForm.lamination.id));
            const frameObj = this.bindedFrames.find(x => Number(x.id) === Number(this.itemForm.frame.id));
            const finishingObj = this.bindedFinishings.find(x => Number(x.id) === Number(this.itemForm.finishing.id));

            this.itemForm.items.push({
              item: itemObj,
              item_id: itemObj.id,
              material: materialObj,
              material_id: materialObj ? materialObj.id : null,
              shape: shapeObj,
              shape_id: shapeObj ? shapeObj.id : null,
              lamination: laminationObj,
              lamination_id: laminationObj ? laminationObj.id : null,
              frame: frameObj,
              frame_id: frameObj ? frameObj.id : null,
              finishing: finishingObj,
              finishing_id: finishingObj ? finishingObj.id : null,
              description: this.itemForm.description,
              qty: this.itemForm.qty,
              price: parseFloat(this.itemForm.price).toFixed(2),
              amount: this.itemForm.amount
            });
            // console.log(JSON.parse(JSON.stringify(this.itemForm.items)));
            this.item = this.getItemFormDefault();
            this.total = this.itemForm.items.reduce(function (total, item) {
                return total + parseFloat(item.amount);
            }, 0).toFixed(2);
        },
        onPhoneNumberEntered: _.debounce(function(e) {
          this.formErrors = []
          // console.log(JSON.parse(JSON.stringify(this.form)))
          axios.post('/api/registration/validate/phonenumber', this.form)
          .then((response) => {
            this.form.phone_number_format_valid = true
          })
          .catch((error) => {
            this.formErrors = error.response.data.errors
            this.form.phone_number_format_valid = false
          })
          .finally(() => {
          });
        }, 800),
        getTransactionFormDefault() {
          return {
            id: '',
            order_date: moment(new Date()).format('YYYY-MM-DD'),
            dispatch_date: moment(this.addDays(new Date(), 2)).format('YYYY-MM-DD'),
            sales_channel: '',
            invoice_number: '',
            tracking_number: '',
            delivery_method: '',
            designed_by: '',
            status: '',
            items: [],
          }
        },
        getCustomerFormDefault() {
          return {
            name: '',
            company_name: '',
            roc: '',
            phone_country: '',
            phone_number: '',
            email: '',
            is_company: 'false',
            payment_term_id: '',
          }
        },
        getAddressFormDefault() {
          return {
            unit: '',
            block: '',
            building_name: '',
            road_name: '',
            area: '',
            postcode: '',
            state: '',
            country: '',
            status: '',
            items: [],
            address: '',
            addresses: ''
          }
        },
        getItemFormDefault() {
          return {
            product: null,
            material: null,
            shape: null,
            lamination: null,
            frame: null,
            finishing: null,
            product_id: '',
            material_id: '',
            shape_id: '',
            lamination_id: '',
            frame_id: '',
            finishing_id: '',
            description: '',
            qty: 1,
            price: '',
            amount: ''
        }
        },
        getFormDefault() {
          return {
            id: '',
            order_date: moment(new Date()).format('YYYY-MM-DD'),
            dispatch_date: moment(this.addDays(new Date(), 2)).format('YYYY-MM-DD'),
            name: '',
            company_name: '',
            roc: '',
            phone_country: '',
            phone_number: '',
            email: '',
            is_company: 'false',
            sales_channel: '',
            invoice_number: '',
            tracking_number: '',
            delivery_method: '',
            unit: '',
            block: '',
            building_name: '',
            road_name: '',
            postcode: '',
            state: '',
            country: '',
            status: '',
            items: [],
            address: '',
            addresses: ''
          }
        },
        onExistingCustomerChosen(customer) {
          axios.post('/api/customer/address', customer).then((response) => {
            this.customerForm.addresses = response.data.data
            if(this.customerForm.addresses.length > 0) {
              this.radioOption.existingAddress = 'true'
            }else {
              this.radioOption.existingAddress = 'false'
            }
          })
        },
        onProductSelected() {
          if(this.itemForm.product) {
            // console.log(JSON.parse(JSON.stringify(this.itemForm.product)))
            this.getBindedMaterials(this.itemForm.product.id)
            this.getBindedShapes(this.itemForm.product.id)
            this.getBindedLaminations(this.itemForm.product.id)
            this.getBindedFrames(this.itemForm.product.id)
            this.getBindedFinishings(this.itemForm.product.id)
          }
        },
        getBindedMaterials(product_id) {
          axios.post('/api/materials/binded/product/' + product_id).then((response) => {
            this.bindedMaterials = response.data.data
          })
        },
        getBindedShapes(product_id) {
          axios.post('/api/shapes/binded/product/' + product_id).then((response) => {
            this.bindedShapes = response.data.data
          })
        },
        getBindedLaminations(product_id) {
          axios.post('/api/laminations/binded/product/' + product_id).then((response) => {
            this.bindedLaminations = response.data.data
          })
        },
        getBindedFrames(product_id) {
          axios.post('/api/frames/binded/product/' + product_id).then((response) => {
            this.bindedFrames = response.data.data
          })
        },
        getBindedFinishings(product_id) {
          axios.post('/api/finishings/binded/product/' + product_id).then((response) => {
            this.bindedFinishings = response.data.data
          })
        },
        customLabelCustomer(option) {
          return `${option.optionName}`
        },
        customLabelName(option) {
          return `${option.name}`
        },
        customLabelCountriesOption(option) {
          return `${option.symbol} (+${option.code})`
        },
        customLabelFullAddress(option) {
          return `${option.full_address}`
        },
        customMaterialLabelName(option) {
          return `${option.material.name}`
        },
        addDays(date, days) {
          var result = new Date(date);
          result.setDate(result.getDate() + days);
          return result;
        },
        removeItem(index) {
          this.itemForm.items.splice(index, 1);
          this.total = this.itemForm.items.reduce(function (total, item) {
              return total + parseFloat(item.amount);
          }, 0).toFixed(2);
        },

        resetObject(radioOption) {
/*
          switch(radioOption) {
            case 'existingCustomer':
              console.log('e_1');
              this.form.map(obj => this.getCustomerFormDefault().find(o => o.id === obj.id) || obj);
              this.form = this.getCustomerFormDefault()
              break;
          } */
        },
      },

      watch: {
/*
        'data'(val) {
          for (var key in this.form) {
            this.form[key] = this.data[key];
          }
        }, */
        'data' (val) {
          if(this.action === 'update') {
            this.form = _.clone(this.data);
            this.transactionForm = this.form
            this.customerForm = this.form
            this.addressForm = this.form
            this.itemForm = this.form
            // console.log(JSON.parse(JSON.stringify(this.itemForm)))
          }

          if(this.action === 'create') {
            this.form = this.getFormDefault()
            this.itemForm = this.getItemFormDefault()
            this.transactionForm = this.getTransactionFormDefault()
            this.customerForm = this.getCustomerFormDefault()
            this.addressForm = this.getAddressFormDefault()
          }

          this.formErrors = [];
          if(this.addressForm.address) {
            this.radioOption.existingAddress = 'true'
          }
        },
        'clearform'(val) {
          if (val) {
            this.formErrors = val
          }
        },
        'action'() {
            this.show_add_item = this.action === 'create';
            this.radioOption.existingAddress = this.action == 'update' ? 'true' : 'false';
        }
      }
    });

    new Vue({
      el: '#indexTransactionController',
    });
  }
