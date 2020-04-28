# Media Preview plugin for FilePond

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/nielsboogaard/filepond-plugin-media-preview/blob/master/LICENSE)
[![npm version](https://badge.fury.io/js/filepond-plugin-media-preview.svg)](https://badge.fury.io/js/filepond-plugin-media-preview)

The Media Preview plugin will kick in automatically when the uploaded file is of type video or audio and render a preview player inside the file item.

<img src="https://github.com/nielsboogaard/filepond-plugin-media-preview/blob/master/demo.gif?raw=true" width="508" alt=""/>


## Quick Start

Install using npm:

```bash
npm install filepond-plugin-media-preview
```

Then import in your project:

```js
import * as FilePond from 'filepond';
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview';
```

Register the plugin:
```js
FilePond.registerPlugin(FilePondPluginMediaPreview);
```
Create a new FilePond instance as normal.
```js
const pond = FilePond.create({
    name: 'filepond'
});

// Add it to the DOM
document.body.appendChild(pond.element);
```
 The preview will become active when uploading an video or audio file.


## Demo
[View the demo](https://nielsboogaard.github.io/filepond-plugin-media-preview/)