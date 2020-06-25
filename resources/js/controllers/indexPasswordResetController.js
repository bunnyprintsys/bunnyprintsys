if (document.querySelector('#indexPasswordResetController')) {
  Vue.component('index-password-reset', {
    template: '#index-password-reset-template',
    data() {
      return {
        loading: false,
        form: this.getFormDefault(),
        steps: this.getRegistrationStepsDefault(),
        countries: [],
        formErrors: []
      }
    },
    mounted() {
      this.getCountriesOption()
    },
    methods: {
      getCountriesOption() {
        axios.get('/api/country').then((response) => {
          this.countries = response.data.data
          this.form.phone_country_id = this.countries[0]
        })
      },
      onPhoneNumberEntered: _.debounce(function(e) {
        this.formErrors = []
        // this.loading = true
        this.form.otp_request_count = 0
        axios.post('/api/registration/validate/user-phonenumber', this.form)
        .then((response) => {
          flash(response.data.msg, response.data.status)
          this.form.phone_number_format_valid = true
        })
        .catch((error) => {
          flash(error.response.data.msg, error.response.data.status)
          this.formErrors = error.response.data.errors
          this.form.phone_number_format_valid = false
        })
        .finally(() => {
          // this.loading = false
        });
      }, 800),
      onSendOTPClicked() {
        axios.post('/api/registration/create-otp', this.form)
        .then((response) => {
          this.form.otp_countdown = true
          this.form.otp_request_count += 1
          const countTime = setInterval(function(){
            this.form.countdown = this.form.countdown - 1
            if(this.form.countdown === 0) {
              clearInterval(countTime)
              this.form.countdown = 30
              this.form.otp_countdown = false
            }
          }.bind(this), 1000)
        })
        .catch((error) => {
          this.formErrors = error.response.data.errors
        });
      },
      onOTPCompleted(value) {
        this.form.otp = value
        this.loading = true
        axios.post('/api/registration/validate-otp', this.form)
        .then((response) => {
          flash(response.data.msg, response.data.status)
          this.form.is_otp_validated = true
          this.onProceedButtonClicked('step_1', 'step_2')
        })
          .catch((error) => {
            this.formErrors = error.response.data.errors
            flash(error.response.data.msg, error.response.data.status)
        })
          .finally(() => {
            this.loading = false
        });
      },
      onSubmit() {
        axios.post('/api/registration/password/update', this.form)
        .then((response) => {
          flash(response.data.msg, response.data.status)
          window.location.href = '/'
        })
        .catch((error) => {
            this.formErrors = error.response.data.errors
            flash(error.response.data.msg, error.response.data.status)
        });
      },
      validatePassword: _.debounce(function(e) {
        this.formErrors = []
        this.loading = true
        axios.post('/api/registration/validate/password', this.form)
        .then((response) => {
          this.form.is_password_validated = true
        })
        .catch((error) => {
          this.formErrors = error.response.data.errors
        })
        .finally(() => {
          this.loading = false
        });
      }, 1000),
      onProceedButtonClicked(prev, next) {
          this.steps[prev] = false
          this.steps[next] = true
      },
      customLabelCountriesOption(option) {
        return `${option.symbol} (+${option.code})`
      },
      getFormDefault() {
        return {
          phone_country_id: '',
          phone_number: '',
          phone_number_format_valid: false,
          otp_countdown: false,
          otp_request_count: 0,
          is_otp_validated: false,
          is_password_validated: false,
          countdown: 30,
        }
      },
      getRegistrationStepsDefault() {
          return {
              step_1: true,
              step_2: false
          }
        }
      },
  });

  new Vue({
    el: '#indexPasswordResetController',
  });
}