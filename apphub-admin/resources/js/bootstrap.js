import _ from 'lodash';
import axios from 'axios';

window._ = _;

// axios sends the CSRF token from the XSRF cookie automatically.
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
