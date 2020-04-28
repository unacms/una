export const vectorMultiply = (v, amount) => createVector(v.x * amount, v.y * amount);

export const vectorAdd = (a, b) => createVector(a.x + b.x, a.y + b.y);

export const vectorNormalize = (v) => {
    const l = Math.sqrt(v.x * v.x + v.y * v.y);
    if (l === 0) {
        return {
            x: 0,
            y: 0
        }
    }
    return createVector(v.x / l, v.y / l);
}

export const vectorRotate = (v, radians, origin) => {
    const cos = Math.cos(radians);
    const sin = Math.sin(radians);
    const t = createVector(v.x - origin.x, v.y - origin.y);
    return createVector(
        origin.x + (cos * t.x) - (sin * t.y),
        origin.y + (sin * t.x) + (cos * t.y)
    );
};

export const createVector = (x = 0, y = 0) => ({ x, y })