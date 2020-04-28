import { getMarkupValue } from './getMarkupValue';

const isDefined = value => value != null;

export const getMarkupRect = (rect, size, scalar = 1) => {

    let left = getMarkupValue(rect.x, size, scalar, 'width') || getMarkupValue(rect.left, size, scalar, 'width');
    let top = getMarkupValue(rect.y, size, scalar, 'height') || getMarkupValue(rect.top, size, scalar, 'height');
    let width = getMarkupValue(rect.width, size, scalar, 'width');
    let height = getMarkupValue(rect.height, size, scalar, 'height');
    let right = getMarkupValue(rect.right, size, scalar, 'width');
    let bottom = getMarkupValue(rect.bottom, size, scalar, 'height');

    if (!isDefined(top)) {
        if (isDefined(height) && isDefined(bottom)) {
            top = size.height - height - bottom;
        }
        else {
            top = bottom;
        }
    }
    
    if (!isDefined(left)) {
        if (isDefined(width) && isDefined(right)) {
            left = size.width - width - right;
        }
        else {
            left = right;
        }
    }
    
    if (!isDefined(width)) {
        if (isDefined(left) && isDefined(right)) {
            width = size.width - left - right;
        }
        else {
            width = 0;
        }
    }

    if (!isDefined(height)) {
        if (isDefined(top) && isDefined(bottom)) {
            height = size.height - top - bottom;
        }
        else {
            height = 0;
        }
    }

    return {
        x: left || 0,
        y: top || 0,
        width: width || 0,
        height: height || 0
    }

}