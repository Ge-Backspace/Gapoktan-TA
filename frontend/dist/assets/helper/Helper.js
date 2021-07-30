//function helper

let is_num = require('locutus/php/var/is_numeric');


export const humanize = (str) => {
    let frags = str.split('_');
    for (let i = 0; i < frags.length; i++) {
        frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
    }
    return frags.join(' ');
};

export const showErrorField = (data, notif) => {
    Object.keys(data).forEach((v, k) => {
        setTimeout(() => {
            notif.error({
                title: humanize(v),
                message: data[v]
            });
        }, 500)
    });
};

export const isEmptyObj = (obj) => {
    return !Object.keys(obj).length;
}

export const is_numeric = (num) => {
    return is_num(num);
}
