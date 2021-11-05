$(document).ready(function() {

    $('#docsEditForm .accordion').accordion({
        heightStyle: 'content',
        collapsible: true
    });

    $('#modalDocsEdit').delegate('.btn.print', wbapp.evClick, function() {
        let data = $('#docsEditForm').serializeJson();
        let $form = $("<form />");
        $form.attr('method', 'POST').attr('target', '_blank').attr('action', '/module/printdocx');
        $.each(data, function(k, v) {
            $form.append('<input type="hidden" name="' + k + '" value="' + v + '">');
        });
        $form.appendTo('body').submit();
        $form.remove();
    })

    $('#docsEditForm #uploadSources').off('mod-filepicker-done');
    // После загрузки исходников
    $('#docsEditForm #uploadSources').on('mod-filepicker-done', function(ev, data) {
        if (!$(ev.currentTarget).is('#uploadSources')) return;
        if (data.img.substr(-4).toLowerCase() == '.pdf') {
            wbapp.post('/module/pdfer/extract/', { 'pdf': data.img }, function(data) {
                $('#docsEditForm #uploadSources [name=sources]').text(json_encode(data));
            })
        }
    })

    $('#docsEditForm #uploadOrder').off('mod-filepicker-done');
    // После загрузки исходников
    $('#docsEditForm #uploadOrder').on('mod-filepicker-done', function(ev, data) {
        if (!$(ev.currentTarget).is('#uploadOrder')) return;
        let sources = JSON.parse($('#docsEditForm #uploadSources [name=sources]').text());
        console.log(sources);
        if (data.img.substr(-4).toLowerCase() == '.pdf') {
            let pdf = data.img;
            let srcpdf = json_decode($('#uploadSources .filepicker-data').text());
            wbapp.post('/module/pdfer/attach/', { 'pdf': pdf, 'sources': sources, 'srcpdf': srcpdf }, function(data) {
                window.open(data.pdf, '_blank');
                $('#docsEditForm #uploadSources [name=sources]').text('');
                $('#docsEditForm #uploadSources .filepicker-data').text('');
                $('#docsEditForm #uploadSources .listview').html('');
                let doc = json_decode($('#docsEditForm #uploadOrder .filepicker-data').text());
                doc[0].img = data.pdf;
                let $img = $('#docsEditForm #uploadOrder figure > img[data-src]');
                $img.attr('src', str_replace(pdf, data.pdf, $img.attr('src')));
                $img.attr('data-src', str_replace(pdf, data.pdf, $img.attr('data-src')));
                $('#docsEditForm #uploadOrder .filepicker-data').text(json_encode(doc));
            })
        }
    })
})