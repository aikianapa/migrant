<div class="m-3" id="moduleExportXls">
    <nav class="nav navbar navbar-expand-md col">
        <div class=" pos-absolute r-0 t-0">
        <a href="#" class="btn btn-primary export">
            <svg wb-module="myicons" class="mi mi-documents-file-excel.2 size-20" stroke="#FFFFFF"></svg>
            Экспортировать
        </a>
        <a href="#" class="btn btn-success export">
            <svg wb-module="myicons" class="mi mi-documents-file-excel.2 size-20" stroke="#FFFFFF"></svg>
            Отправить в архив
        </a>
        </div>
        <h3 class="tx-bold tx-spacing--2 order-1">Экспорт в XLS</h3>
    </nav>

    <ul class="list-group mb-3" id="moduleExportXlsAccept">
        <li class="list-group-item bg-gray-200">
            <h5 class="m-0">Список клиентов для экспорта</h5>
            <input type="checkbox" class="pos-absolute wd-20 ht-20 r-10 t-10" onclick="$(this).parents('.list-group').find('[type=checkbox]').prop('checked',$(this).prop('checked'));">
        </li>
        <wb-foreach wb="table=docs&bind=cms.list.modExport&tpl=true&size=10" wb-filter="{'code':{'$ne':''},'order':{'$ne':''}}">
            <li class="list-group-item">
                <div>
                    <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                    <input type="checkbox" class="pos-absolute wd-20 ht-20 r-10 t-10">
                    <a class="pos-absolute r-40 t-10" data-id="{{id}}" href="javascript:void(0);" onclick="window.open('{{order.0.img}}', '_blank');$(this).prev('input').prop('checked','true')">
                        <img data-src="/module/myicons/printer.svg?size=24&stroke=0168fa"> 
                        {{code}}
                    </a>

                    <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>

                </div>
            </li>
        </wb-foreach>
    </ul>
    <script wb-app remove>
        $('#moduleExportXls .btn.export').off('click');
        $('#moduleExportXls .btn.export').on('click', function() {

            $.ajax({
                type: 'POST',
                url: '/module/export/process/',
                data: {},
                dataType: 'json'
            }).done(function(data) {
                var $a = $("<a>");
                $a.attr("href", data);
                $a.addClass("d-none");
                $("body").append($a);
                $a.attr("download", "Report.xls");
                $a[0].click();
                $a.remove();
            });
        });
    </script>
</div>