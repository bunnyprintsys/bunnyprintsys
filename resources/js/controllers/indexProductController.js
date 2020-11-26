if (document.querySelector('#indexProductController')) {
  Vue.component('index-product', {
    template: '#index-product-template',
    data() {
      return {
        list: [],
        search: {
          name: '',
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
          name: this.search.name
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/product/all?page=' + data.page +
          '&per_page=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name
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
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = {}
        this.formdata = {
            ...data,
            'name': data.name
        }
      },
      removeSingleEntry(data) {
        console.log(data);
        axios.delete('/api/product/' + data.id).then((repsonse) => {
          this.fetchTable();
        })
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

  Vue.component('form-product', {
    template: '#form-product-template',
    props: ['data', 'clearform'],
    data() {
      return {
        form: {
          id: '',
          name: ''
        },
        formErrors: {}
      }
    },
    mounted() {
    },
    methods: {
      onSubmit() {
        axios.post('/api/product', this.form)
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

  Vue.component('index-material', {
    template: '#index-material-template',
    data() {
      return {
        list: [],
        search: {
          name: '',
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
          name: this.search.name
        };
        axios.get(
          // subject to change (search list and pagination)
          '/api/materials/all?page=' + data.page +
          '&per_page=' + data.per_page +
          '&sortkey=' + data.sortkey +
          '&reverse=' + data.reverse +
          '&name=' + data.name
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
      },
      editSingleEntry(data) {
        this.clearform = {}
        this.formdata = {}
        this.formdata = {
            ...data,
            'name': data.name
        }
      },
      removeSingleEntry(data) {
        console.log(data);
        axios.delete('/api/materials/' + data.id).then((repsonse) => {
          this.fetchTable();
        })
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

  Vue.component('form-material', {
    template: '#form-material-template',
    props: ['data', 'clearform'],
    data() {
      return {
        form: {
          id: '',
          name: ''
        },
        formErrors: {}
      }
    },
    mounted() {
    },
    methods: {
      onSubmit() {
        axios.post('/api/materials', this.form)
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
    el: '#indexProductController',
  });
}
