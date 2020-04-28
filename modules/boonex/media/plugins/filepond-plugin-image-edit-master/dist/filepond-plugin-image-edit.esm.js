/*!
 * FilePondPluginImageEdit 1.6.0
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */

/* eslint-disable */

const isPreviewableImage = file => /^image/.test(file.type);

/**
 * Image Edit Proxy Plugin
 */
const plugin = _ => {
  const { addFilter, utils, views } = _;
  const { Type, createRoute, createItemAPI = item => item } = utils;
  const { fileActionButton } = views;

  addFilter(
    'SHOULD_REMOVE_ON_REVERT',
    (shouldRemove, { item, query }) =>
      new Promise(resolve => {
        const { file } = item;

        // if this file is editable it shouldn't be removed immidiately even when instant uploading
        const canEdit =
          query('GET_ALLOW_IMAGE_EDIT') &&
          query('GET_IMAGE_EDIT_ALLOW_EDIT') &&
          isPreviewableImage(file);

        // if the file cannot be edited it should be removed on revert
        resolve(!canEdit);
      })
  );

  // open editor when loading a new item
  addFilter(
    'DID_LOAD_ITEM',
    (item, { query, dispatch }) =>
      new Promise((resolve, reject) => {
        // if is temp or local file
        if (item.origin > 1) {
          resolve(item);
          return;
        }

        // get file reference
        const { file } = item;
        if (
          !query('GET_ALLOW_IMAGE_EDIT') ||
          !query('GET_IMAGE_EDIT_INSTANT_EDIT')
        ) {
          resolve(item);
          return;
        }

        // exit if this is not an image
        if (!isPreviewableImage(file)) {
          resolve(item);
          return;
        }

        const createEditorResponseHandler = (
          item,
          resolve,
          reject
        ) => userDidConfirm => {
          // remove item
          editRequestQueue.shift();

          // handle item
          if (userDidConfirm) {
            resolve(item);
          } else {
            reject(item);
          }

          // TODO: Fix, should not be needed to kick the internal loop in case no processes are running
          dispatch('KICK');

          // handle next item!
          requestEdit();
        };

        const requestEdit = () => {
          if (!editRequestQueue.length) return;

          const { item, resolve, reject } = editRequestQueue[0];

          dispatch('EDIT_ITEM', {
            id: item.id,
            handleEditorResponse: createEditorResponseHandler(
              item,
              resolve,
              reject
            )
          });
        };

        queueEditRequest({ item, resolve, reject });

        if (editRequestQueue.length === 1) {
          requestEdit();
        }
      })
  );

  // extend item methods
  addFilter('DID_CREATE_ITEM', (item, { query, dispatch }) => {
    item.extend('edit', () => {
      dispatch('EDIT_ITEM', { id: item.id });
    });
  });

  const editRequestQueue = [];
  const queueEditRequest = editRequest => {
    editRequestQueue.push(editRequest);
    return editRequest;
  };

  // called for each view that is created right after the 'create' method
  addFilter('CREATE_VIEW', viewAPI => {
    // get reference to created view
    const { is, view, query } = viewAPI;

    if (!query('GET_ALLOW_IMAGE_EDIT')) return;

    const canShowImagePreview = query('GET_ALLOW_IMAGE_PREVIEW');

    // only run for either the file or the file info panel
    const shouldExtendView =
      (is('file-info') && !canShowImagePreview) ||
      (is('file') && canShowImagePreview);

    if (!shouldExtendView) return;

    // no editor defined, then exit
    const editor = query('GET_IMAGE_EDIT_EDITOR');
    if (!editor) return;

    // set default FilePond options and add bridge once
    if (!editor.filepondCallbackBridge) {
      editor.outputData = true;
      editor.outputFile = false;
      editor.filepondCallbackBridge = {
        onconfirm: editor.onconfirm || (() => {}),
        oncancel: editor.oncancel || (() => {})
      };
    }

    // opens the editor, if it does not already exist, it creates the editor
    const openEditor = ({ root, props, action }) => {
      const { id } = props;
      const { handleEditorResponse } = action;

      // update editor props that could have changed
      editor.cropAspectRatio =
        root.query('GET_IMAGE_CROP_ASPECT_RATIO') || editor.cropAspectRatio;
      editor.outputCanvasBackgroundColor =
        root.query('GET_IMAGE_TRANSFORM_CANVAS_BACKGROUND_COLOR') ||
        editor.outputCanvasBackgroundColor;

      // get item
      const item = root.query('GET_ITEM', id);
      if (!item) return;

      // file to open
      const file = item.file;

      // crop data to pass to editor
      const crop = item.getMetadata('crop');
      const cropDefault = {
        center: {
          x: 0.5,
          y: 0.5
        },
        flip: {
          horizontal: false,
          vertical: false
        },
        zoom: 1,
        rotation: 0,
        aspectRatio: null
      };

      // size data to pass to editor
      const resize = item.getMetadata('resize');

      // filter and color data to pass to editor
      const filter = item.getMetadata('filter') || null;
      const filters = item.getMetadata('filters') || null;
      const colors = item.getMetadata('colors') || null;
      const markup = item.getMetadata('markup') || null;

      // build parameters object
      const imageParameters = {
        crop: crop || cropDefault,
        size: resize
          ? {
              upscale: resize.upscale,
              mode: resize.mode,
              width: resize.size.width,
              height: resize.size.height
            }
          : null,
        filter: filters ? filters.id || filters.matrix : filter,
        color: colors,
        markup
      };

      editor.onconfirm = ({ data }) => {
        const { crop, size, filter, color, colorMatrix, markup } = data;

        // create new metadata object
        const metadata = {};

        // append crop data
        if (crop) {
          metadata.crop = crop;
        }

        // append size data
        if (size) {
          const initialSize = (item.getMetadata('resize') || {}).size;
          const targetSize = {
            width: size.width,
            height: size.height
          };

          if (!(targetSize.width && targetSize.height) && initialSize) {
            targetSize.width = initialSize.width;
            targetSize.height = initialSize.height;
          }

          if (targetSize.width || targetSize.height) {
            metadata.resize = {
              upscale: size.upscale,
              mode: size.mode,
              size: targetSize
            };
          }
        }

        if (markup) {
          metadata.markup = markup;
        }

        // set filters and colors so we can restore them when re-editing the image
        metadata.colors = color;
        metadata.filters = filter;

        // set merged color matrix to use in preview plugin
        metadata.filter = colorMatrix;

        // update crop metadata
        item.setMetadata(metadata);

        // call
        editor.filepondCallbackBridge.onconfirm(data, createItemAPI(item));

        // used in instant edit mode
        if (!handleEditorResponse) return;
        editor.onclose = () => {
          handleEditorResponse(true);
          editor.onclose = null;
        };
      };

      editor.oncancel = () => {
        // call
        editor.filepondCallbackBridge.oncancel(createItemAPI(item));

        // used in instant edit mode
        if (!handleEditorResponse) return;
        editor.onclose = () => {
          handleEditorResponse(false);
          editor.onclose = null;
        };
      };

      editor.open(file, imageParameters);
    };

    /**
     * Image Preview related
     */

    // create the image edit plugin, but only do so if the item is an image
    const didLoadItem = ({ root, props }) => {
      if (!query('GET_IMAGE_EDIT_ALLOW_EDIT')) return;

      const { id } = props;

      // try to access item
      const item = query('GET_ITEM', id);
      if (!item) return;

      // get the file object
      const file = item.file;

      // exit if this is not an image
      if (!isPreviewableImage(file)) return;

      // handle interactions
      root.ref.handleEdit = e => {
        e.stopPropagation();
        root.dispatch('EDIT_ITEM', { id });
      };

      if (canShowImagePreview) {
        // add edit button to preview
        const buttonView = view.createChildView(fileActionButton, {
          label: 'edit',
          icon: query('GET_IMAGE_EDIT_ICON_EDIT'),
          opacity: 0
        });

        // edit item classname
        buttonView.element.classList.add('filepond--action-edit-item');
        buttonView.element.dataset.align = query(
          'GET_STYLE_IMAGE_EDIT_BUTTON_EDIT_ITEM_POSITION'
        );
        buttonView.on('click', root.ref.handleEdit);

        root.ref.buttonEditItem = view.appendChildView(buttonView);
      } else {
        // view is file info
        const filenameElement = view.element.querySelector(
          '.filepond--file-info-main'
        );
        const editButton = document.createElement('button');
        editButton.className = 'filepond--action-edit-item-alt';
        editButton.innerHTML =
          query('GET_IMAGE_EDIT_ICON_EDIT') + '<span>edit</span>';
        editButton.addEventListener('click', root.ref.handleEdit);
        filenameElement.appendChild(editButton);

        root.ref.editButton = editButton;
      }
    };

    view.registerDestroyer(({ root }) => {
      if (root.ref.buttonEditItem) {
        root.ref.buttonEditItem.off('click', root.ref.handleEdit);
      }
      if (root.ref.editButton) {
        root.ref.editButton.removeEventListener('click', root.ref.handleEdit);
      }
    });

    const routes = {
      EDIT_ITEM: openEditor,
      DID_LOAD_ITEM: didLoadItem
    };

    if (canShowImagePreview) {
      // view is file
      const didPreviewUpdate = ({ root }) => {
        if (!root.ref.buttonEditItem) return;
        root.ref.buttonEditItem.opacity = 1;
      };

      routes.DID_IMAGE_PREVIEW_SHOW = didPreviewUpdate;
    } else {
    }

    // start writing
    view.registerWriter(createRoute(routes));
  });

  // Expose plugin options
  return {
    options: {
      // enable or disable image editing
      allowImageEdit: [true, Type.BOOLEAN],

      // location of processing button
      styleImageEditButtonEditItemPosition: ['bottom center', Type.STRING],

      // open editor when image is dropped
      imageEditInstantEdit: [false, Type.BOOLEAN],

      // allow editing
      imageEditAllowEdit: [true, Type.BOOLEAN],

      // the icon to use for the edit button
      imageEditIconEdit: [
        '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M8.5 17h1.586l7-7L15.5 8.414l-7 7V17zm-1.707-2.707l8-8a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414l-8 8A1 1 0 0 1 10.5 19h-3a1 1 0 0 1-1-1v-3a1 1 0 0 1 .293-.707z" fill="currentColor" fill-rule="nonzero"/></svg>',
        Type.STRING
      ],

      // editor object
      imageEditEditor: [null, Type.OBJECT]
    }
  };
};

// fire pluginloaded event if running in browser, this allows registering the plugin when using async script tags
const isBrowser =
  typeof window !== 'undefined' && typeof window.document !== 'undefined';
if (isBrowser) {
  document.dispatchEvent(
    new CustomEvent('FilePond:pluginloaded', { detail: plugin })
  );
}

export default plugin;
