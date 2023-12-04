import { ref } from 'vue';
import axios from 'axios';
import Cookies from 'js-cookie';

export default function useFetch() {
  const data = ref(null);
  const error = ref(null);
  const status = ref(null);
  const isLoading = ref(false);
  
  const getFullApiUrl = (endpoint) => {
    // return `${import.meta.env.VITE_API_URL}:${import.meta.env.VITE_API_PORT}${endpoint}`;
    return `${import.meta.env.VITE_API_BACKEND}${endpoint}`;
  };
  const fetchData = async (endpoint, method = 'GET', postData = null) => {
    isLoading.value = true;
    const url = getFullApiUrl(endpoint);
  
    try {
      let response;

      const headers = {
        headers: {
          'Authorization': `Bearer ${Cookies.get('token')}`
        }
      };
      if (method === 'POST') {
        response = await axios.post(url, postData, headers);
      } else {
        response = await axios.get(url, headers);
      }
  
      console.log('Response: ', response);
  
      data.value = response.data;
      status.value = response.status;

      return response;
    } catch (e) {
      error.value = 'Failed to fetch data. ' + e;
      console.error(error.value);

      return e.response;
    } finally {
      isLoading.value = false;
    }
  };

  return { data, status, error, isLoading, fetchData };
}