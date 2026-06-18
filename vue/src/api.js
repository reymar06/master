import axios from 'axios';

// Create a custom instance
const api = axios.create({
    baseURL: 'http://hrdapps70new:7070/data-extract/', // Sets your different path
     //baseURL: 'http://127.0.0.1:8001/', // Sets your different path
    timeout: 60000,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    withCredentials: false, // Essential if you are using Sanctum/cookies
});

export default api;
