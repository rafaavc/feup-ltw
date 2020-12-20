

export const elapsedTime = (time) => {
    time = Math.floor(new Date(time).getTime()/1000);
    time = Math.floor(Date.now()/1000) - time;
    time = time < 1 ? 1 : time;
    const marks = {
        31536000: 'year',
        2592000: 'month',
        604800: 'week',
        86400: 'day',
        3600: 'hour',
        60: 'minute',
        1: 'second'
    };

    for (const mark in marks) {
        const text = marks[mark];
        if (time < mark) continue;
        const markNo = Math.floor(time / mark);
        return `${markNo} ${text}${markNo > 1 ? 's' : ''}`;
    }
}

export const getCSRF = () => {
    return document.body.dataset.csrf;
}
