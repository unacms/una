export const cloneCanvas = (origin, target) => {
    target = target || document.createElement('canvas');
    target.width = origin.width;
    target.height = origin.height;
    const ctx = target.getContext('2d');
    ctx.drawImage(origin, 0, 0);
    return target;
}