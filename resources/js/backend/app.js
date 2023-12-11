import "alpinejs";

import React from "react";
import { createRoot } from "react-dom/client";
import ElectionResults from "./components/ElectionResults";

window.$ = window.jQuery = require("jquery");
window.Swal = require("sweetalert2");

// CoreUI
require("@coreui/coreui");

// Boilerplate
require("../plugins");
const root = document.getElementById("root");

createRoot(root).render(<ElectionResults />);
