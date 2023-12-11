// services/api.js
import axios from "axios";

const api = axios.create({
    baseURL: "/api", // Adjust the base URL based on your Laravel API routes
});

// Add a request interceptor
api.interceptors.request.use(
    function (config) {
        // You can modify the request config here (e.g., add headers)
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

// Add a response interceptor
api.interceptors.response.use(
    function (response) {
        // You can modify the response data here
        return response.data;
    },
    function (error) {
        // You can handle errors here
        return Promise.reject(error);
    }
);

export default api;
