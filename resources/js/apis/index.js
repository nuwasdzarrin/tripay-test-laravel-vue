import Axios from 'axios';

Axios.defaults.baseURL = '/';
Axios.defaults.withCredentials = true;
Axios.defaults.headers.post['Content-Type'] = "application/json";
Axios.defaults.headers.post['Accept'] = "application/json";

const api = {
    product: {
        index: (params) => {
            return Axios.get('api/v1/products', {
                params: params
            });
        },
        buy: (data) => {
            return Axios.post('api/v1/products/buy', data);
        },
    },
    payment_method: {
        index: (params) => {
            return Axios.get('api/v1/payment_method', {
                params: params
            });
        },
    }
};

export default api;
