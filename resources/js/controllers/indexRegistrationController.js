if (document.querySelector('#indexRegistrationController')) {
  Vue.component('index-registration', {
    template: '#index-registration-template',
    data() {
      return {
        form: this.getFormDefault(),
        formErrors: []
      }
    },
    methods: {
      onSubmit() {
        axios.post('/api/registration', this.form)
        .then((response) => {
          flash('Your registration has submitted, we will contact you shortly', 'success');
          this.form = this.getFormDefault()
        })
          .catch((error) => {
            this.formErrors = error.response.data.errors
        });
      },
      onIsCompanyChosen(value) {
        this.form.is_company = value
        this.formErrors = []
      },
      getFormDefault() {
        return {
          is_company: 'false',
          name: '',
          email: '',
          contact: ''
        }
      }
    },
  });

  new Vue({
    el: '#indexRegistrationController',
  });
}
