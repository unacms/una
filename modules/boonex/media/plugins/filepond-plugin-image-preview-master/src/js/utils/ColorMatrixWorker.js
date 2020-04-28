/**
 * ColorMatrix Worker
 */
export const ColorMatrixWorker = function() {
    self.onmessage = e => {

        const imageData = e.data.message.imageData;
        const matrix = e.data.message.colorMatrix;

        const data = imageData.data;
        const l = data.length;

        const m11 = matrix[0];
        const m12 = matrix[1];
        const m13 = matrix[2];
        const m14 = matrix[3];
        const m15 = matrix[4];

        const m21 = matrix[5];
        const m22 = matrix[6];
        const m23 = matrix[7];
        const m24 = matrix[8];
        const m25 = matrix[9];
        
        const m31 = matrix[10];
        const m32 = matrix[11];
        const m33 = matrix[12];
        const m34 = matrix[13];
        const m35 = matrix[14];

        const m41 = matrix[15];
        const m42 = matrix[16];
        const m43 = matrix[17];
        const m44 = matrix[18];
        const m45 = matrix[19];

        let index=0, r=0.0, g=0.0, b=0.0, a=0.0;

        for (; index<l; index+=4) {
            r = data[index] / 255;
            g = data[index + 1] / 255;
            b = data[index + 2] / 255;
            a = data[index + 3] / 255;
            data[index] = Math.max(0, Math.min(((r * m11) + (g * m12) + (b * m13) + (a * m14) + (m15)) * 255, 255));
            data[index + 1] = Math.max(0, Math.min(((r * m21) + (g * m22) + (b * m23) + (a * m24) + (m25)) * 255, 255));
            data[index + 2] = Math.max(0, Math.min(((r * m31) + (g * m32) + (b * m33) + (a * m34) + (m35)) * 255, 255));
            data[index + 3] = Math.max(0, Math.min(((r * m41) + (g * m42) + (b * m43) + (a * m44) + (m45)) * 255, 255));
        }

        self.postMessage({ id: e.data.id, message: imageData }, [imageData.data.buffer]);
    };
};