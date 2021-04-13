/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// object oriented errors prompt
require('./error');

// global
window.Vue = require('vue');
window.moment = require('moment-timezone');
window.moment.tz.setDefault('Asia/Kuala_Lumpur');
window.select2 = require('select2');
window.events = new Vue();
window.flash = function (message, level = 'info') {
  window.events.$emit('flash', {message, level});
};

import Datepicker from 'vuejs-datepicker';
Vue.component('datepicker', Datepicker);

// loading overlay
import LoadingOverlay from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.component('loading-overlay', LoadingOverlay);

// vue multiselect
import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

// select2 vue
import Select2 from './components/Select2.vue';
Vue.component('select2', Select2);

import Select2MustChoose from './components/Select2MustChoose.vue';
Vue.component('select2-must', Select2MustChoose);

// flash vue
import Flash from './components/Flash.vue';
Vue.component('flash', Flash);

// OTP vue input template
import VueOtp2 from 'vue-otp-2';
Vue.component('vue-otp-2', VueOtp2);

import OtpInput from "@bachdgvn/vue-otp-input";
Vue.component("v-otp-input", OtpInput);

// vue pagination component
import Pagination from './components/Pagination.vue';
Vue.component('pagination', Pagination);

// vue js loading
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
Vue.component('pulse-loader', PulseLoader);

import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
Vue.component('clip-loader', ClipLoader);

import VueFileAgent from 'vue-file-agent';
Vue.component('vue-file-agent', VueFileAgent);

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
Vue.component('v-select', vSelect);

// vue controllers
require('./controllers/accountUserController');
require('./controllers/indexAdminController');
require('./controllers/indexCustomerController');
require('./controllers/indexJobTicketController');
require('./controllers/indexMemberController');
require('./controllers/indexOrderController');
require('./controllers/indexPriceController');
require('./controllers/indexRegistrationController');
require('./controllers/indexPasswordResetController');
require('./controllers/indexTransactionController');
require('./controllers/indexTransactionDataSettingController');
require('./controllers/indexProductController');
require('./controllers/indexProfileController');
require('./controllers/indexVoucherController');


$(".sidebar-dropdown > a").click(function () {
  $(".sidebar-submenu").slideUp(200);
  if (
    $(this)
      .parent()
      .hasClass("active")
  ) {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .parent()
      .removeClass("active");
  } else {
    $(".sidebar-dropdown").removeClass("active");
    $(this)
      .next(".sidebar-submenu")
      .slideDown(200);
    $(this)
      .parent()
      .addClass("active");
  }
});

$("#close-sidebar").click(function () {
  $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function () {
  $(".page-wrapper").addClass("toggled");
});

$(document).ready(function () {
    if ($(this).width() < 576) {
        $(".page-wrapper").removeClass("toggled");
    } else {
        $(".page-wrapper").addClass("toggled");
    }
});

$(window).resize(function() {
    if ($(this).width() < 576) {
      $(".page-wrapper").removeClass("toggled");
    } else {
      $(".page-wrapper").addClass("toggled");
    }
});

$('#checkAll').change(function () {
  var all = this;
  $(this).closest('table').find('input[type="checkbox"]').prop('checked', all.checked);
});