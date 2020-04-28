import { ResizeWorker } from './ResizeWorker';

// creates an ImageBitmap object from the current item
export const createPreviewImageBitmapCreator = createWorker => (
    file,
    width,
    height,
    cb
) => {
    // now let's scale the image in a worker
    const worker = createWorker(ResizeWorker);
    worker.post({ file, width, height }, imageBitmap => {
        cb({ imageBitmap, width, height });
    });
};
