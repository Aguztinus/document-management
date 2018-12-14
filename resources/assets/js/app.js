/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import moment from "moment";
window.moment = moment;
import { Form, HasError, AlertError } from "vform";

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

import swal from "sweetalert2";
window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 5000
});
window.toast = toast;

window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

import Multiselect from "vue-multiselect";
Vue.component("multiselect", Multiselect);

import VueClip from "vue-clip";
Vue.use(VueClip);

import VTooltip from "v-tooltip";
Vue.use(VTooltip);

Vue.component("pagination", require("laravel-vue-pagination"));

import VueRouter from "vue-router";
Vue.use(VueRouter);

import VueProgressBar from "vue-progressbar";
Vue.use(VueProgressBar, {
    color: "#3490dc",
    failedColor: "red",
    height: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300
    }
});

let routes = [
    { path: "/", component: require("./components/Dashboard.vue") },
    { path: "/dashboard", component: require("./components/Dashboard.vue") },
    { path: "/developer", component: require("./components/Developer.vue") },
    { path: "/users", component: require("./components/Users.vue") },
    { path: "/units", component: require("./components/Units.vue") },
    { path: "/profile", component: require("./components/Profile.vue") },
    { path: "/document", component: require("./components/Document.vue") },
    {
        path: "/documentlist",
        component: require("./components/DocumentList.vue")
    },
    {
        path: "/documenttype",
        component: require("./components/DocumentType.vue")
    },
    { path: "/upload", component: require("./components/Upload.vue") },
    { path: "/tesselect", component: require("./components/TesSelect.vue") },
    { path: "/testable", component: require("./components/TesVuetable.vue") },
    { path: "*", component: require("./components/NotFound.vue") }
];

const router = new VueRouter({
    mode: "history",
    routes // short for `routes: routes`
});

Vue.filter("upText", function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter("myDate", function(created) {
    return moment(created)
        .locale("id")
        .format("LL");
});

Vue.filter("myDateshort", function(created) {
    return moment(created)
        .locale("id")
        .format("L");
});

import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
Vue.component("flat-pickr", flatPickr);

window.Fire = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("passport-clients", require("./components/passport/Clients.vue"));

import Vuetable from "vuetable-2/src/components/Vuetable";
import VueTablePagination from "vuetable-2/src/components/VuetablePagination";
import VueTablePaginationDropDown from "vuetable-2/src/components/VuetablePaginationDropdown";
import VueTablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo";
import VueEvents from "vue-events";

Vue.use(VueEvents);
Vue.component("vuetable", Vuetable);
Vue.component("vuetable-pagination", VueTablePagination);
Vue.component("vuetable-pagination-dropdown", VueTablePaginationDropDown);
Vue.component("vuetable-pagination-info", VueTablePaginationInfo);

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue")
);

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue")
);

Vue.component("not-found", require("./components/NotFound.vue"));

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue")
);

const app = new Vue({
    el: "#app",
    router,
    data: {
        search: ""
    },
    methods: {
        searchit: _.debounce(() => {
            Fire.$emit("searching");
        }, 1000),

        printme() {
            window.print();
        }
    }
});
