/**
 * Write a JavaScript function that would generate a hex color code (#f1f2f3) from the full name of a person. It should always generate the same color for a given name. Describe how you arrived at your solution.
 *
 */
const HASH_INITIAL_VALUE = 0x811c9dc5;
const HASH_MULTIPLIER = 0x01000193;

function normalizeName(name) {
    if (typeof name !== 'string' || name.trim().length === 0) {
        throw new Error('Name must be a non-empty string.');
    }
    return name
        .normalize('NFC')
        .trim()
        .toLowerCase()
        .replace(/\s+/g, ' ');
}

function calculateHash(input) {
    let hash = HASH_INITIAL_VALUE;
    for (let i = 0; i < input.length; i++) {
        hash ^= input.charCodeAt(i);
        hash = Math.imul(hash, HASH_MULTIPLIER);
    }
    return hash >>> 0;
}

function getColorFromName(name) {
    const normalizedName = normalizeName(name);
    const hash = calculateHash(normalizedName);
    const rgb = hash & 0xffffff;
    return '#' + rgb.toString(16).padStart(6, '0');
}

function getAvatarColorFromName(name) {
    const normalizedName = normalizeName(name);
    const hash = calculateHash(normalizedName);
    const hue = hash % 360;
    return hslToHex(hue, 65, 50);
}

function hslToHex(h, s, l) {
    s /= 100;
    l /= 100;
    const k = (n) => (n + h / 30) % 12;
    const a = s * Math.min(l, 1 - l);
    const f = (n) => {
        const c = l - a * Math.max(-1, Math.min(k(n) - 3, Math.min(9 - k(n), 1)));
        return Math.round(c * 255).toString(16).padStart(2, '0');
    };
    return `#${f(0)}${f(8)}${f(4)}`;
}

module.exports = { getColorFromName, getAvatarColorFromName };