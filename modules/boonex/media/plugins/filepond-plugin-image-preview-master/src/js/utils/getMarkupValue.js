export const getMarkupValue = (value, size, scalar = 1, axis) => {
    if (typeof value === 'string') {
        return parseFloat(value) * scalar;
    }
    if (typeof value === 'number') {
        return value * (axis ? size[axis] : Math.min(size.width, size.height));
    }
    return;
};