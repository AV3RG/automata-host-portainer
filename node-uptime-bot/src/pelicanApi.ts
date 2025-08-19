import axios from "axios";
import loadedEnv from "./loadedEnv";


interface NodeListSingle {
    object: 'node';
    attributes: {
        id: number;
        public: boolean;
        name: string;
        fqdn: string;
        scheme: string;
        daemon_listen: number;
    }
}

export async function pelicanFetchNodeList(): Promise<NodeListSingle[]> {
    const response = await axios.get(`${loadedEnv.PELICAN_API_URL}/nodes`, {
        headers: {
            'Authorization': `Bearer ${loadedEnv.PELICAN_API_KEY}`
        }
    });
    return response.data.data;
}
