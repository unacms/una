<script>
    function bx_oembed_process_links(el) {
        var alinksHref = [];
        var alinksEl = [];

        if (typeof el.tagName != 'undefined' && el.tagName == 'A') {
            alinksEl.push(el);
            alinksHref.push($(el).attr('href'));
        } else {
            $('a[bx-oembed-url]', el).each(function (i, a) {
                alinksEl.push(a);
                alinksHref.push($(a).attr('href'));
            });
        }

        if (alinksEl.length) {
            $.post(sUrlRoot + 'oembed.php', {l: alinksHref}, function (oResponse) {
                if (oResponse.length)
                    for (i in oResponse) {
                        if (oResponse[i].html.length) {
                            $(alinksEl[i]).replaceWith(oResponse[i].html);
                        } else {
                            $(alinksEl[i]).removeAttr('bx-oembed-url');
                        }
                    }
            }, 'json');
        }
    }

    $(document).ready(function(){
        bx_oembed_process_links(this);

        if (typeof glOnProcessHtml === 'undefined')
            glOnProcessHtml = [];
        if (glOnProcessHtml instanceof Array) {
            glOnProcessHtml.push(bx_oembed_process_links);
        }
    });
</script>