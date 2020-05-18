if (document.querySelector('#accountUserController')) {
    Vue.component('account-user', {
      template: '#account-user-template',
      data() {
        return {
          form: {
            id: '',
            name: '',
            email: '',
            phone_number: '',
            password: '',
            password_confirmation: '',
            is_temporary_password: 5
          },
          formErrors: {}
        }
      },
      mounted() {
        this.fetchUser();
      },
      methods: {
        fetchUser() {
          axios.get(
            '/api/user/self'
          ).then((response) => {
            const result = response.data;
            if (result) {
              this.form = result;
            }
          });
        },
        onSubmit() {
          axios.post('/api/user/store-update', this.form)
          .then((response) => {
            flash('Password has successfully changed', 'success')
            this.formErrors = {}
            this.form.password = ''
            this.form.password_confirmation = ''
          })
          .catch((error) => {
            this.formErrors = error.response.data.errors
          });
        }
      }
    });

    new Vue({
      el: '#accountUserController',
    });
  }
