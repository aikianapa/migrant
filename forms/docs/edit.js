$(document).ready(function () {
    $('#docsEditForm .accordion').accordion({
        heightStyle: 'content',
        collapsible: true
    });

    docsListSources = new Ractive({
        el: '#listSources .srcList',
        template: $('#listSources .srcList').html(),
        data: {
            uid: wbapp.newId(),
            srcList: []
        },
        on: {
            update(ev) {
                $('#listSources .srcList').find('a[data-href]').each(function () {
                    $(this).attr('href', $(this).attr('data-href'))
                    $(this).children('img').attr('src', $(this).children('img').attr('data-src'))
                })
                if (typeof refreshFsLightbox !== "undefined") { refreshFsLightbox() }
            }
        }
    })
    let srclist = $('#uploadSources').children('textarea[name=sources]').val()
    try {
        srclist = $.parseJSON($('#uploadSources').children('textarea[name=sources]').val())
    } catch (error) {
        srclist = []
    }

    docsListSources.set('srcList', srclist)
    docsListSources.update()

    $('#modalDocsEdit').delegate('.btn.print', wbapp.evClick, function () {
        let data = $('#docsEditForm').serializeJson();
        let $form = $("<form />");
        $form.attr('method', 'POST').attr('target', '_blank').attr('action', '/module/printdocx');
        $.each(data, function (k, v) {
            $form.append('<input type="hidden" name="' + k + '" value="' + v + '">');
        });
        $form.appendTo('body').submit();
        $form.remove();
    })

    $('#docsEditForm #uploadSources').off('mod-filepicker-done');
    // После загрузки исходников
    $('#docsEditForm #uploadSources').on('mod-filepicker-done', function (ev, data) {
        if (!$(ev.currentTarget).is('#uploadSources')) return;
        data = data[0]
        if (data.img.substr(-4).toLowerCase() == '.pdf') {
            wbapp.post('/module/pdfer/extract/', { 'pdf': data.img }, function (data) {
                $('#docsEditForm #uploadSources [name=sources]').text(json_encode(data));
                $('#modalDocsEdit .btn-save').trigger('click');
            })
        }
    })

    $('#modalDocsEdit').on('wb-save-done', function (ev, data) {
        if (data.params.form !== '#docsEditForm') return
        docsListSources.set('srcList', data.data.sources)
        docsListSources.set('uid', wbapp.newId())
        docsListSources.update()
    });

    $('#docsEditForm #uploadOrder').off('mod-filepicker-done');
    // После загрузки договора
    $('#docsEditForm #uploadOrder').on('mod-filepicker-done', function (ev, data) {
        if (!$(ev.currentTarget).is('#uploadOrder')) return;
        let sources;
        try {
            sources = JSON.parse($('#docsEditForm #uploadSources [name=sources]').text());
        } catch (error) {
            wbapp.toast('Предупреждение', 'Вначале загрузите скан с документами!', { bgcolor: 'danger' });
            $('#docsEditForm #uploadOrder [name=order]').text('');
            $('#docsEditForm #uploadOrder .listview').html('');
            return;
        }
        if (data.img.substr(-4).toLowerCase() == '.pdf') {
            let pdf = data.img;
            let srcpdf;
            try {
                srcpdf = $.parseJSON($('#uploadSources .filepicker-data').text());
            } catch (error) {
                srcpdf = null;
            }
            let item = $('#docsEditForm').serializeJson();
            item['_created'] > ' ' ? null : item['_created'] = date('Y-m-d', strtotime('now'));
            let dstpdf = date('dmY', strtotime(item['_created'])) + '_' + item['doc_ser'] + item['doc_num'] + '.pdf';
            dstpdf.replace("__", "_");
            wbapp.post('/module/pdfer/attach/', { 'pdf': pdf, 'sources': sources, 'srcpdf': srcpdf, 'dstpdf': dstpdf }, function (data) {
                window.open(data.pdf, '_blank');
                //                $('#docsEditForm #uploadSources [name=sources]').text('');
                $('#docsEditForm #uploadSources .filepicker-data').text('');
                $('#docsEditForm #uploadSources .listview').html('');
                let doc = $.parseJSON($('#docsEditForm #uploadOrder .filepicker-data').text());
                doc[0].img = data.pdf;
                let $img = $('#docsEditForm #uploadOrder figure > img[data-src]');
                $img.attr('src', str_replace(pdf, data.pdf, $img.attr('src')));
                $img.attr('data-src', str_replace(pdf, data.pdf, $img.attr('data-src')));
                $('#docsEditForm #uploadOrder .filepicker-data').text(json_encode(doc));
                $('#modalDocsEdit .btn-save').trigger('click');
            })
        }
    })

    $('#modalDocsEdit').delegate('#docViewPdf', wbapp.evClick, function () {
        let doc = $('#docsEditForm #uploadOrder figure > img[data-src]').attr('data-src');
        if (doc > '') {
            window.open(doc, '_blank');
        }
        return false;

    });


})