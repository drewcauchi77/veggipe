export const getDeviceType = (userAgent) => {
    const ua = userAgent.toLowerCase();

    if (ua.includes('tablet') ||
        ua.includes('ipad') ||
        ua.includes('playbook') ||
        ua.includes('silk') ||
        (ua.includes('android') && !ua.includes('mobile'))) {
        return 'tablet';
    }

    if (ua.includes('mobile') ||
        ua.includes('iphone') ||
        ua.includes('ipod') ||
        ua.includes('blackberry') ||
        ua.includes('opera mini') ||
        ua.includes('windows phone')) {
        return 'mobile';
    }

    return 'desktop';
};