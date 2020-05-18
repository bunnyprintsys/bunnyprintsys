if (document.querySelector('#indexTransactionController')) {
    Vue.component('index-transaction', {
      template: '#index-transaction-template',
      data() {
        return {
          list: [],
          unit_options: [],
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
          filterchanged: false,
          clearform: {}
        }
      },
      mounted() {
        this.fetchTable();
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
        createSingleTransaction() {
          this.clearform = {}
          this.formdata = '';
        },
        editSingleTransaction(data) {
          this.clearform = {}
          this.formdata = '';
          this.formdata = {
              ...data,
          }
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
        }
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
      props: ['data', 'clearform'],
      data() {
        return {
          form: {
            id: '',
            name: '',
            company_name: '',
            roc: '',
            phone_number: '',
            email: '',
            is_company: 'false'
          },
          formErrors: {}
        }
      },
      methods: {
        onSubmit() {
          axios.post('/api/member/store-update', this.form)
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
      el: '#indexTransactionController',
    });
  }
