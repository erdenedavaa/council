/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import algoliasearch from "algoliasearch/lite";

require('./bootstrap');

window.Vue = require('vue');

import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);


let authorizations = require('./authorizations');

// Global aar zarim function zarlah yed ene zamaar
Vue.prototype.authorize = function (...params) {
    // Additional admin privileges.
    // return true; System iin huvid bygdiis zasah erhtei bolno

    if (!window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);  // authorize('foo', 'bar')
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;


Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);
Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);

// Algolia example START
Vue.component('my-search', require('./components/MySearch.vue').default);
// Algolia example END

Vue.component('thread-view', require('./pages/Thread.vue').default);
// Bolohgui zunduu udsanii daraa .default nemsen chin bolchloo

window.events = new Vue();

window.flash = function (message, level = 'success') {
    window.events.$emit('flash', {message, level});
} // flash('my new flash message')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    data: {
        searchClient: algoliasearch(
            'VLUG7C20ZQ',
            'd928962683cba0bd2285df417b71c9ca'
        )
    }
});
