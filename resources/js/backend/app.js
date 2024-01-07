import "alpinejs";

import React from "react";
import { createRoot } from "react-dom/client";
import "../i18n";

import ElectionResults from "./components/ElectionResults";
import OverallResultDzongkha from "./components/dashboard-charts/OverallResultDzongkha";

window.$ = window.jQuery = require("jquery");
window.Swal = require("sweetalert2");

// CoreUI
require("@coreui/coreui");

// Boilerplate
require("../plugins");

const adminDashboard = document.getElementById("admin");

if (adminDashboard) {
    createRoot(adminDashboard).render(<OverallResultDzongkha />);
}

const root = document.getElementById("root");
if (root) {
    createRoot(root).render(<ElectionResults />);
}
