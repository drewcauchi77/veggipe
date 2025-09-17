import {getDeviceType} from "./app.helpers.js";

export const eagerLoadRecipeImages = (userAgent) => {
    const device = getDeviceType(userAgent)

    if (device === 'mobile') {
        return 2;
    } else if (device === 'tablet') {
        return 4;
    }

    return 6;
};