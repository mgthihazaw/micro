
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//TinyMce 
import Editor from '@tinymce/tinymce-vue';
Vue.component('editor', Editor)

//Alert Message

import swal from 'sweetalert2';
window.swal=swal;
const Toast = swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
  });
window.Toast=Toast


import moment from 'moment';
Vue.filter('myDate', function (created) {
	return moment().format('YYYY-MM-DD');
})
//MultiSelect
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)

window.Bus = new Vue();

import Auth from './Helpers/Auth';
Vue.mixin(Auth);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import router from './Router/router.js';

import {User} from './Helpers/User.js';
import {Gate} from './Helpers/Gate.js';

axios.interceptors.response.use(function (response) {
	// Do something with response data
	
	return response;
}, function (error) {
	// console.log("NetWork Error",error)
	// if(error =="Network Error"){
	// 	Toast.fire({
	// 		type : "error",
	// 		title : "Network Error,check your network connection and refresh the page"
	// 	});
	// }
	Toast.fire({
		type: "error",
		title: error.response.data.error
	  });
	  
	if (error.response.data.type == 'token_invalid') {
		// alert(error.response.data.error)
		Bus.$emit('logout')
	} else if (error.response.data.type == 'token_expired') {
		// alert(error.response.data.error)
		Bus.$emit('logout')
	}
	return Promise.reject(error)
});


window.User = new User()
window.Gate = new Gate()

// if (window.User.isLoggedIn()) {
// 	axios.post('/api/auth/me')
// 		.then(response => {
// 			// console.log(response.data.user.roles,response.data.user.permissions)
// 			window.Gate.setUser(response.data.user.roles,response.data.user.permissions);
// 			//   console.log(Gate.can('service-list'))
// 		})
// 		.catch(err=>{
// 			console.log(err)
// 		})
// }

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('app-home', require('./components/AppHome.vue'));

// import Turbolinks from 'turbolinks';
// Turbolinks.start()
  
//   import TurbolinksAdapter from 'vue-turbolinks';
//   Vue.use(TurbolinksAdapter)
//   document.addEventListener('turbolinks:load', () => {
//     var element=document.getElementById("app")
//     if(element != null){
const app = new Vue({
	el: '#app',
	router,
});
// }
// });
$(function(){
	$(document).on('keydown', function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	})
})
