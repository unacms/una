export const cloneImageData = imageData => {
    let id;
    try {
        id = new ImageData(imageData.width, imageData.height);
    }
    catch(e) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        id = ctx.createImageData(imageData.width, imageData.height);
    }
    id.data.set(new Uint8ClampedArray(imageData.data));
    return id;
};