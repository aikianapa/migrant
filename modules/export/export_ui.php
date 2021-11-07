<div class="m-3" id="moduleExportXls">
    <nav class="nav navbar navbar-expand-md col">
        <a href="#" class="btn btn-success export pos-absolute r-0 t-0">
            <svg wb-module="myicons" class="mi mi-documents-file-excel.2 size-20" stroke="#FFFFFF"></svg>
            Экспортировать
        </a>
        <h3 class="tx-bold tx-spacing--2 order-1">Экспорт в XLS</h3>
    </nav>

    <ul class="list-group mb-3" id="moduleExportXlsAccept">
        <wb-foreach wb="table=docs&bind=cms.list.modExport&tpl=true&size=10" wb-filter="{'code':{'$ne':''},'order':{'$ne':''}}">
            <li class="list-group-item">
                <div>
                    <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                    <span class="pos-absolute r-10 t-0 tx-11 tx-primary">{{code}}</span>
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