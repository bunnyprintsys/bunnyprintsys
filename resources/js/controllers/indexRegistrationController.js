if (document.querySelector('#indexRegistrationController')) {
    Vue.component('index-registration', {
      template: '#index-registration-template',
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
          this.loading = true
          this.form.otp_request_count = 0
          axios.post('/api/registration/validate/phonenumber', this.form)
          .then((response) => {
            this.form.phone_number_format_valid = true
          })
          .catch((error) => {
            this.formErrors = error.response.data.errors
            this.form.phone_number_format_valid = false
          })
          .finally(() => {
            this.loading = false
          });
        }, 800),
        onSendOTPClicked() {
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
        },
        onOTPCompleted(value) {
          this.form.otp = value
          // this.loading = true
          axios.post('/api/registration', this.form)
          .then((response) => {
            flash('Thanks you for signing up', 'success');
            // this.form = this.getFormDefault()
          })
            .catch((error) => {
              this.formErrors = error.response.data.errors
          })
            .finally(() => {
              // this.loading = false
          });
        },
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
        onProceedButtonClicked(prev, next) {
            this.steps[prev] = false
            this.steps[next] = true
        },
        customLabelCountriesOption(option) {
          return `${option.symbol} (+${option.code})`
        },
        getFormDefault() {
          return {
            is_company: 'false',
            company_name: '',
            name: '',
            email: '',
            phone_country_id: '',
            phone_number: '',
            phone_number_format_valid: false,
            otp_countdown: false,
            otp_request_count: 0,
            countdown: 30,
          }
        },
        getRegistrationStepsDefault() {
            return {
                step_1: true,
                step_2: false,
                step_3: false
            }
          }
        },
    });

    new Vue({
      el: '#indexRegistrationController',
    });
  }