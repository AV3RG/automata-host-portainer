import dotenv from "dotenv";

dotenv.config();

const unverifiedEnv = {
    PELICAN_API_URL: process.env.PELICAN_API_URL,
    PELICAN_API_KEY: process.env.PELICAN_API_KEY,
    DISCORD_TOKEN: process.env.DISCORD_TOKEN,
    DISCORD_CHANNEL_ID: process.env.DISCORD_CHANNEL_ID,
    DISCORD_GUILD_ID: process.env.DISCORD_GUILD_ID,
    DISCORD_INITIAL_MESSAGE_ID: process.env.DISCORD_INITIAL_MESSAGE_ID,
    TIMER_INTERVAL: parseInt(process.env.TIMER_INTERVAL!),
}

const missingVars = Object.entries(unverifiedEnv)
    .filter(([_, value]) => !value)
    .map(([key]) => key);

if (missingVars.length > 0) {
    throw new Error(`Missing environment variables: ${missingVars.join(', ')}`);
}

const loadedEnv = Object.fromEntries(
    Object.entries(unverifiedEnv).map(([key, value]) => [key, value!])
) as { [K in keyof typeof unverifiedEnv]: NonNullable<(typeof unverifiedEnv)[K]> };

export default loadedEnv;
