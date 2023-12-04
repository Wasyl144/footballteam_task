import useFetch from '@/composables/useFetch';
import router from "@/router";
import Cookies from 'js-cookie';

export default function useHeader() {
  const { fetchData, status } = useFetch();
  
  const logOut = async () => {
     await fetchData('/api/logout', 'POST');

    if (status.value === 200) {
      Cookies.remove('token');
      router.push('/');
    }
  };
  return { logOut };
}