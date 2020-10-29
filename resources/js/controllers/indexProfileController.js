if (document.querySelector('#indexProfileController')) {
    Vue.component('index-profile', {
      template: '#index-profile-template',
      data() {
        return {
          list: [],
          unit_options: [],
          search: {
            company_name: '',
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
            name: this.search.company_name
          };
          axios.get(
            // subject to change (search list and pagination)
            '/api/profile?page=' + data.page +
            '&per_page=' + data.per_page +
            '&sortkey=' + data.sortkey +
            '&reverse=' + data.reverse +
            '&name=' + data.company_name
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
        createSingleProfile() {
          this.clearform = {}
          this.formdata = {}
        },
        editSingleProfile(data) {
          // console.log(JSON.parse(JSON.stringify(data)))
          this.clearform = {}
          this.formdata = {}
          this.formdata = {
              ...data
          }
          // console.log(JSON.parse(JSON.stringify(this.formdata)))
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

    Vue.component('form-profile', {
      template: '#form-profile-template',
      props: ['data', 'clearform'],
      data() {
        return {
          form: {
            id: '',
            address_id: '',
            user_id: '',
            company_name: '',
            roc: '',
            name: '',
            email: '',
            phone_number: '',
            alt_phone_number: '',
            unit: '',
            block: '',
            building_name: '',
            road_name: '',
            postcode: '',
            state_id: '',
            country_id: '',
            job_prefix: '',
            invoice_prefix: ''
          },
          formErrors: {},
          states: [],
          countries: []
        }
      },
      mounted() {
        this.getStateOptions()
        this.getCountryOptions()
      },
      methods: {
        onSubmit() {
          axios.post('/api/profile/store-update', this.form)
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
        getStateOptions() {
          axios.get('/api/state/country/1').then((response) => {
            this.states = response.data.data
          })
        },
        getCountryOptions() {
          axios.get('/api/country/all').then((response) => {
            this.countries = response.data.data
          })
        },
      },
      watch: {
        'data'(val) {
          // this.form = _.clone(this.data);
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
      el: '#indexProfileController',
    });
  }
