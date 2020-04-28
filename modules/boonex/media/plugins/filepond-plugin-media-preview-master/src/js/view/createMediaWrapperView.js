import { createMediaView } from './createMediaView';

export const createMediaWrapperView = _ => {

    /**
     * Write handler for when preview container has been created
     */
    const didCreatePreviewContainer = ({ root, props }) => {
        const { id } = props;
        const item = root.query('GET_ITEM', id);
        if (!item) return;

        // the preview is now ready to be drawn
        root.dispatch('DID_MEDIA_PREVIEW_LOAD', {
            id
        });
    };

    /**
     * Constructor
     */
    const create = ({ root, props }) => {
        const media = createMediaView(_);

        // append media presenter
        root.ref.media = root.appendChildView(
            root.createChildView(media, {
                id: props.id
            })
        );
    };

    return _.utils.createView({
        name: 'media-preview-wrapper',
        create,
        write: _.utils.createRoute({
            // media preview stated
            DID_MEDIA_PREVIEW_CONTAINER_CREATE: didCreatePreviewContainer
        })
    });
};
