/*!
 * FilePondPluginMediaPreview 1.0.4
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit undefined for details.
 */

/* eslint-disable */

(function(global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined'
    ? (module.exports = factory())
    : typeof define === 'function' && define.amd
    ? define(factory)
    : ((global = global || self),
      (global.FilePondPluginMediaPreview = factory()));
})(this, function() {
  'use strict';

  const isPreviewableVideo = file => /^video/.test(file.type);

  const isPreviewableAudio = file => /^audio/.test(file.type);

  ('use strict');

  class AudioPlayer {
    constructor(mediaEl, audioElems) {
      this.mediaEl = mediaEl;
      this.audioElems = audioElems;
      this.onplayhead = false;
      this.duration = 0;
      this.timelineWidth =
        this.audioElems.timeline.offsetWidth -
        this.audioElems.playhead.offsetWidth;
      this.moveplayheadFn = this.moveplayhead.bind(this);
      this.registerListeners();
    }

    registerListeners() {
      this.mediaEl.addEventListener(
        'timeupdate',
        this.timeUpdate.bind(this),
        false
      );
      this.mediaEl.addEventListener(
        'canplaythrough',
        () => (this.duration = this.mediaEl.duration),
        false
      );
      this.audioElems.timeline.addEventListener(
        'click',
        this.timelineClicked.bind(this),
        false
      );
      this.audioElems.button.addEventListener('click', this.play.bind(this));
      this.audioElems.playhead.addEventListener(
        'mousedown',
        this.mouseDown.bind(this),
        false
      );
      window.addEventListener('mouseup', this.mouseUp.bind(this), false);
    }

    play() {
      if (this.mediaEl.paused) {
        this.mediaEl.play();
      } else {
        this.mediaEl.pause();
      }

      this.audioElems.button.classList.toggle('play');
      this.audioElems.button.classList.toggle('pause');
    }

    timeUpdate() {
      let playPercent = (this.mediaEl.currentTime / this.duration) * 100;
      this.audioElems.playhead.style.marginLeft = playPercent + '%';

      if (this.mediaEl.currentTime === this.duration) {
        this.audioElems.button.classList.toggle('play');
        this.audioElems.button.classList.toggle('pause');
      }
    }

    moveplayhead(event) {
      let newMargLeft =
        event.clientX - this.getPosition(this.audioElems.timeline);

      if (newMargLeft >= 0 && newMargLeft <= this.timelineWidth) {
        this.audioElems.playhead.style.marginLeft = newMargLeft + 'px';
      }

      if (newMargLeft < 0) {
        this.audioElems.playhead.style.marginLeft = '0px';
      }

      if (newMargLeft > this.timelineWidth) {
        this.audioElems.playhead.style.marginLeft =
          this.timelineWidth - 4 + 'px';
      }
    }

    timelineClicked(event) {
      this.moveplayhead(event);
      this.mediaEl.currentTime = this.duration * this.clickPercent(event);
    }

    mouseDown() {
      this.onplayhead = true;
      window.addEventListener('mousemove', this.moveplayheadFn, true);
      this.mediaEl.removeEventListener(
        'timeupdate',
        this.timeUpdate.bind(this),
        false
      );
    }

    mouseUp(event) {
      window.removeEventListener('mousemove', this.moveplayheadFn, true);

      if (this.onplayhead == true) {
        this.moveplayhead(event); // change current time

        this.mediaEl.currentTime = this.duration * this.clickPercent(event);
        this.mediaEl.addEventListener(
          'timeupdate',
          this.timeUpdate.bind(this),
          false
        );
      }

      this.onplayhead = false;
    }

    clickPercent(event) {
      return (
        (event.clientX - this.getPosition(this.audioElems.timeline)) /
        this.timelineWidth
      );
    }

    getPosition(el) {
      return el.getBoundingClientRect().left;
    }
  }

  const createMediaView = _ =>
    _.utils.createView({
      name: 'media-preview',
      tag: 'div',
      ignoreRect: true,
      create: ({ root, props }) => {
        const { id } = props; // get item

        const item = root.query('GET_ITEM', {
          id: props.id
        });
        let tagName = isPreviewableAudio(item.file) ? 'audio' : 'video';
        root.ref.media = document.createElement(tagName);
        root.ref.media.setAttribute('controls', true);
        root.element.appendChild(root.ref.media);

        if (isPreviewableAudio(item.file)) {
          let docfrag = document.createDocumentFragment();
          root.ref.audio = [];
          (root.ref.audio.container = document.createElement('div')),
            (root.ref.audio.button = document.createElement('span')),
            (root.ref.audio.timeline = document.createElement('div')),
            (root.ref.audio.playhead = document.createElement('div'));
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
          const { id } = props; // get item

          const item = root.query('GET_ITEM', {
            id: props.id
          });
          if (!item) return;
          let URL = window.URL || window.webkitURL;
          let blob = new Blob([item.file], {
            type: item.file.type
          });
          root.ref.media.type = item.file.type;
          root.ref.media.src = URL.createObjectURL(blob); // create audio player in case of audio file

          if (isPreviewableAudio(item.file)) {
            new AudioPlayer(root.ref.media, root.ref.audio);
          } // determine dimensions and update panel accordingly

          root.ref.media.addEventListener(
            'loadeddata',
            () => {
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
            },
            false
          );
        }
      })
    });

  const createMediaWrapperView = _ => {
    /**
     * Write handler for when preview container has been created
     */
    const didCreatePreviewContainer = ({ root, props }) => {
      const { id } = props;
      const item = root.query('GET_ITEM', id);
      if (!item) return; // the preview is now ready to be drawn

      root.dispatch('DID_MEDIA_PREVIEW_LOAD', {
        id
      });
    };
    /**
     * Constructor
     */

    const create = ({ root, props }) => {
      const media = createMediaView(_); // append media presenter

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

  /**
   * Media Preview Plugin
   */

  const plugin = fpAPI => {
    const { addFilter, utils } = fpAPI;
    const { Type, createRoute } = utils;
    const mediaWrapperView = createMediaWrapperView(fpAPI); // called for each view that is created right after the 'create' method

    addFilter('CREATE_VIEW', viewAPI => {
      // get reference to created view
      const { is, view, query } = viewAPI; // only hook up to item view

      if (!is('file')) {
        return;
      } // create the media preview plugin, but only do so if the item is video or audio

      const didLoadItem = ({ root, props }) => {
        const { id } = props;
        const item = query('GET_ITEM', id);

        if (
          !item ||
          item.archived ||
          (!isPreviewableVideo(item.file) && !isPreviewableAudio(item.file))
        ) {
          return;
        } // set preview view

        root.ref.mediaPreview = view.appendChildView(
          view.createChildView(mediaWrapperView, {
            id
          })
        ); // now ready

        root.dispatch('DID_MEDIA_PREVIEW_CONTAINER_CREATE', {
          id
        });
      }; // start writing

      view.registerWriter(
        createRoute(
          {
            DID_LOAD_ITEM: didLoadItem
          },
          ({ root, props }) => {
            const { id } = props;
            const item = query('GET_ITEM', id); // don't do anything while not an video or audio file or hidden

            if (
              (!isPreviewableVideo(item.file) &&
                !isPreviewableAudio(item.file)) ||
              root.rect.element.hidden
            )
              return;
          }
        )
      );
    }); // expose plugin

    return {
      options: {
        allowVideoPreview: [true, Type.BOOLEAN],
        allowAudioPreview: [true, Type.BOOLEAN]
      }
    };
  }; // fire pluginloaded event if running in browser, this allows registering the plugin when using async script tags

  const isBrowser =
    typeof window !== 'undefined' && typeof window.document !== 'undefined';

  if (isBrowser) {
    document.dispatchEvent(
      new CustomEvent('FilePond:pluginloaded', {
        detail: plugin
      })
    );
  }

  return plugin;
});
