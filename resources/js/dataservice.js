import axios from "axios";
class DataService{

    getEmployees(search_key){
        return axios.get(`/employee/get?search_key=${search_key}`);
    }

    createEmployee(data){
        return axios.post(`/employee/create`,data);
    }

    updateEmployee(id,data){
        return axios.post(`/employee/update/${id}`,data);
    }

    deleteEmployee(id){
        return axios.get(`/employee/delete/${id}`);
    }

}

export default  new DataService();
