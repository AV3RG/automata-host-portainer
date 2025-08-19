import fs from "fs";

function markNodeAsOffline(node: string) {
    const markedDown = fs.readFileSync("markedDown.txt", "utf8");
    const lines = markedDown.split("\n");
    if (!lines.includes(node)) {
        lines.push(node);
        fs.writeFileSync("markedDown.txt", lines.join("\n"));
    }
}

function markNodeAsOnline(node: string) {
    const markedDown = fs.readFileSync("markedDown.txt", "utf8");
    const lines = markedDown.split("\n");
    const newLines = lines.filter(line => line !== node);
    fs.writeFileSync("markedDown.txt", newLines.join("\n"));
}

function isNodeMarkedAsOffline(node: string) {
    const markedDown = fs.readFileSync("markedDown.txt", "utf8");
    const lines = markedDown.split("\n");
    return lines.includes(node);
}

export { markNodeAsOffline, markNodeAsOnline, isNodeMarkedAsOffline };
