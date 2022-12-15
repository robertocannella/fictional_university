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
        const response = await this.api.delete(`/wp-json/wp/v2/${type}/${id}`)
        return response;
    }

    async createSingle(type, data){
        return  await this.api.post(`/wp-json/wp/v2/${type}`,data)
    }

    async updateSingle(type, id, data){
        try {
            const result = await this.api.post(`/wp-json/wp/v2/${type}/${id}`,data);
            return result
        }catch (error){
            return error;
        }
    }

}

const axiosService = new AxiosService();
export default axiosService;