function bx_ex_editor_init(oEditor, oParams)
{
    $(oParams.selector).after("<div id='" + oParams.name + "' class='" + oParams.name + " bx-def-font-inputs bx-form-input-textarea bx-form-input-html bx-form-input-html-editor mt-px text-gray-700 dark:text-gray-300  w-full p-4  ring-1 ring-gray-300 dark:ring-gray-700 dark:focus:placeholder-700 bg-gray-50 dark:bg-gray-900/50 placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-gray-900 focus:placeholder-gray-300 dark:focus:placeholder-gray-700  focus:text-gray-900 dark:focus:text-gray-100 text-base border-0 flex-wrap" + oParams.css_class + "'></div>" );
    
    $(oParams.selector).hide();
    
    var oImage = {
        class: ImageTool,
            config: {
            field: 'file',    
            uploader: {
              uploadByFile(file){
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append("file", file);
                        fetch(oParams.root_url + "storage.php?o=sys_images_editor&t=sys_images_editor&a=upload", {
                                method: "POST",
                                body: formData
                            }
                        )
                        .then(response => response.json())
                        .then(result => {
                            if ('undefined' != typeof(result.link))
                                resolve({
                                    success: 1,
                                    file: {
                                        url: result.link,
                                    }
                                });
                            else
                                reject("Upload failed");
                        })
                        .catch(error => {
                            reject("Upload failed");
                        });
                    });    
              },

              uploadByUrl(url){
                  return new Promise((resolve, reject) => {
                      resolve({
                        success: 1,
                        file: {
                            url: url,
                        }
                      });
                  });
              }
            }    
        }
    };
    
    var oTools = {
        paragraph: {
          class: Paragraph,
          inlineToolbar: true,
        },
        embedin: BxEmbedInline,
        mention: BxMention,
        marker: Marker,
        inlineCode: InlineCode,
    };

    console.log(oParams.toolbar);
    
    oParams.toolbar.forEach(function(item){
         console.log(item);
        switch(item) {
            case 'header':
                oTools.header = Header;
                break;

            case 'list':
                oTools.list = List;
                break;

            case 'image':
                oTools.image = oImage;
                break;
                
            case 'embed':
                oTools.embed = BxEmbedBlock;
                break;
                
            case 'code':
                oTools.code = CodeTool;
                break;
                
            case 'delimiter':
                oTools.delimiter = Delimiter;
                break;
        }
    })
    console.log(oTools);
    
    oEditor = new EditorJS({
        holder : oParams.name,
        inlineToolbar: oParams.toolbar_inline.concat(['embedin', 'mention']),
        tools: oTools,
        onReady: () => {
            oEditor.blocks.renderFromHTML($(oParams.selector).val());
        },
        onChange:() =>{
            
            oEditor.save().then((savedData) =>{
                const edjsParser = edjsHTML({embed2: bx_ex_editor_custom_parser});
                oData = edjsParser.parse(savedData);
                var s ='';
                oData.forEach(function(item){
                    s += item;
                })
                
                $(oParams.selector).val(s)
                
            }).catch((error) =>{
                console.log("error", error)
            })  
        }
    }); 
    
    /*$('#' + oParams.name).atwho({
        at: "@",
        searchKey: 'label',
        insertTpl: '<a class="bx-menthion-link" dchar="@" data-profile-id="${value}" href="${url}">@${label}</a>',
        displayTpl: '<li class="bx-mention-row" data-value="${value}"><span>${label}</span></li>',
        callbacks: {
            remoteFilter: function(query, callback) {
                $.getJSON(oParams.root_url + "/searchExtended.php?action=get_mention&symbol=%40&", {term: query}, function(data) {
                    callback(data);
                });
            }
        },
    });
    
    $('#' + oParams.name).atwho({
        at: "#",
        searchKey: 'label',
        insertTpl: '<a class="bx-menthion-link" dchar="#" data-profile-id="${value}" href="${url}">#${label}</a>',
        displayTpl: '<li class="bx-mention-row" data-value="${value}"><span>${label}</span> <img class="bx-def-round-corners" src="${thumb}" /></li>',
        callbacks: {
            remoteFilter: function(query, callback) {
                $.getJSON(oParams.root_url + "/searchExtended.php?action=get_mention&symbol=%23&", {term: query}, function(data) {
                    callback(data);
                });
            }
        },
    });*/
    tribute = new Tribute({
        collection: [
            {
                selectTemplate: function(item) {
                    if (this.range.isContentEditable(this.current.element)) {
                        return ('<a class="bx-menthion-link" dchar="@" data-profile-id="' + item.original.value + '" href="' + item.original.url + '">@' + item.original.label + '</a>');
                    }
                    return "@" + item.original.value;
                },
                values: function (text, cb) {
                    $.getJSON( "/searchExtended.php?action=get_mention&symbol=%40&", {term: text}, function(data) {
                        cb(data);
                    });
                },
                lookup: "label",
                fillAttr: "label"
            },
            {
                trigger: "#",
                selectTemplate: function(item) {
                    if (this.range.isContentEditable(this.current.element)) {
                        return ('<a class="bx-menthion-link" dchar="#" data-profile-id="' + item.original.value + '" href="' + item.original.url + '">#' + item.original.label + '</a>');
                    }
                    return "#" + item.original.value;
                },
                values: function (text, cb) {
                    $.getJSON( "/searchExtended.php?action=get_mention&symbol=%23&", {term: text}, function(data) {
                        cb(data);
                    });
                },
                lookup: "label",
                fillAttr: "label"
            }
        ]
    })
    tribute.attach(document.getElementById(oParams.name));
}

function bx_ex_editor_custom_parser(block)
{
    return '<p><span class="bx-embed-link" source="' + block.data.source + '">' + block.data.source + '</span></p>';
}