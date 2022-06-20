import{Extension} from 'https://esm.sh/@tiptap/core';
import{Plugin, PluginKey} from 'https://esm.sh/v85/prosemirror-state@1.4.0/es2022/prosemirror-state.js';

var a = Extension.create({
    name: 'eventHandler',
    addOptions: function () {
        return {
            uploaderUrl: ""
        };
    },
    addProseMirrorPlugins: function () {
        var _this = this;
        console.log(_this.options.uploaderUrl)
        return [
            new Plugin({
                key: new PluginKey('eventHandler'),
                uploaderUrl: _this.options.uploaderUrl,
                props: {
                    handlePaste: function (view, event, slice) { 
                        const dT = event.clipboardData || window.clipboardData;
                        const images = dT.files;
                        if (images.length === 0)
                            return
                        event.preventDefault();
                        
                        for (var i = 0; i < images.length; i++) {
      
                            bx_ex_editor_upload(_this.editor, this.spec.uploaderUrl, images[i])
                           
                        }
                    }
                }
            }),
        ];
    }
});

export{a as EventHandler ,a as default};