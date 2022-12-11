import axios from "axios";



class AxiosService {
    // Get functionality is inside PHP page scripts
    // This file only Create Delete Update from frontend.
    constructor() {
        this.api = axios.create({
            baseURL: globalSiteData.siteUrl,
            headers: {
                'content-type': 'application/json',
                'X-WP-Nonce': globalSiteData.nonceX
            }
        });
    }

    async deleteSingle(type, id){
        try {
            const response = await this.api.delete(`/wp-json/wp/v2/${type}/${id}`)
            return response;
        }
        catch (error) {
            return error;
        }

    }
    async createSingle(type, data){
        try {
            const response =  await this.api.post(`/wp-json/wp/v2/${type}`,data)
            console.log('inside axios response')
            return response;
        }
        catch (error) {
            return error;
        }
    }

    async updateSingle(type, id, data){

        return await this.api.post(`/wp-json/wp/v2/${type}/${id}`,data)
            .then( function (response) {
                console.log(response);
                return true;
            })
            .catch(function (error) {
                alert(`Error: ${error.message}` )
                console.log(error);
                return error;
            });

    }

}

const axiosService = new AxiosService();
export default axiosService;