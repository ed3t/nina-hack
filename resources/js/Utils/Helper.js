export function truncateString(str, maxLength = 10) {
    if (str.length <= maxLength) {
        return str;
    }
    return str.slice(0, maxLength) + '...';
}

export function formatDatetime(datetime) {
    const date = new Date(datetime);

    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };

    return date.toLocaleString('en-US', options);
}

export function base64UrlEncode(str) {
    const base64 = btoa(str);

    return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
};

export function base64UrlDecode(encodedStr) {
    let base64 = encodedStr
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const padding = base64.length % 4;
    if (padding) {
        base64 += '='.repeat(4 - padding);
    }

    return atob(base64);
};
