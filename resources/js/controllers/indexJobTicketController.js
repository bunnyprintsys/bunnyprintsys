if (document.querySelector('#indexJobTicketController')) {
  Vue.component('index-job-ticket', {
    template: '#index-job-ticket-template',
    data() {
      return {
        list: [],
        unit_options: [],
        statuses: [],
        excelHistories: [],
        search: {
          code: '',
          doc_no: '',
          status: '',
          date_from: '',
          date_to: ''
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
        filterchanged: false,
        clearform: {},
        action: '',
        excelFile: '',
        is_file_selected: false,
        uploading: false,
        fileData: new FormData()
      }
    },
    mounted() {
      this.fetchTable();
      this.getStatusOptions();
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
          code: this.search.code,
          doc_no: this.search.doc_no,
          date_from: this.search.date_from,
          date_to: this.search.date_to,
          status: this.search.status
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/job-tickets?page=' + data.page +
          '&per_page=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&code=' + data.code +
          '&doc_no=' + data.doc_no +
          '&date_from=' + data.date_from +
          '&date_to=' + data.date_to +
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
        this.formdata = {}
        this.action = 'create'
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = '';
        this.formdata = {
          ...data,
        }
        this.action = 'update'
      },
      toggleUserStatus(data, status_code) {
        var approval = confirm('Are you sure to update the status ' + data.name + '?')
        if (approval) {
          axios.post('/api/user/' + data.user_id + '/status-toggle', { 'status_code': status_code }).then((response) => {
            this.searchData();
          });
        } else {
          return false;
        }
      },
      onFilterChanged() {
        this.filterchanged = true;
      },
      getStatusOptions() {
        axios.get('/api/status/all').then((response) => {
          this.statuses = response.data.data
        })
      },
      onDateChanged(modelName) {
        this.search[modelName] = moment(this[modelName]).format('YYYY-MM-DD')
      },
      getFiveLatestExcel() {
        axios.get('/api/job-tickets/last-five-excel').then((response) => {
          this.excelHistories = response.data
        })
      },
      onExcelModalClicked() {
        this.getFiveLatestExcel()
      },
      onFilesChosen(e) {
        this.fileData = new FormData();
        _.forEach(e.target.files, (file, index) => {
          this.fileData.append('files[' + index + ']', file);
        });
        this.is_file_selected = true
      },
      onFilesUpload(type) {
        this.uploading = true;
        const that = this;
        axios.post('/api/job-tickets/upload-excel', this.fileData)
          .then((response) => {
            this.fetchTable();
            flash('Excel has successfully imported', 'success');
          })
          .catch((e) => {
            flash(e.response.data.message || 'Something wrong...', 'danger');
            return e;
          })
          .then((e) => {
            this.getFiveLatestExcel()
            that.uploading = false;
            that.is_file_selected = false;
            that.$refs.files.value = '';
          });
      },
      closeModal(modelId) {
        let model = '#' + modelId
        $(model).modal('hide');
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

  Vue.component('form-job-ticket', {
    template: '#form-job-ticket-template',
    props: ['data', 'clearform', 'action'],
    data() {
      return {
        form: this.getFormDefault(),
        formErrors: {},
        radioOption: {
          existingCustomer: 'true',
          existingProduct: 'true'
        },
        customers: [],
        products: [],
        statuses: [],
        deliveryMethods: []
      }
    },
    mounted() {
      this.getCustomerOptions()
      this.getDeliveryMethodOptions()
      this.getProductOptions()
      this.fetchStatusOptions()
    },
    methods: {
      onSubmit() {
        if (this.action === 'create') {
          axios.post('/api/job-tickets/', this.form)
            .then((response) => {
              $('.modal').modal('hide');
              for (var key in this.form) {
                this.form[key] = null;
              }
              this.$emit('updatetable')
              flash('Entry has successfully created or updated', 'success');
            })
            .catch((error) => {
              this.formErrors = error.response.data.errors
            });
        } else if (this.action === 'update') {
          axios.post('/api/job-tickets/update/' + this.form.id, this.form)
            .then((response) => {
              $('.modal').modal('hide');
              for (var key in this.form) {
                this.form[key] = null;
              }
              this.$emit('updatetable')
              flash('Entry has successfully created or updated', 'success');
            })
            .catch((error) => {
              this.formErrors = error.response.data.errors
            });
        }
      },
      closeModal(modelId) {
        let model = '#' + modelId
        $(model).modal('hide');
      },
      getFormDefault() {
        return {
          id: '',
          address: '',
          code: '',
          delivery_method: '',
          delivery_remarks: '',
          doc_no: '',
          doc_date: '',
          customer: '',
          customer_code: '',
          customer_name: '',
          product: '',
          product_code: '',
          product_name: '',
          status: '',
          remarks: '',
          qty: '',
          uom: '',
          url_link: '',
        }
      },
      getDeliveryMethodOptions() {
        axios.get('/api/delivery-method/all')
          .then((response) => {
            return response.data;
          })
          .catch((error) => {
            flash('Something wrong...', 'danger');
            return error;
          })
          .then((result) => {
            this.deliveryMethods = result.data;
            // console.log(JSON.parse(JSON.stringify(this.itemOptions)))
          });
      },
      getCustomerOptions() {
        axios.get('/api/customer')
          .then((response) => {
            return response.data;
          })
          .catch((error) => {
            flash('Something wrong...', 'danger');
            return error;
          })
          .then((result) => {
            this.customers = result.data;
            // console.log(JSON.parse(JSON.stringify(this.itemOptions)))
          });
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
            this.products = result.data;
            // console.log(JSON.parse(JSON.stringify(this.itemOptions)))
          });
      },
      fetchStatusOptions() {
        axios.get('/api/status/all').then((response) => {
          this.statuses = response.data.data
        })
      },
      customLabelCodeName(option) {
        return `${option.code} - ${option.name}`
      },
      customLabelName(option) {
        return `${option.name}`
      },
      onDateChanged(modelName) {
        this.form[modelName] = moment(this.form[modelName]).format('YYYY-MM-DD')
      },
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
      },
      'clearform'(val) {
        if (val) {
          this.formErrors = val
        }
      }
    }
  });

  new Vue({
    el: '#indexJobTicketController',
  });
}
