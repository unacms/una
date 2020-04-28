import { getMarkupValue } from './getMarkupValue';

export const getMarkupStyles = (markup, size, scale) => {
    const lineStyle = markup.borderStyle || markup.lineStyle || 'solid';
    const fill = markup.backgroundColor || markup.fontColor || 'transparent';
    const stroke =  markup.borderColor || markup.lineColor || 'transparent';
    const strokeWidth = getMarkupValue(markup.borderWidth || markup.lineWidth, size, scale);
    const lineCap = markup.lineCap || 'round';
    const lineJoin = markup.lineJoin || 'round';
    const dashes = typeof lineStyle === 'string' ? '' : lineStyle.map(v => getMarkupValue(v, size, scale)).join(',');
    const opacity = markup.opacity || 1;
    return {
        'stroke-linecap': lineCap,
        'stroke-linejoin': lineJoin,
        'stroke-width': strokeWidth || 0,
        'stroke-dasharray': dashes,
        stroke,
        fill,
        opacity
    }
}