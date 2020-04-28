import { fixImageOrientation } from './fixImageOrientation';

// draws the preview image to canvas
export const createPreviewImage = (data, width, height, orientation) => {

    // can't draw on half pixels
    width = Math.round(width);
    height = Math.round(height);

    // draw image
    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');

    // if is rotated incorrectly swap width and height
    if (orientation >= 5 && orientation <= 8) {
        [width, height] = [height, width];
    }

    // correct image orientation
    fixImageOrientation(ctx, width, height, orientation);

    // draw the image
    ctx.drawImage(data, 0, 0, width, height);

    return canvas;
};
