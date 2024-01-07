/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import React from "react";
import {
    createBrowserRouter,
    RouterProvider,
    Route,
    Link,
} from "react-router-dom";
import { createRoot } from "react-dom/client";
import Dashboard from "../backend/components/Dashboard";

import ConstituencyWise from "../backend/components/dashboard-charts/ConstituencyWiseChart";
import ConstituencyWiseDzongkha from "../backend/components/dashboard-charts/ConstituencyWiseChartDzongkha";
import OverallResultDzongkha from "../backend/components/dashboard-charts/OverallResultDzongkha";

require("../bootstrap");
require("../plugins");

// import Vue from "vue";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const router = createBrowserRouter([
    {
        path: "/",
        element: <Dashboard />,
    },
    {
        path: "/overall",
        element: <OverallResultDzongkha />,
    },
    {
        path: "/constituency-wise",
        element: <ConstituencyWise />,
    },
    {
        path: "/dz/constituency-wise",
        element: <ConstituencyWiseDzongkha />,
    },
]);

const root = document.getElementById("root");

if (root) {
    createRoot(document.getElementById("root")).render(
        <RouterProvider router={router} />
    );
}

// const constituencyWise = document.getElementById("constituency-wise");

// if (constituencyWise) {
//     createRoot(constituencyWise).render(<ConstituencyWise />);
// }

// Vue.component(
//     "example-component",
//     require("./components/ExampleComponent.vue").default
// );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: "#app",
// });
