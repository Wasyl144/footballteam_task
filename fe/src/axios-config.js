import axios from 'axios';
import router from './router';

axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === 401) {
      router.push('/');
    }
    return Promise.reject(error);
  }
);