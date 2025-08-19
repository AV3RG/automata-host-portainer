import { Client, EmbedBuilder, GatewayIntentBits } from "discord.js";
import loadedEnv from "./loadedEnv";
import type { UptimeCheckResult } from "./uptimeChecker";

const client = new Client({ intents: [GatewayIntentBits.Guilds] });

export async function checkInitialMessage() {
    const channel = await client.channels.fetch(loadedEnv.DISCORD_CHANNEL_ID);
    if (!channel || !channel.isTextBased() || !('send' in channel)) {
        throw new Error("Channel not found or cannot send messages");
    }

    // Check if channel has a message with the id of 134134134134134134
    const message = await channel.messages.fetch(loadedEnv.DISCORD_INITIAL_MESSAGE_ID);

    if (!message) {
        await sendInitialMessage();
    }
    
}

async function sendInitialMessage() {
    const channel = await client.channels.fetch(loadedEnv.DISCORD_CHANNEL_ID);
    if (!channel || !channel.isTextBased() || !('send' in channel)) {
        throw new Error("Channel not found or cannot send messages");
    }

    await channel.send("Hello, world!");
}

export async function updateUptimeStatus(uptimeCheck: UptimeCheckResult) {
    const channel = await client.channels.fetch(loadedEnv.DISCORD_CHANNEL_ID);
    if (!channel || !channel.isTextBased() || !('send' in channel)) {
        throw new Error("Channel not found or cannot send messages");
    }

    const message = await channel.messages.fetch(loadedEnv.DISCORD_INITIAL_MESSAGE_ID);
    if (!message) {
        throw new Error("Message not found");
    }

    await message.edit({ embeds: [uptimeCheckResultToEmbed(uptimeCheck)] });
}

function uptimeCheckResultToEmbed(uptimeCheck: UptimeCheckResult) {
    const embed = new EmbedBuilder()
        .setTitle("Uptime Status")
        .setDescription("Current uptime status of the nodes")
        .setColor(0x0099ff);

    for (const node of Object.entries(uptimeCheck.nodes)) {
        embed.addFields({ name: node[0], value: node[1].status });
    }

    return embed;
}