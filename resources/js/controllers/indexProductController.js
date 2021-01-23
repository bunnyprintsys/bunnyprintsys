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
// console.log(JSON.parse(JSON.stringify(this.form)))
        this.formdata = {
            ...data,
            'name': data.name
        }
      },
      removeSingleEntry(data) {
        // console.log(data);
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
          name: '',
          is_material: '',
          is_shape: '',
          is_lamination: '',
          is_frame: '',
          is_finishing: '',
          material_id: ''
        },
        formErrors: {},
        bindedMaterials: [],
        nonBindedMaterials: [],
        bindedShapes: [],
        nonBindedShapes: [],
        bindedLaminations: [],
        nonBindedLaminations: [],
        bindedFrames: [],
        nonBindedFrames: [],
        bindedFinishings: [],
        nonBindedFinishings: [],
      }
    },
    mounted() {
    },
    methods: {
      onSubmit() {
        if(this.form.id) {
          axios.post('/api/product/update/' + this.form.id, this.form)
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
        }else {
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
        }
      },
      getMaterialsBinding() {
        axios.post('/api/materials/product-material/product-binding', {product_id: this.form.id}).then((response) => {
          this.bindedMaterials = response.data.binded
          this.nonBindedMaterials = response.data.unbinded
        })
      },
      getShapesBinding() {
        axios.post('/api/shapes/product-shape/product-binding', {product_id: this.form.id}).then((response) => {
          this.bindedShapes = response.data.binded
          this.nonBindedShapes = response.data.unbinded
        })
      },
      getLaminationsBinding() {
        axios.post('/api/laminations/product-lamination/product-binding', {product_id: this.form.id}).then((response) => {
          this.bindedLaminations = response.data.binded
          this.nonBindedLaminations = response.data.unbinded
        })
      },
      getFramesBinding() {
        axios.post('/api/frames/product-frame/product-binding', {product_id: this.form.id}).then((response) => {
          this.bindedFrames = response.data.binded
          this.nonBindedFrames = response.data.unbinded
        })
      },
      getFinishingsBinding() {
        axios.post('/api/finishings/product-finishing/product-binding', {product_id: this.form.id}).then((response) => {
          this.bindedFinishings = response.data.binded
          this.nonBindedFinishings = response.data.unbinded
        })
      },

      getAllFinishings() {
        axios.post('/api/finishings/all').then((response) => {
          this.finishings = response.data.data
        })
      },
      addProductBindingEntry(type) {
        switch(type) {
          case 'material':
            axios.post('/api/materials/product-material/bind', {product_id: this.form.id, material_id: this.form.material_id}).then((response) => {
              this.getMaterialsBinding()
              this.form.material_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'shape':
            axios.post('/api/shapes/product-shape/bind', {product_id: this.form.id, shape_id: this.form.shape_id}).then((response) => {
              this.getShapesBinding()
              this.form.shape_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'lamination':
            axios.post('/api/laminations/product-lamination/bind', {product_id: this.form.id, lamination_id: this.form.lamination_id}).then((response) => {
              this.getLaminationsBinding()
              this.form.lamination_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'frame':
            axios.post('/api/frames/product-frame/bind', {product_id: this.form.id, frame_id: this.form.frame_id}).then((response) => {
              this.getFramesBinding()
              this.form.frame_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'finishing':
            axios.post('/api/finishings/product-finishing/bind', {product_id: this.form.id, finishing_id: this.form.finishing_id}).then((response) => {
              this.getFinishingsBinding()
              this.form.finishing_id = {
                id: '',
                name: ''
              }
            })
            break;
        }
      },
      removeProductBinding(type, data) {
        switch(type) {
          case 'material':
            axios.post('/api/materials/delete/product/' + this.form.id, {material_id: data.id}).then((response) => {
              this.getMaterialsBinding()
              this.form.material_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'shape':
            axios.post('/api/shapes/delete/product/' + this.form.id, {shape_id: data.id}).then((response) => {
              this.getShapesBinding()
              this.form.shape_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'lamination':
            axios.post('/api/laminations/delete/product/' + this.form.id, {lamination_id: data.id}).then((response) => {
              this.getLaminationsBinding()
              this.form.lamination_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'frame':
            axios.post('/api/frames/delete/product/' + this.form.id, {frame_id: data.id}).then((response) => {
              this.getFramesBinding()
              this.form.frame_id = {
                id: '',
                name: ''
              }
            })
            break;
          case 'finishing':
            axios.post('/api/finishings/delete/product/' + this.form.id, {finishing_id: data.id}).then((response) => {
              this.getFinishingsBinding()
              this.form.finishing_id = {
                id: '',
                name: ''
              }
            })
            break;
        }
      }
    },
    watch: {
      'data'(val) {
        for (var key in this.form) {
          this.form[key] = this.data[key];
        }
        if(this.data.id) {
          this.getMaterialsBinding()
          this.getShapesBinding()
          this.getLaminationsBinding()
          this.getFramesBinding()
          this.getFinishingsBinding()
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
        axios.post(
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
        // console.log(data);
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
        if(this.form.id) {
          axios.post('/api/materials/update/' + this.form.id, this.form)
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
        }else {
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
        }
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

  Vue.component('index-shape', {
    template: '#index-shape-template',
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
        axios.post(
          // subject to change (search list and pagination)
          '/api/shapes/all?page=' + data.page +
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
        // console.log(data);
        axios.delete('/api/shapes/' + data.id).then((repsonse) => {
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

  Vue.component('form-shape', {
    template: '#form-shape-template',
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
        if(this.form.id) {
          axios.post('/api/shapes/update/' + this.form.id, this.form)
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
        }else {
          axios.post('/api/shapes', this.form)
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

  Vue.component('index-lamination', {
    template: '#index-lamination-template',
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
        axios.post(
          // subject to change (search list and pagination)
          '/api/laminations/all?page=' + data.page +
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
          // console.log(JSON.parse(JSON.stringify(this.list)))
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
        // console.log(data);
        axios.delete('/api/laminations/' + data.id).then((repsonse) => {
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

  Vue.component('form-lamination', {
    template: '#form-lamination-template',
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
        if(this.form.id) {
          axios.post('/api/laminations/update/' + this.form.id, this.form)
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
        }else {
          axios.post('/api/laminations', this.form)
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

  Vue.component('index-frame', {
    template: '#index-frame-template',
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
        axios.post(
          // subject to change (search list and pagination)
          '/api/frames/all?page=' + data.page +
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
        // console.log(data);
        axios.delete('/api/frames/' + data.id).then((repsonse) => {
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

  Vue.component('form-frame', {
    template: '#form-frame-template',
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
        if(this.form.id) {
          axios.post('/api/frames/update/' + this.form.id, this.form)
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
        }else {
          axios.post('/api/frames', this.form)
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

  Vue.component('index-finishing', {
    template: '#index-finishing-template',
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
        axios.post(
          // subject to change (search list and pagination)
          '/api/finishings/all?page=' + data.page +
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
        // console.log(data);
        axios.delete('/api/finishings/' + data.id).then((repsonse) => {
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

  Vue.component('form-finishing', {
    template: '#form-finishing-template',
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
        if(this.form.id) {
          axios.post('/api/finishings/update/' + this.form.id, this.form)
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
        }else {
          axios.post('/api/finishings', this.form)
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
