import "alpinejs";

import React from "react";
import { createRoot } from "react-dom/client";
import ElectionResults from "./components/ElectionResults";
import Dashboard from "./components/Dashboard";

window.$ = window.jQuery = require("jquery");
window.Swal = require("sweetalert2");

// CoreUI
require("@coreui/coreui");

// Boilerplate
require("../plugins");

const adminDashboard = document.getElementById("admin");

if (adminDashboard) {
    createRoot(adminDashboard).render(<Dashboard />);
}

const root = document.getElementById("root");
if (root) {
    createRoot(root).render(<ElectionResults />);
}
