class BxMention {

    static get CSS() {
        return 'bx-menthion-link';
    };

    constructor({api}) {
        this.api = api;

        this.button = null;

        this.tag = 'a';

        this.iconClasses = {
          base: this.api.styles.inlineToolButton,
          active: this.api.styles.inlineToolButtonActive
        };
    }

    static get isInline() {
        return true;
    }

    render() {
        return;
    }
    
    surround(range) {
        if (!range) {
            return;
        }

        let termWrapper = this.api.selection.findParentTag(this.tag, BxMention.CSS);

        if (termWrapper) {
            this.unwrap(termWrapper);
        } else {
            this.wrap(range);
        }
    }

    wrap(range) {
        let marker = document.createElement(this.tag);

        marker.classList.add(BxMention.CSS);

        marker.appendChild(range.extractContents());
        range.insertNode(marker);

        this.api.selection.expandToTag(marker);
    }

    unwrap(termWrapper) {
        this.api.selection.expandToTag(termWrapper);

        let sel = window.getSelection();
        let range = sel.getRangeAt(0);

        let unwrappedContent = range.extractContents();

        termWrapper.parentNode.removeChild(termWrapper);

        range.insertNode(unwrappedContent);

        sel.removeAllRanges();
        sel.addRange(range);
    }
    
    checkState() {
        const termTag = this.api.selection.findParentTag(this.tag, BxMention.CSS);

        this.button.classList.toggle(this.iconClasses.active, !!termTag);
    }
    
    get toolboxIcon() {
        return require('./../assets/icon.svg').default;
    }
    
    static get sanitize() {
        return {
            a: function(el) {
                return {
                  class: BxMention.CSS,
                  href: el.getAttribute('href'),
                  'data-profile-id': el.getAttribute('data-profile-id'),
                  title: el.getAttribute('title'),
                  dchar: el.getAttribute('dchar')
                }
            }
        };
    }
}