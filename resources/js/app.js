
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/* imports */
import moment from 'moment';
Vue.prototype.$moment = moment;
import { Form, HasError, AlertError } from 'vform';
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import swal from 'sweetalert2';
import Gate from "./Gate"

/* Passing laravel gate to js */
Vue.prototype.$gate = new Gate(window.user);

/* Sweetalert2 */
window.swal = swal;

const toast = swal.mixin({
  	toast: true,
  	position: 'top-end',
  	showConfirmButton: false,
  	timer: 3000,
  	timerProgressBar: true,
  	onOpen: (toast) => {
    	toast.addEventListener('mouseenter', swal.stopTimer)
    	toast.addEventListener('mouseleave', swal.resumeTimer)
  	}
})

window.toast = toast;

/* vForm */
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

/* Vue Router */
Vue.use(VueRouter)

let routes = [
  	{ path: '/dashboard', component: require('./components/Dashboard.vue').default },
  	{ path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
  	{ path: '/users', component: require('./components/Users.vue').default },
    { path: '/employees', component: require('./components/Employee.vue').default },
    { path: '/employeeSchedule', component: require('./components/EmployeeSchedules.vue').default },
    { path: '/employeeSchedule/logs/:id', name: 'employeeLogs', component: require('./components/EmployeeScheduleLogs.vue').default },
    { path: '/', redirect: '/dashboard' },
    { path: '/home', redirect: '/dashboard' },
    { path: '*', component: require('./components/NotFound.vue').default },
]

const router = new VueRouter({
	mode: 'history',
 	routes, // short for `routes: routes`
});

/* Vue Global Filters */
Vue.filter('uppercase', function(text){
	return text.charAt(0).toUpperCase() + text.slice(1);
	// return text.toUpperCase();
})

Vue.filter('formatDate', function(timestamp){
	return moment(timestamp).format('MMMM DD, YYYY');
})

Vue.filter('formatTime', function(timestamp){
  return moment(moment().format('YYYY-MM-DD')+' '+timestamp).format('h:mm A');
})

/* Vue progress bar */
Vue.use(VueProgressBar, {
  	color: 'rgb(143, 255, 199)',
  	failedColor: 'red',
  	height: '2px'
})

/* Vue pagination */
Vue.component('pagination', require('laravel-vue-pagination'));

// Custom event
window.Fire = new Vue();
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* passport */
Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue').default
);

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue').default
);
/* end passport */

/* not found component */
Vue.component(
  'not-found',
  require('./components/NotFound.vue').default
);
/* not found component end */

// datepicker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker',DatePicker); 

// vue moment
Vue.component(require('vue-moment'));

const app = new Vue({
    el: '#app',
   	router,
    data: {
      search: '',
    },
    methods:{
      searchIt: _.debounce(() => {
        Fire.$emit('searching');
      },500),

      printme() {
        window.print();
      }
    }
});
