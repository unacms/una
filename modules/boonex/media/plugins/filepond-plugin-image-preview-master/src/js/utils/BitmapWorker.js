/**
 * Bitmap Worker
 */
export const BitmapWorker = function() {
    self.onmessage = e => {
        createImageBitmap(e.data.message.file).then(bitmap => {
            self.postMessage({ id: e.data.id, message: bitmap }, [bitmap]);
        });
    };
};