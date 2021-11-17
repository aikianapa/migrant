<div class="m-3" id="moduleExportXls">
    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Экспорт в XLS</h3>
        <div class="pos-md-absolute r-0 t-0">
            <a href="#" class="btn btn-primary download mb-1">
                <svg wb-module="myicons" class="mi mi-zip-archive-circle.1 size-20" stroke="#FFFFFF"></svg>
                Скачать документы
            </a>
            <a href="#" class="btn btn-primary export mb-1">
                <svg wb-module="myicons" class="mi mi-documents-file-excel.2 size-20" stroke="#FFFFFF"></svg>
                Экспорт реестра
            </a>
            <a href="#" class="btn btn-danger archive mb-1">
                <svg wb-module="myicons" class="mi mi-mailbox-archive size-20" stroke="#FFFFFF"></svg>
                Отправить в архив
            </a>
        </div>

    </nav>

    <wb-var date="" />
    <ul class="list-group mb-3" id="moduleExportXlsAccept">
        <li class="list-group-item bg-gray-200">
            <h5 class="m-0">Список клиентов для экспорта</h5>
            <input type="checkbox" class="pos-absolute wd-20 ht-20 r-10 t-10" onclick="$(this).parents('.list-group').find('[type=checkbox]').prop('checked',$(this).prop('checked'));">
        </li>
        <wb-foreach wb="from=list&bind=cms.list.modExport&tpl=true&size=99999">
            <li class="list-unstyled" wb-if="'{{_var.date}}'!=='{{date}}'" class="bg-transparent">
                <wb-var date="{{date}}" />
                <div class="divider-text tx-primary">{{wbDate("d.m.Y",{{{{_created}}}})}}</div>

            </li>
            <li class="list-group-item">
                <div>
                    <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                    <input type="checkbox" class="pos-absolute wd-20 ht-20 r-10 t-10" data-id="{{id}}">
                    <a class="pos-absolute r-40 t-10" href="javascript:void(0);" onclick="window.open('{{order.0.img}}', '_blank');$(this).prev('input').prop('checked','true')">
                        <img data-src="/module/myicons/printer.svg?size=24&stroke=0168fa"> {{code}}
                    </a>

                    <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>

                </div>
            </li>
        </wb-foreach>
    </ul>

<div class="modal" tabindex="-1" data-backdrop="static" role="dialog" id="moduleExportWait" aria-modal="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ожидайте</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body"><p class="tx-center">Идёт подготовка документов...<br><br><span class="spinner-border spinner-border" role="status" aria-hidden="true"></span></p></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

    <script wb-app remove>

        $('#moduleExportXls .btn.download').off('click');
        $('#moduleExportXls .btn.download').on('click', function() {
            let data = {};
            data.items = [];
            $('#moduleExportXlsAccept .list-group-item input[data-id]:checked').each(function(i) {
                data.items[i] = $(this).data('id')
            });
            if (data.items.length == 0) {
                wbapp.toast('Предупреждение', 'Необходимо выбрать хотя бы один документ.', {
                    bgcolor: 'danger'
                });
                return false;
            } else {
                $('#moduleExportWait').modal('show');
            }
            $.ajax({
                type: 'POST',
                url: '/module/export/zipdocs/',
                data: data
            }).done(function(data) {
                $('#moduleExportWait').modal('hide');
                var $a = $("<a>");
                $a.attr("href", data);
                $a.attr("target", "_blank");
                $a.addClass("d-none");
                $("body").append($a);
                $a.attr("download", "Documents.zip");
                $a[0].click();
                $a.remove();
            });
        });


        $('#moduleExportXls .btn.export').off('click');
        $('#moduleExportXls .btn.export').on('click', function() {
            let data = {};
            data.items = [];
            $('#moduleExportXlsAccept .list-group-item input[data-id]:checked').each(function(i) {
                data.items[i] = $(this).data('id')
            });
            if (data.items.length == 0) {
                wbapp.toast('Предупреждение', 'Необходимо выбрать хотя бы один документ.', {
                    bgcolor: 'danger'
                });
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/module/export/process/',
                data: data,
                dataType: 'json'
            }).done(function(data) {
                var $a = $("<a>");
                $a.attr("href", data);
                $a.attr("target", "_blank");
                $a.addClass("d-none");
                $("body").append($a);
                $a.attr("download", "Report.xls");
                $a[0].click();
                $a.remove();
            });
        });
        $('#moduleExportXls .btn.archive').off('click');
        $('#moduleExportXls .btn.archive').on('click', function() {
            let data = {};
            data.items = [];
            $('#moduleExportXlsAccept .list-group-item input[data-id]:checked').each(function(i) {
                data.items[i] = $(this).data('id')
            });
            if (data.items.length == 0) {
                wbapp.toast('Предупреждение', 'Необходимо выбрать хотя бы один документ.', {
                    bgcolor: 'danger'
                });
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/module/export/archive/',
                data: data,
                dataType: 'json'
            }).done(function(data) {
                if (!data.error) {
                    $('#moduleExportXlsAccept .list-group-item input[data-id]:checked').closest(
                        '.list-group-item').remove();
                    wbapp.toast('Выполнено', 'Указанные записи отпралены в архив.', {
                        bgcolor: 'success'
                    });
                } else {
                    wbapp.toast('Ошибка', data.msg, {
                        bgcolor: 'danger'
                    });
                }
            });
        });
    </script>
</div>