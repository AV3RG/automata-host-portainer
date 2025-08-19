import { checkInitialMessage, updateUptimeStatus } from "./discordHandler";
import loadedEnv from "./loadedEnv";
import { checkUptime } from "./uptimeChecker";

await checkInitialMessage();

async function startTask() {

    setInterval(async () => {
        setTimeout(async () => {
            try {
                const uptimeCheck = await checkUptime();
                await updateUptimeStatus(uptimeCheck);
            } catch (error) {
                console.error(error);
            }
        }, 60000);
    }, loadedEnv.TIMER_INTERVAL);
}

startTask();
