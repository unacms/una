function bx_ex_editor_init(oEditor, oParams)
{
	import(sUrlRoot + '/modules/boonex/editor/js/libs.js').then((Module) => {
		$(oParams.selector).after("<div class=\"mt-px flex items-center p-2 bg-white dark:bg-gray-800 shadow ring-1 ring-gray-300 dark:ring-gray-700 rounded-t-md\"><div id='" + oParams.name + "_toolbar' class='editor_toolbar flex space-x-0.5 w-full justify-start items-center flex-wrap'></div></div><div id='" + oParams.name + "' class='bx-def-font-inputs bx-form-input-textarea bx-form-input-html bx-form-input-html-editor mt-px text-gray-700 dark:text-gray-300  w-full p-4  ring-1 ring-gray-300 dark:ring-gray-700 dark:focus:placeholder-700 bg-gray-50 dark:bg-gray-900/50 placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-gray-900 focus:placeholder-gray-300 dark:focus:placeholder-gray-700  focus:text-gray-900 dark:focus:text-gray-100 text-base border-0 flex-wrap" + oParams.css_class + "'></div>" );
    	$(oParams.selector).hide();
		oEditor = new Module.Editor({
			element: document.querySelector('#' + oParams.name),
			extensions: [
				Module.Document, 
				Module.Paragraph, 
				Module.Text, 
				Module.Heading,
				Module.Bold, 
				Module.Italic, 
				Module.Strike,
				Module.Underline, 
				Module.Subscript,
				Module.Superscript, 
				Module.Blockquote,
				Module.BulletList,
				Module.OrderedList,
				Module.ListItem,
				Module.Code,
				Module.Highlight,
				Module.CodeBlock,
				Module.Highlight,
				Module.Heading,
				Module.Link,
				Module.Image.configure({
                  allowBase64: true,
                }),
				Module.Mention.configure({
                    HTMLAttributes: {
                        class: 'mention',
                        },
                    suggestion: {
                      },
                }),
				Module.Span,
				Module.Iframe,
				Module.Div,
				Module.Embed,
                Module.EventHandler.configure({
                  uploaderUrl: oParams.bx_url_uploader,
                }),
				Module.TextAlign.configure({
					types: ['heading', 'paragraph'],
				})

			],
			editorProps: {
				attributes: {
					class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl m-5 focus:outline-none',
				},
			},
			  
			content: $(oParams.selector).val(),
			onUpdate({ editor }) {
				$(oParams.selector).val(editor.getHTML());
			},
			onSelectionUpdate({ editor }) {
				bx_ex_editor_check(editor, oParams);
			}
		});
		
		oParams.toolbar.forEach(
			elements => {
				elements.forEach(
					element => {
						var oBtnData = eval("oParams.buttons." + element);
						if (oBtnData){
							var oBtn = $('<button title = "' + oBtnData.text + '">' + (oBtnData.icon ? '<i class="sys-icon ' + oBtnData.icon + ' w-5 opacity-70 group-hover:opacity-100"></i>': oBtnData.text) + '</button>').attr({
								 class: "group justify-center  border border-transparent hover:border-gray-200/50 text-gray-600 dark:text-gray-400 active:bg-gray-300 active:border-gray-300 dark:active:bg-black/50   hover:bg-gray-200/50 dark:hover:text-gray-200 dark:hover:bg-gray-700/50 dark:hover:border-gray-700/50 dark:active:border-gray-700 hover:text-gray-800 flex items-center px-2 py-1.5  text-sm font-medium rounded-md",
                                 type: 'button',
								 id: element
							});
							$('#' + oParams.name + '_toolbar').append(oBtn);
							$('#' + oParams.name + '_toolbar button#' + element).bind( "click", function() {
								if(oBtnData.command_ex && oBtnData.command_ex == true)
									eval(oBtnData.command);
								else
									eval('oEditor.commands.' + oBtnData.command);
								
								bx_ex_editor_check(oEditor, oParams);
							});
						}
					}
				)
			}
		);
	});
}

function bx_ex_editor_add_link(oEditor, oParams)
{
	if(oEditor.isActive('link')){
		oEditor.commands.unsetLink()
	}
	else{
		bx_prompt(_t('_bx_editor_embed_popup_link'), '', function(oPopup){
			 oEditor.commands.setLink({ href: $(oPopup).find("input[type = 'text']").val() })
		});
	}
}

function bx_ex_editor_add_image(oEditor, uploaderUrl)
{
	var input = document.createElement('input');
	input.type = 'file';

	input.onchange = e => { 
		bx_ex_editor_upload(oEditor, uploaderUrl, e.target.files[0])
	}

	input.click();
}

function bx_ex_editor_upload(oEditor, uploaderUrl, file)
{
    const formData = new FormData();
    formData.append("file", file);
    fetch(uploaderUrl, {
            method: "POST",
            body: formData
        }
    )
    .then(response => response.json())
    .then(result => {
        if ('undefined' != typeof(result.link))
            oEditor.commands.setImage({ src: result.link });
            oEditor.commands.setParagraph()
    })
    .catch(error => {
    });
}

function bx_ex_editor_add_embed(oEditor, oParams)
{
	bx_prompt(_t('_bx_editor_embed_popup_embed'), '', function(oPopup){
        oEditor.commands.setEmbed({ class: 'bx-embed-link', source: $(oPopup).find("input[type = 'text']").val() })
    });
}

function bx_ex_editor_check(oEditor, oParams)
{
	oParams.toolbar.forEach(
	elements => {
		elements.forEach(
			element => {
				var oBtnData = eval("oParams.buttons." + element);
				if (oBtnData && oBtnData.checkOnSelect && oBtnData.checkOnSelect == true){
					if(oEditor.isActive(element))
						$('#' + oParams.name + '_toolbar button#' + element).addClass('selected');
					else
						$('#' + oParams.name + '_toolbar button#' + element).removeClass('selected');
				}
			}
		)
	})
}


