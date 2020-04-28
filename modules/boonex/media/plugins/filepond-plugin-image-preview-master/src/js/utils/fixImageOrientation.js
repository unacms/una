const transforms = {
    1: () => [1, 0, 0, 1, 0, 0],
    2: width => [-1, 0, 0, 1, width, 0],
    3: (width, height) => [-1, 0, 0, -1, width, height],
    4: (width, height) => [1, 0, 0, -1, 0, height],
    5: () => [0, 1, 1, 0, 0, 0],
    6: (width, height) => [0, 1, -1, 0, height, 0],
    7: (width, height) => [0, -1, -1, 0, height, width],
    8: width => [0, -1, 1, 0, 0, width]
};

export const fixImageOrientation = (ctx, width, height, orientation) => {
    // no orientation supplied
    if (orientation === -1) {
        return;
    }

    ctx.transform.apply(ctx, transforms[orientation](width, height));
};
