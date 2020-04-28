export const fitToBounds = (width, height, boundsWidth, boundsHeight) => {
    const resizeFactor = Math.min(boundsWidth / width, boundsHeight / height);
    return {
        width: Math.round(width * resizeFactor),
        height: Math.round(height * resizeFactor)
    };
};
