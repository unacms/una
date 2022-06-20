import{Node, mergeAttributes, textblockTypeInputRule, nodeInputRule} from 'https://esm.sh/@tiptap/core';
var inputRegex = /(!\[(.+|:?)]\((\S+)(?:(?:\s+)["'](\S+)["'])?\))$/;
import{Plugin,PluginKey,TextSelection} from 'https://esm.sh/v85/prosemirror-state@1.4.0/es2022/prosemirror-state.js';
var a = Node.create({
    name: 'embed',
    addOptions: function () {
        return {
            inline: false,
            allowBase64: false,
            HTMLAttributes: {}
        };
    },
    inline: function () {
        return this.options.inline;
    },
    group: function () {
        return this.options.inline ? 'inline' : 'block';
    },
    draggable: true,
    addAttributes: function () {
        return {
            class: {
                default: null
            },
            source: {
                default: null
            },
            title: {
                default: null
            }
        };
    },
    parseHTML: function () {
        return [
            {
                tag: this.options.allowBase64
                    ? 'div[class]'
                    : 'div[class]:not([src^="data:"])'
            },
        ];
    },
    renderHTML: function (_a) {
        var HTMLAttributes = _a.HTMLAttributes;
        return ['div', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes)];
    },
    addCommands: function () {
        var _this = this;
        return {
            setEmbed: function (options) { return function (_a) {
                var commands = _a.commands;
                return commands.insertContent({
                    type: _this.name,
                    attrs: options
                });
            }; }
        };
    },
    addInputRules: function () {
        return [
            nodeInputRule({
                find: inputRegex,
                type: this.type,
                getAttributes: function (match) {
                    var className = match[2], source = match[3], title = match[4];
                    return { class: className, source: source, title: title };
                }
            }),
        ];
    }
});
export{a as Embed,a as default};
