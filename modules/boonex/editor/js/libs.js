import {Editor, Mark, mergeAttributes,Node } from 'https://esm.sh/@tiptap/core'
import Document from 'https://esm.sh/@tiptap/extension-document'
import Paragraph from 'https://esm.sh/@tiptap/extension-paragraph'
import Text from 'https://esm.sh/@tiptap/extension-text'
import Bold from 'https://esm.sh/@tiptap/extension-bold'
import Italic from 'https://esm.sh/@tiptap/extension-italic'
import Strike from 'https://esm.sh/@tiptap/extension-strike'
import Underline from 'https://esm.sh/@tiptap/extension-underline'
import Subscript from 'https://esm.sh/@tiptap/extension-subscript'
import Superscript from 'https://esm.sh/@tiptap/extension-superscript'
import Blockquote from 'https://esm.sh/@tiptap/extension-blockquote'
import Code from 'https://esm.sh/@tiptap/extension-code'
import Highlight from 'https://esm.sh/@tiptap/extension-highlight'

import ListItem from 'https://esm.sh/@tiptap/extension-list-item'
import OrderedList from 'https://esm.sh/@tiptap/extension-ordered-list'
import BulletList from 'https://esm.sh/@tiptap/extension-bullet-list'
import CodeBlock from 'https://esm.sh/@tiptap/extension-code-block'

import TextAlign from 'https://esm.sh/@tiptap/extension-text-align'
import Heading from 'https://esm.sh/@tiptap/extension-heading'

import Link from 'https://esm.sh/@tiptap/extension-link'
import Image from 'https://esm.sh/@tiptap/extension-image'

import Mention from 'https://esm.sh/@tiptap/extension-mention'

import Embed from '/modules/boonex/editor/js/embed.js'
import EventHandler  from '/modules/boonex/editor/js/eventHandler.js'
//import Image from '/modules/boonex/editor/js/image.js'


export { Editor, Document, Paragraph, Text, Bold, Italic, Strike, Underline, Subscript, Superscript, Blockquote, BulletList, ListItem, Code, Highlight, CodeBlock, OrderedList, TextAlign, Heading, Link, Image, Mention, Mark, mergeAttributes, Embed, EventHandler };