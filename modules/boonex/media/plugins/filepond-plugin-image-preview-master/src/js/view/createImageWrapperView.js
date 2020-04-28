import { createImageView } from './createImageView';
import { createImageOverlayView } from './createImageOverlayView';
import { BitmapWorker } from '../utils/BitmapWorker';
import { ColorMatrixWorker } from '../utils/ColorMatrixWorker';
import { getImageSize } from '../utils/getImageSize';
import { createPreviewImage } from '../utils/createPreviewImage';
import { isBitmap } from '../utils/isBitmap';
import { calculateAverageColor } from '../utils/calculateAverageColor';
import { cloneCanvas } from '../utils/cloneCanvas';
import { cloneImageData } from '../utils/cloneImageData';

const loadImage = (url) => new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = () => {
        resolve(img);
    };
    img.onerror = e => {
        reject(e);
    };
    img.src = url;
});


export const createImageWrapperView = _ => {

    // create overlay view
    const OverlayView = createImageOverlayView(_);

    const ImageView = createImageView(_);

    const { createWorker } = _.utils;

    const applyFilter = (root, filter, target) => new Promise(resolve => {

        // will store image data for future filter updates
        if (!root.ref.imageData) {
            root.ref.imageData = target.getContext('2d').getImageData(0, 0, target.width, target.height);
        }

        // get image data reference
        const imageData = cloneImageData(root.ref.imageData);

        if (!filter || filter.length !== 20) {
            target.getContext('2d').putImageData(imageData, 0, 0);
            return resolve();
        }

        const worker = createWorker(ColorMatrixWorker);
        worker.post(
            {
                imageData,
                colorMatrix: filter
            },
            response => {

                // apply filtered colors
                target.getContext('2d').putImageData(response, 0, 0);

                // stop worker
                worker.terminate();

                // done!
                resolve();
            },
            [imageData.data.buffer]
        );
        
    });

    const removeImageView = (root, imageView) => {
        root.removeChildView(imageView);
        imageView.image.width = 1;
        imageView.image.height = 1;
        imageView._destroy();
    }

    // remove an image
    const shiftImage = ({ root }) => {
        const imageView = root.ref.images.shift();
        imageView.opacity = 0;
        imageView.translateY = -15;
        root.ref.imageViewBin.push(imageView);
        return imageView;
    };

    // add new image
    const pushImage = ({ root, props, image }) => {
        
        const id = props.id;
        const item = root.query('GET_ITEM', { id });
        if (!item) return;

        const crop = item.getMetadata('crop') || {
            center: {
                x:.5,
                y:.5
            },
            flip: {
                horizontal: false,
                vertical: false
            },
            zoom: 1,
            rotation: 0,
            aspectRatio: null
        };

        const background = root.query('GET_IMAGE_TRANSFORM_CANVAS_BACKGROUND_COLOR');

        let markup;
        let resize;
        let dirty = false;
        if (root.query('GET_IMAGE_PREVIEW_MARKUP_SHOW')) {
            markup = item.getMetadata('markup') || [];
            resize = item.getMetadata('resize');
            dirty = true;
        }

        // append image presenter
        const imageView = root.appendChildView(
            root.createChildView(ImageView, {
                id, 
                image,
                crop,
                resize,
                markup,
                dirty,
                background,
                opacity: 0,
                scaleX: 1.15,
                scaleY: 1.15,
                translateY: 15
            }),
            root.childViews.length
        );
        root.ref.images.push(imageView);

        // reveal the preview image
        imageView.opacity = 1;
        imageView.scaleX = 1;
        imageView.scaleY = 1;
        imageView.translateY = 0;

        // the preview is now ready to be drawn
        setTimeout(() => {
            root.dispatch('DID_IMAGE_PREVIEW_SHOW', { id });
        }, 250);
    }

    const updateImage = ({ root, props }) => {
        const item = root.query('GET_ITEM', { id: props.id });
        if (!item) return;
        const imageView = root.ref.images[root.ref.images.length-1];
        imageView.crop = item.getMetadata('crop');
        imageView.background = root.query('GET_IMAGE_TRANSFORM_CANVAS_BACKGROUND_COLOR');
        if (root.query('GET_IMAGE_PREVIEW_MARKUP_SHOW')) {
            imageView.dirty = true;
            imageView.resize = item.getMetadata('resize');
            imageView.markup = item.getMetadata('markup');
        }
    }

    // replace image preview
    const didUpdateItemMetadata = ({ root, props, action }) => {

        // only filter and crop trigger redraw
        if (!/crop|filter|markup|resize/.test(action.change.key)) return;

        // no images to update, exit
        if (!root.ref.images.length) return;

        // no item found, exit
        const item = root.query('GET_ITEM', { id: props.id });
        if (!item) return;

        // for now, update existing image when filtering
        if (/filter/.test(action.change.key)) {
            const imageView = root.ref.images[root.ref.images.length-1];
            applyFilter(root, action.change.value, imageView.image);
            return;
        }

        if (/crop|markup|resize/.test(action.change.key)) {

            const crop = item.getMetadata('crop');
            const image = root.ref.images[root.ref.images.length-1];
            
            // if aspect ratio has changed, we need to create a new image 
            if (Math.abs(crop.aspectRatio - image.crop.aspectRatio) > .00001) {
                const imageView = shiftImage({ root });
                pushImage({ root, props, image: cloneCanvas(imageView.image) });
            }
            // if not, we can update the current image
            else {
                updateImage({ root, props });
            }
        }
    };


    const canCreateImageBitmap = file => {

        // Firefox versions before 58 will freeze when running createImageBitmap
        // in a Web Worker so we detect those versions and return false for support
        const userAgent = window.navigator.userAgent;
        const isFirefox = userAgent.match(/Firefox\/([0-9]+)\./);
        const firefoxVersion = isFirefox ? parseInt(isFirefox[1]) : null;
        if (firefoxVersion <= 58) return false;
        
        return 'createImageBitmap' in window && isBitmap(file);
    }
    
    


    /**
     * Write handler for when preview container has been created
     */
    const didCreatePreviewContainer = ({ root, props }) => {

        const { id } = props;

        // we need to get the file data to determine the eventual image size
        const item = root.query('GET_ITEM', id);
        if (!item) return;

        // get url to file (we'll revoke it later on when done)
        const fileURL = URL.createObjectURL(item.file);

        // determine image size of this item
        getImageSize(fileURL, (width, height) => {
            
            // we can now scale the panel to the final size
            root.dispatch('DID_IMAGE_PREVIEW_CALCULATE_SIZE', {
                id,
                width,
                height
            });

        });
    };

    const drawPreview = ({ root, props }) => {

        const { id } = props;

        // we need to get the file data to determine the eventual image size
        const item = root.query('GET_ITEM', id);
        if (!item) return;

        // get url to file (we'll revoke it later on when done)
        const fileURL = URL.createObjectURL(item.file);

        // fallback
        const loadPreviewFallback = () => {
            // let's scale the image in the main thread :(
            loadImage(fileURL).then(previewImageLoaded);
        };

        // image is now ready
        const previewImageLoaded = imageData => {

            // the file url is no longer needed
            URL.revokeObjectURL(fileURL);

            // draw the scaled down version here and use that as source so bitmapdata can be closed
            // orientation info
            const exif = item.getMetadata('exif') || {};
            const orientation = exif.orientation || -1; 

            // get width and height from action, and swap if orientation is incorrect
            let { width, height } = imageData;
            if (orientation >= 5 && orientation <= 8) {
                [width, height] = [height, width];
            }

            // scale canvas based on pixel density
            // we multiply by .75 as that creates smaller but still clear images on screens with high res displays
            const pixelDensityFactor = Math.max(1, window.devicePixelRatio * .75);

            // we want as much pixels to work with as possible,
            // this multiplies the minimum image resolution,
            // so when zooming in it doesn't get too blurry
            const zoomFactor = root.query('GET_IMAGE_PREVIEW_ZOOM_FACTOR');

            // imaeg scale factor
            const scaleFactor = zoomFactor * pixelDensityFactor;

            // calculate scaled preview image size
            const previewImageRatio = height / width;

            // calculate image preview height and width
            const previewContainerWidth = root.rect.element.width;
            const previewContainerHeight = root.rect.element.height;

            let imageWidth = previewContainerWidth;
            let imageHeight = imageWidth * previewImageRatio;

            if (previewImageRatio > 1) {
                imageWidth = Math.min(width, previewContainerWidth * scaleFactor);
                imageHeight = imageWidth * previewImageRatio;
            }
            else {
                imageHeight = Math.min(height, previewContainerHeight * scaleFactor);
                imageWidth = imageHeight / previewImageRatio;
            }

            // transfer to image tag so no canvas memory wasted on iOS
            const previewImage = createPreviewImage(imageData, imageWidth, imageHeight, orientation);

            // done
            const done = () => {

                // calculate average image color, disabled for now
                const averageColor = root.query('GET_IMAGE_PREVIEW_CALCULATE_AVERAGE_IMAGE_COLOR') ? calculateAverageColor(data) : null;
                item.setMetadata('color', averageColor, true);

                // data has been transferred to canvas ( if was ImageBitmap )
                if ('close' in imageData) {
                    imageData.close();
                }

                // show the overlay
                root.ref.overlayShadow.opacity = 1;

                // create the first image
                pushImage({ root, props, image: previewImage });
            }

            // apply filter
            const filter = item.getMetadata('filter');
            if (filter) {
                applyFilter(root, filter, previewImage).then(done);
            }
            else {
                done();
            }

        };

        // if we support scaling using createImageBitmap we use a worker
        if (canCreateImageBitmap(item.file)) {

            // let's scale the image in a worker
            const worker = createWorker(BitmapWorker);

            worker.post(
                {
                    file: item.file
                },
                imageBitmap => {

                    // destroy worker
                    worker.terminate();

                    // no bitmap returned, must be something wrong,
                    // try the oldschool way
                    if (!imageBitmap) {
                        loadPreviewFallback();
                        return;
                    }

                    // yay we got our bitmap, let's continue showing the preview
                    previewImageLoaded(imageBitmap);
                }
            );
        } else {
            // create fallback preview
            loadPreviewFallback();
        }
    }

    /**
     * Write handler for when the preview image is ready to be animated
     */
    const didDrawPreview = ({ root }) => {
        // get last added image
        const image = root.ref.images[root.ref.images.length - 1];
        image.translateY = 0;
        image.scaleX = 1.0;
        image.scaleY = 1.0;
        image.opacity = 1;
    };
    

    /**
     * Write handler for when the preview has been loaded
     */
    const restoreOverlay = ({ root }) => {
        root.ref.overlayShadow.opacity = 1;
        root.ref.overlayError.opacity = 0;
        root.ref.overlaySuccess.opacity = 0;
    };

    const didThrowError = ({ root }) => {
        root.ref.overlayShadow.opacity = 0.25;
        root.ref.overlayError.opacity = 1;
    };

    const didCompleteProcessing = ({ root }) => {
        root.ref.overlayShadow.opacity = 0.25;
        root.ref.overlaySuccess.opacity = 1;
    };

    /**
     * Constructor
     */
    const create = ({ root }) => {
        
        // image view
        root.ref.images = [];

        // the preview image data (we need this to filter the image)
        root.ref.imageData = null;

        // image bin
        root.ref.imageViewBin = [];

        // image overlays
        root.ref.overlayShadow = root.appendChildView(
            root.createChildView(OverlayView, {
                opacity: 0,
                status: 'idle'
            })
        );

        root.ref.overlaySuccess = root.appendChildView(
            root.createChildView(OverlayView, {
                opacity: 0,
                status: 'success'
            })
        );

        root.ref.overlayError = root.appendChildView(
            root.createChildView(OverlayView, {
                opacity: 0,
                status: 'failure'
            })
        );

    };

    return _.utils.createView({
        name: 'image-preview-wrapper',
        create,
        styles: [
            'height'
        ],
        apis: [
            'height'
        ],
        destroy: ({ root }) => {
            // we resize the image so memory on iOS 12 is released more quickly (it seems)
            root.ref.images.forEach(imageView => {
                imageView.image.width = 1;
                imageView.image.height = 1;
            });
        },
        didWriteView: ({ root }) => {
            root.ref.images.forEach(imageView => {
                imageView.dirty = false
            });
        },
        write: _.utils.createRoute({
            // image preview stated
            DID_IMAGE_PREVIEW_DRAW: didDrawPreview,
            DID_IMAGE_PREVIEW_CONTAINER_CREATE: didCreatePreviewContainer,
            DID_FINISH_CALCULATE_PREVIEWSIZE: drawPreview,
            DID_UPDATE_ITEM_METADATA: didUpdateItemMetadata,

            // file states
            DID_THROW_ITEM_LOAD_ERROR: didThrowError,
            DID_THROW_ITEM_PROCESSING_ERROR: didThrowError,
            DID_THROW_ITEM_INVALID: didThrowError,
            DID_COMPLETE_ITEM_PROCESSING: didCompleteProcessing,
            DID_START_ITEM_PROCESSING: restoreOverlay,
            DID_REVERT_ITEM_PROCESSING: restoreOverlay
        }, ({ root }) => {

            // views on death row
            const viewsToRemove = root.ref.imageViewBin.filter(imageView => imageView.opacity === 0);
            
            // views to retain
            root.ref.imageViewBin = root.ref.imageViewBin.filter(imageView => imageView.opacity > 0);
            
            // remove these views
            viewsToRemove.forEach(imageView => removeImageView(root, imageView));
            viewsToRemove.length = 0;
        })
    });
};
