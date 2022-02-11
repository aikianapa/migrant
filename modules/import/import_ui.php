<div>
    <div class="m-3" id="moduleImportXls">
        <nav class="nav navbar navbar-expand-md col">
            <h3 class="tx-bold tx-spacing--2 order-1">Импорт из XLS</h3>
        </nav>

        <div class="mod-data row">
            <div class="col-auto col-form-label">
                От кого
            </div>
            <div class="col">
                <select name="team" class="form-control select2" placeholder="От кого" wb-tree="item=teams">
                    <option value="{{id}}">{{name}}</option>
                </select>
            </div>
            <div class="col">
                <input wb="module=filepicker&mode=button&ext=xls" name="file" wb-path="/uploads/tmp" wb-button="Файл XLS">
            </div>
        </div>

        <div class="mod-wait d-none">
            <div class="alert alert-secondary">
                Выполняется импорт данных. Ждите...
                <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
            </div>
        </div>

        <ul class="list-group" id="moduleImportXlsAccept">
            <wb-foreach wb="from=result&bind=cms.list.modImport.accept&render=client&tpl=true">
                {{#if @index == 0}}
                <li class="list-group-item bg-primary tx-16 tx-semibold tx-white">Приняты</li>
                {{/if}}
                <li class="list-group-item">
                    <div>
                        <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                        <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>
                    </div>
                </li>
            </wb-foreach>
        </ul>

        <ul class="list-group" id="moduleImportXlsDecline">
            <wb-foreach wb="from=result&bind=cms.list.modImport.decline&render=client&tpl=true">
                {{#if @index == 0}}
                <li class="list-group-item bg-danger tx-16 tx-semibold tx-white">Отклонены</li>
                {{/if}}
                <li class="list-group-item">
                    <div>
                        <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                        <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>
                    </div>
                </li>
            </wb-foreach>
        </ul>

        <script>
            wbapp.loadStyles(['/engine/lib/js/select2/select2.min.css'], 'select2-css');
            wbapp.loadScripts(['/engine/lib/js/select2/select2.min.js'], 'select2-js', function() {
                $('#moduleImportXls [name=team]').select2().on("select2:select", function(e) {
                    if ($(e.currentTarget).val() == '') {
                        $('#moduleImportXls').find('.btn, input[type=file]').prop('disabled', true);
                    } else {
                        $('#moduleImportXls').find('.btn, input[type=file]').prop('disabled', false);
                    }
                }).trigger('select2:select');
            });
            wbapp.storage('cms.list.modImport', null);
            $(document).undelegate('#moduleImportXls', 'mod-filepicker-done');
            $(document).delegate('#moduleImportXls', 'mod-filepicker-done', function(ev, data) {
                data.team = $('#moduleImportXls [name=team]').val();
                if (!$(ev.currentTarget).is('#moduleImportXls')) return;
                $('#moduleImportXls .mod-data').hide();
                $('#moduleImportXls .mod-wait').removeClass('d-none');
                wbapp.loading();
                wbapp.post('/module/import/process/', data, function(data) {
                    wbapp.unloading();
                    $('#moduleImportXls .mod-wait').hide();
                    wbapp.storage('cms.list.modImport.decline', data.decline);
                    wbapp.storage('cms.list.modImport.accept', data.accept);
                });
            })
        </script>
    </div>

    <div class="m-3" id="moduleImportZip">
        <nav class="nav navbar navbar-expand-md col">
            <h3 class="tx-bold tx-spacing--2 order-1">Импорт документов ZIP</h3>
        </nav>

        <div class="mod-data row mb-3">
            <div class="col-auto">
                <input wb="module=filepicker&mode=button&ext=zip" name="file" wb-path="/uploads/tmp/src" wb-button="Файл ZIP">
            </div>
            <div class="col col-form-label">
                Выберите ZIP файл с исходными документами
            </div>
        </div>

        <div class="mod-data row">
            <div class="col-auto">
                <input wb="module=filepicker&mode=button&ext=zip" name="file" wb-path="/uploads/tmp/doc" wb-button="Файл ZIP">
            </div>
            <div class="col col-form-label">
                Выберите ZIP файл с подписанными договорами
            </div>
        </div>



        <div class="mod-wait d-none">
            <div class="alert alert-secondary">
                Выполняется импорт данных. Ждите...
                <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
            </div>
        </div>

        <ul class="list-group" id="moduleImportZipAccept">
            <wb-foreach wb="from=result&bind=cms.list.modImport.accept&render=client&tpl=true">
                {{#if @index == 0}}
                <li class="list-group-item bg-primary tx-16 tx-semibold tx-white">Приняты</li>
                {{/if}}
                <li class="list-group-item">
                    <div>
                        <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                        <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>
                    </div>
                </li>
            </wb-foreach>
        </ul>

        <ul class="list-group" id="moduleImportZipDecline">
            <wb-foreach wb="from=result&bind=cms.list.modImport.decline&render=client&tpl=true">
                {{#if @index == 0}}
                <li class="list-group-item bg-danger tx-16 tx-semibold tx-white">Отклонены</li>
                {{/if}}
                <li class="list-group-item">
                    <div>
                        <h6 class="tx-13 tx-inverse tx-semibold mg-b-0">{{fullname}}</h6>
                        <span class="d-block tx-11 text-muted">{{birth_date}} {{doc_ser}} {{doc_num}}</span>
                    </div>
                </li>
            </wb-foreach>
        </ul>

        <script>
            wbapp.storage('cms.list.modImportZip', null);
            $(document).undelegate('#moduleImportZip', 'mod-filepicker-done');
            $(document).delegate('#moduleImportZip', 'mod-filepicker-done', function(ev, data) {
                wbapp.loading();
                wbapp.post('/module/import/zipdocs/', data, function(data) {
                    wbapp.unloading();
                    $('#moduleImportZip .mod-wait').hide();
                    wbapp.storage('cms.list.modImportZip.decline', data.decline);
                    wbapp.storage('cms.list.modImportZip.accept', data.accept);
                });
            })
        </script>
    </div>
</div>