import { isPreviewableVideo } from './../utils/isPreviewableVideo';
import { isPreviewableAudio } from './../utils/isPreviewableAudio';
import AudioPlayer from './../components/audioPlayer';

export const createMediaView = _ =>
    _.utils.createView({
        name: 'media-preview',
        tag: 'div',
        ignoreRect: true,
        create: ({ root, props }) => {
            const { id } = props;

            // get item
            const item = root.query('GET_ITEM', { id: props.id });
            let tagName = isPreviewableAudio(item.file) ? 'audio' : 'video';

            root.ref.media = document.createElement(tagName);
            root.ref.media.setAttribute('controls', true);
            root.element.appendChild(root.ref.media);

            if (isPreviewableAudio(item.file)) {
                let docfrag = document.createDocumentFragment();
                    root.ref.audio = [];
                    root.ref.audio.container = document.createElement('div'),
                    root.ref.audio.button = document.createElement('span'),
                    root.ref.audio.timeline = document.createElement('div'),
                    root.ref.audio.playhead = document.createElement('div');

                root.ref.audio.container.className = 'audioplayer';
                root.ref.audio.button.className = 'playpausebtn play';
                root.ref.audio.timeline.className = 'timeline';
                root.ref.audio.playhead.className = 'playhead';

                root.ref.audio.timeline.appendChild(root.ref.audio.playhead);
                root.ref.audio.container.appendChild(root.ref.audio.button);
                root.ref.audio.container.appendChild(root.ref.audio.timeline);
                docfrag.appendChild(root.ref.audio.container);

                root.element.appendChild(docfrag);
            }
        },
        write: _.utils.createRoute({
            DID_MEDIA_PREVIEW_LOAD: ({ root, props }) => {
                const {id} = props;

                // get item
                const item = root.query('GET_ITEM', {id: props.id});
                if (!item) return;

                let URL = window.URL || window.webkitURL;
                let blob = new Blob([item.file], {type: item.file.type});

                root.ref.media.type = item.file.type;
                root.ref.media.src = URL.createObjectURL(blob);

                // create audio player in case of audio file
                if (isPreviewableAudio(item.file)) {
                    new AudioPlayer(root.ref.media, root.ref.audio);
                }

                // determine dimensions and update panel accordingly
                root.ref.media.addEventListener('loadeddata', () => {
                    let height = 75; // default height for audio panel
                    if (isPreviewableVideo(item.file)) {
                        let containerWidth = root.ref.media.offsetWidth;
                        let factor = root.ref.media.videoWidth / containerWidth;
                        height = root.ref.media.videoHeight / factor;
                    }

                    root.dispatch('DID_UPDATE_PANEL_HEIGHT', {
                        id: props.id,
                        height: height
                    });
                }, false);
            }
        })
    });
