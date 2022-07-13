class BxEmbedInline {
    static get CSS() {
        return 'bx-embed-link';
    };

    constructor({api}) {
        this.api = api;
        this.button = null;
        this.tag = 'span';

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

        let termWrapper = this.api.selection.findParentTag(this.tag, BxEmbedInline.CSS);
        if (termWrapper) {
          this.unwrap(termWrapper);
        } else {
          this.wrap(range);
        }
    }

    wrap(range) {
        let marker = document.createElement(this.tag);

        marker.classList.add(BxEmbedInline.CSS);

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
        const termTag = this.api.selection.findParentTag(this.tag, BxEmbedInline.CSS);

        this.button.classList.toggle(this.iconClasses.active, !!termTag);
    }

    static get sanitize() {
        return {
          span: function(el) {
              const source = el.getAttribute('source')
              return {
                  class: BxEmbedInline.CSS,
                  source: source
              }
          }
        };
    }
}