if (document.querySelector('#indexVoucherController')) {
    Vue.component('index-voucher', {
      template: '#index-voucher-template',
      data() {
        return {
          list: [],
          unit_options: [],
          search: {
            name: '',
            valid_from: moment().startOf('month').format('YYYY-MM-DD'),
            valid_to: moment().endOf('month').format('YYYY-MM-DD'),
            is_active: '',
            is_percentage: '',
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
            valid_from: this.search.valid_from,
            valid_to: this.search.valid_to,
            is_active: this.search.is_active,
            is_percentage: this.search.is_percentage,
          };
          axios.get(
            // subject to change (search list and pagination)
            '/api/voucher?page=' + data.page +
            '&per_page=' + data.per_page +
            '&sortkey=' + data.sortkey +
            '&reverse=' + data.reverse +
            '&name=' + data.name +
            '&valid_from=' + data.valid_from +
            '&valid_to=' + data.valid_to +
            '&is_active=' + data.is_active +
            '&is_percentage=' + data.is_percentage
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
        createSingleVoucher() {
          this.clearform = {}
          this.formdata = '';
        },
        editSingleVoucher(data) {
          this.clearform = {}
          this.formdata = '';
          this.formdata = {
              ...data,
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

    Vue.component('form-voucher', {
      template: '#form-voucher-template',
      props: ['data', 'clearform'],
      data() {
        return {
          form: {
            id: '',
            name: '',
            desc: '',
            is_active: '',
            is_unique_customer: '',
            is_percentage: '',
            value: '',
            is_count_limit: '',
            count_limit: ''
          },
          formErrors: {}
        }
      },
      methods: {
        onSubmit() {
          axios.post('/api/voucher/store-update', this.form)
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
      el: '#indexVoucherController',
    });
  }
