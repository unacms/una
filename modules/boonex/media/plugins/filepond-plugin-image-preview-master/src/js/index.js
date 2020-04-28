import { isPreviewableImage } from './utils/isPreviewableImage';
import { createImageWrapperView } from './view/createImageWrapperView';
import { isBitmap } from './utils/isBitmap';

/**
 * Image Preview Plugin
 */
const plugin = fpAPI => {
    const { addFilter, utils } = fpAPI;
    const { Type, createRoute, isFile } = utils;

    // imagePreviewView
    const imagePreviewView = createImageWrapperView(fpAPI);

    // called for each view that is created right after the 'create' method
    addFilter('CREATE_VIEW', viewAPI => {
        
        // get reference to created view
        const { is, view, query } = viewAPI;

        // only hook up to item view and only if is enabled for this cropper
        if (!is('file') || !query('GET_ALLOW_IMAGE_PREVIEW')) return;

        // create the image preview plugin, but only do so if the item is an image
        const didLoadItem = ({ root, props }) => {

            const { id } = props;
            const item = query('GET_ITEM', id);

            // item could theoretically have been removed in the mean time
            if (!item || !isFile(item.file) || item.archived) return;

            // get the file object
            const file = item.file;

            // exit if this is not an image
            if (!isPreviewableImage(file)) return;

            // test if is filtered
            if (!query('GET_IMAGE_PREVIEW_FILTER_ITEM')(item)) return;

            // exit if image size is too high and no createImageBitmap support
            // this would simply bring the browser to its knees and that is not what we want
            const supportsCreateImageBitmap = 'createImageBitmap' in (window || {});
            const maxPreviewFileSize = query('GET_IMAGE_PREVIEW_MAX_FILE_SIZE');
            if (!supportsCreateImageBitmap && (maxPreviewFileSize && file.size > maxPreviewFileSize)) return;

            // set preview view
            root.ref.imagePreview = view.appendChildView(view.createChildView(imagePreviewView, { id }));

            // update height if is fixed
            const fixedPreviewHeight = root.query('GET_IMAGE_PREVIEW_HEIGHT');
            if (fixedPreviewHeight) {
                root.dispatch('DID_UPDATE_PANEL_HEIGHT', {
                    id: item.id,
                    height: fixedPreviewHeight
                });
            }

            // now ready
            const queue = !supportsCreateImageBitmap && file.size > query('GET_IMAGE_PREVIEW_MAX_INSTANT_PREVIEW_FILE_SIZE');
            root.dispatch('DID_IMAGE_PREVIEW_CONTAINER_CREATE', { id }, queue);
        };

        const rescaleItem = (root, props) => {

            if (!root.ref.imagePreview) return;

            let { id } = props;

            // get item
            const item = root.query('GET_ITEM', { id });
            if (!item) return;

            // if is fixed height or panel has aspect ratio, exit here, height has already been defined
            const panelAspectRatio = root.query('GET_PANEL_ASPECT_RATIO');
            const itemPanelAspectRatio = root.query('GET_ITEM_PANEL_ASPECT_RATIO');
            const fixedHeight = root.query('GET_IMAGE_PREVIEW_HEIGHT');
            if (panelAspectRatio || itemPanelAspectRatio || fixedHeight) return;

            // no data!
            let { imageWidth, imageHeight } = root.ref;
            if (!imageWidth || !imageHeight) return;

            // get height min and max
            const minPreviewHeight = root.query('GET_IMAGE_PREVIEW_MIN_HEIGHT');
            const maxPreviewHeight = root.query('GET_IMAGE_PREVIEW_MAX_HEIGHT');

            // orientation info
            const exif = item.getMetadata('exif') || {};
            const orientation = exif.orientation || -1;

            // get width and height from action, and swap of orientation is incorrect
            if (orientation >= 5 && orientation <= 8) [imageWidth, imageHeight] = [imageHeight, imageWidth];

            // scale up width and height when we're dealing with an SVG
            if (!isBitmap(item.file) || root.query('GET_IMAGE_PREVIEW_UPSCALE')) {
                const scalar = 2048 / imageWidth;
                imageWidth *= scalar;
                imageHeight *= scalar;
            }

            // image aspect ratio
            const imageAspectRatio = imageHeight / imageWidth;

            // we need the item to get to the crop size
            const previewAspectRatio = (item.getMetadata('crop') || {}).aspectRatio || imageAspectRatio;

            // preview height range
            let previewHeightMax = Math.max(minPreviewHeight, Math.min(imageHeight, maxPreviewHeight));
            const itemWidth = root.rect.element.width;
            const previewHeight = Math.min(itemWidth * previewAspectRatio, previewHeightMax);
            
            // request update to panel height
            root.dispatch('DID_UPDATE_PANEL_HEIGHT', {
                id: item.id,
                height: previewHeight
            });
        };

        const didResizeView = ({ root }) => {
            // actions in next write operation
            root.ref.shouldRescale = true;
        };

        const didUpdateItemMetadata = ({ root, action }) => {

            if (action.change.key !== 'crop') return;

            // actions in next write operation
            root.ref.shouldRescale = true;
        };

        const didCalculatePreviewSize = ({ root, action }) => {

            // remember dimensions
            root.ref.imageWidth = action.width;
            root.ref.imageHeight = action.height;

            // actions in next write operation
            root.ref.shouldRescale = true;
            root.ref.shouldDrawPreview = true;

            // as image load could take a while and fire when draw loop is resting we need to give it a kick
            root.dispatch('KICK');
        };

        // start writing
        view.registerWriter(
            createRoute({
                DID_RESIZE_ROOT: didResizeView,
                DID_STOP_RESIZE: didResizeView,
                DID_LOAD_ITEM: didLoadItem,
                DID_IMAGE_PREVIEW_CALCULATE_SIZE: didCalculatePreviewSize,
                DID_UPDATE_ITEM_METADATA: didUpdateItemMetadata
            }, ({ root, props }) => {

                // no preview view attached
                if (!root.ref.imagePreview) return;

                // don't do anything while hidden
                if (root.rect.element.hidden) return;

                // resize the item panel
                if (root.ref.shouldRescale) {
                    rescaleItem(root, props);
                    root.ref.shouldRescale = false;
                }

                if (root.ref.shouldDrawPreview) {
                    // queue till next frame so we're sure the height has been applied this forces the draw image call inside the wrapper view to use the correct height
                    requestAnimationFrame(() => {
                        root.dispatch('DID_FINISH_CALCULATE_PREVIEWSIZE', { id: props.id });
                    });
                    root.ref.shouldDrawPreview = false;
                }

            })
        );
        
    });

    // expose plugin
    return {
        options: {
            // Enable or disable image preview
            allowImagePreview: [true, Type.BOOLEAN],

            // filters file items to determine which are shown as preview
            imagePreviewFilterItem: [() => true, Type.FUNCTION],

            // Fixed preview height
            imagePreviewHeight: [null, Type.INT],

            // Min image height
            imagePreviewMinHeight: [44, Type.INT],

            // Max image height
            imagePreviewMaxHeight: [256, Type.INT],

            // Max size of preview file for when createImageBitmap is not supported
            imagePreviewMaxFileSize: [null, Type.INT],

            // The amount of extra pixels added to the image preview to allow comfortable zooming 
            imagePreviewZoomFactor: [2, Type.INT],

            // Should we upscale small images to fit the max bounding box of the preview area
            imagePreviewUpscale: [false, Type.BOOLEAN],

            // Max size of preview file that we allow to try to instant preview if createImageBitmap is not supported, else image is queued for loading
            imagePreviewMaxInstantPreviewFileSize: [1000000, Type.INT],

            // Style of the transparancy indicator used behind images
            imagePreviewTransparencyIndicator: [null, Type.STRING],

            // Enables or disables reading average image color
            imagePreviewCalculateAverageImageColor: [false, Type.BOOLEAN],

            // Enables or disables the previewing of markup
            imagePreviewMarkupShow: [true, Type.BOOLEAN],

            // Allows filtering of markup to only show certain shapes
            imagePreviewMarkupFilter: [() => true, Type.FUNCTION]
        }
    };
};

// fire pluginloaded event if running in browser, this allows registering the plugin when using async script tags
const isBrowser = typeof window !== 'undefined' && typeof window.document !== 'undefined';
if (isBrowser) {
    document.dispatchEvent(new CustomEvent('FilePond:pluginloaded', { detail: plugin }));
}

export default plugin;