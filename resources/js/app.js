/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import lang from 'element-ui/lib/locale/lang/es';
import locale from 'element-ui/lib/locale';
import ElementUI from 'element-ui';

// Vuex
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
Vue.use(Vuesax)

// Vuex
import store from './store';

// Mixins
import token from './mixins/token';
import loader from './mixins/loader';
import commons from './mixins/commons';

window.axios.interceptors.request.use(function (request) {
  // store.commit('setLoaderState', true);
  return request;
}, function (error) {
  // store.commit('setLoaderState', false);
  return Promise.reject(error);
});

window.axios.interceptors.response.use(function (response) {
  // store.commit('setLoaderState', false);
  return response;
}, function (error) {
  // store.commit('setLoaderState', false);
  return Promise.reject(error);
});

/**
 * Load mixins
 */
Vue.mixin(token);
Vue.mixin(loader);
Vue.mixin(commons);

// configure language
locale.use(lang);

Vue.use(ElementUI, {
  size: 'medium'
});

Vue.component('login-component', require('./components/Login/LoginComponent').default);

Vue.component('list-sales', require('./components/Sales/ListComponent').default);
Vue.component('create-pop-sales', require('./components/Sales/CreatePopUpComponent').default);
Vue.component('create-sales', require('./components/Sales/CreateComponent').default);
Vue.component('select-service', require('./components/Sales/SelectServiceComponent').default);
Vue.component('receipt-sales', require('./components/Sales/ReceiptComponent').default);
Vue.component('receipt-copy', require('./components/Sales/ReceiptCopyComponent').default);
Vue.component('confirm-sales', require('./components/Sales/ConfirmComponent').default);

Vue.component('create-items', require('./components/Items/CreateComponent').default);
Vue.component('edit-items', require('./components/Items/EditComponent').default);
Vue.component('delete-items', require('./components/Items/DeleteComponent').default);
Vue.component('list-items', require('./components/Items/ListComponent').default);

Vue.component('create-services', require('./components/Services/CreateComponent').default);
Vue.component('edit-services', require('./components/Services/EditComponent').default);
Vue.component('delete-services', require('./components/Services/DeleteComponent').default);
Vue.component('list-services', require('./components/Services/ListComponent').default);

Vue.component('create-cars', require('./components/Cars/CreateComponent').default);
Vue.component('edit-cars', require('./components/Cars/EditComponent').default);
Vue.component('delete-cars', require('./components/Cars/DeleteComponent').default);
Vue.component('list-cars', require('./components/Cars/ListComponent').default);

Vue.component('create-clients', require('./components/Clients/CreateComponent').default);
Vue.component('edit-clients', require('./components/Clients/EditComponent').default);
Vue.component('delete-clients', require('./components/Clients/DeleteComponent').default);
Vue.component('list-clients', require('./components/Clients/ListComponent').default);

Vue.component('create-carservices', require('./components/CarServices/CreateComponent').default);
Vue.component('delete-carservices', require('./components/CarServices/DeleteComponent').default);
Vue.component('list-carservices', require('./components/CarServices/ListComponent').default);
Vue.component('select-item', require('./components/CarServices/SelectItemComponent').default);

Vue.component('create-users', require('./components/Users/CreateComponent').default);
Vue.component('edit-users', require('./components/Users/EditComponent').default);
Vue.component('delete-users', require('./components/Users/DeleteComponent').default);
Vue.component('list-users', require('./components/Users/ListComponent').default);

Vue.component('create-roles', require('./components/Roles/CreateComponent').default);
Vue.component('edit-roles', require('./components/Roles/EditComponent').default);
Vue.component('delete-roles', require('./components/Roles/DeleteComponent').default);
Vue.component('list-roles', require('./components/Roles/ListComponent').default);

Vue.component('datepicker', require('./components/Dashboard/DatepickerComponent').default);
Vue.component('top-numbers', require('./components/Dashboard/TopNumbersComponent').default);
Vue.component('dashboard-chart', require('./components/Dashboard/ChartComponent').default);
Vue.component('dashboard-table', require('./components/Dashboard/TableComponent').default);
Vue.component('dashboard-users', require('./components/Dashboard/UsersComponent').default);
Vue.component('quick-receipt', require('./components/Dashboard/QuickReceipt').default);

// 2020
Vue.component('daily-cleaning', require('./components/Bitacora/Cleanings/DailyCleaningComponent').default);
Vue.component('weekly-cleaning', require('./components/Bitacora/Cleanings/weeklyCleaningComponent').default);
Vue.component('dc-edit', require('./components/Bitacora/Cleanings/DailyCleaningEditComponent').default);
Vue.component('daily-cars', require('./components/Bitacora/Cars/DailyCarsComponent').default);
Vue.component('weekly-cars', require('./components/Bitacora/Cars/weeklyCarsComponent').default);
Vue.component('view-carservices', require('./components/CarServices/ServiceViewComponent').default);
Vue.component('row-item', require('./components/CarServices/RowItemComponent').default);
Vue.component('tabs-cleaning', require('./components/Bitacora/Cleanings/ContainerComponent').default);
Vue.component('tabs-cars', require('./components/Bitacora/cars/ContainerComponent').default);

// Nomina
Vue.component('payroll-general', require('./components/Payroll/PayrollComponent').default);
Vue.component('payroll-item', require('./components/Payroll/PayrollItemComponent').default);
Vue.component('payroll-full', require('./components/Payroll/FullPayrollComponent').default);
Vue.component('payroll-container', require('./components/Payroll/TabContainerComponent').default);

// Gatos / Ingresos
Vue.component('expense-component', require('./components/Expenses/ExpenseComponent').default);
Vue.component('expense-edit', require('./components/Expenses/ExpenseEditComponent').default);
Vue.component('fund-edit', require('./components/Expenses/FundEditComponent').default);
Vue.component('income-component', require('./components/Expenses/IncomeComponent').default);
Vue.component('income-edit', require('./components/Expenses/IncomeEditComponent').default);
Vue.component('expense-container', require('./components/Expenses/TabContainerComponent').default);

// reports
Vue.component('utility-component', require('./components/Reports/UtilityComponent').default);
Vue.component('employee-component', require('./components/Reports/EmployeeIncomeComponent').default);
Vue.component('guarantee-component', require('./components/Reports/GuaranteeComponent').default);
Vue.component('guarantee-edit', require('./components/Reports/GuaranteeEditComponent').default);
Vue.component('report-container', require('./components/Reports/TabContainerComponent').default);

// Sales
Vue.component('edit-password', require('./components/Sales/EditPasswordComponent').default);


// Salomon S.A. Tools
Vue.component('file-upload', require('./components/Tools/UploadFile').default);
Vue.component('image-dialog', require('./components/Tools/ImageDialog').default);
Vue.component('bar-chart', require('./components/Tools/ChartComponent').default);
Vue.component('notify-component', require('./components/Tools/Notifications').default);
Vue.component('select-format', require('./components/Tools/SelectFormat').default);

Vue.component('create-workshops', require('./components/workshops/CreateComponent').default);
Vue.component('edit-workshops', require('./components/workshops/EditComponent').default);
Vue.component('delete-workshops', require('./components/workshops/DeleteComponent').default);
Vue.component('list-workshops', require('./components/workshops/ListComponent').default);

new Vue({
  el: '#app',
  store,
  mixins: [
    token,
    loader
  ],
  data() {
    return {
      primaryColor: "#021C73"
    }
  },
  methods: {
    goto: function (link) {
      window.location.href = link;
    }
  }
});