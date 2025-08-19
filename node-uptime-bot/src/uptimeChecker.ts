import { pelicanFetchNodeList } from "./pelicanApi";
import axios from "axios";

type UptimeStatus = 'online' | 'offline' | 'unknown';

export interface UptimeCheckResult {
    nodes: {
        [key: string]: {
            status: UptimeStatus;
        }
    }
}

export async function checkUptime(): Promise<UptimeCheckResult> {
    const nodes = await pelicanFetchNodeList();
    const results: UptimeCheckResult = {
        nodes: {}
    }
    nodes.forEach(node => {
        results.nodes[node.attributes.name] = {
            status: 'unknown'
        }
    })
    for (const node of nodes) {
        const status = await checkSingleUptime(node.attributes.fqdn);
        results.nodes[node.attributes.name]!!.status = status;
    }
    return results;
}

async function checkSingleUptime(nodeUrl: string): Promise<UptimeStatus> {
    const response = await axios.get(`${nodeUrl}/api/system`);
    // If the node is online, it will return 401 because the api is protected
    if (response.status === 401) {
        return 'online';
    } else {
        return 'offline';
    }
}
