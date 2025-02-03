import axios from 'axios';
// @ts-ignore
window.axios = axios;

// @ts-ignore
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';