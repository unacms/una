export const getImageSize = (url, cb) => {
    let image = new Image();
    image.onload = () => {
        const width = image.naturalWidth;
        const height = image.naturalHeight;
        image = null;
        cb(width, height);
    };
    image.src = url;
};
