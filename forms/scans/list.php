<html>
<div class="m-3" id="yongerscans" wb-allow="admin,reg">

    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Сканы</h3>
        <button class="navbar-toggler order-2" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="wd-20 ht-20 fa fa-ellipsis-v"></i>
        </button>

        <div class="collapse navbar-collapse order-2" id="navbarSupportedContent">
            <form class="form-inline mg-t-10 mg-lg-0  ml-auto">
                <div class="form-group">
                    <input class="form-control mg-r-10 col-auto" type="search" placeholder="Поиск..."
                        aria-label="Поиск..."
                        data-ajax="{'target':'#{{_form}}List','filter_add':{'$or':[{ 'doc_num' : {'$like' : '$value'} }, { 'fullname': {'$like' : '$value'} }]} }">
                        <wb-module id="scansImport" wb="{
                            'module':'filepicker',
                            'button': 'Импорт',
                            'width':'200',
                            'height':'200',
                            'mode':'button',
                            'original': false
                        }" wb-ext="zip" wb-path='/uploads/tmp/' />
                </div>
            </form>
        </div>
    </nav>

    <wb-var date="" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']}}" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']},'_creator':'{{_sess.user.id}}'}" wb-if="in_array({{_sess.user.role}},['partner','',null])" />


    <div class="yongerscans-wait d-none my-3">
        <div class="alert alert-secondary">
            Выполняется импорт данных. Ждите...
            <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
        </div>
    </div>

    <table class="table table-striped table-hover tx-15">
        <thead>
            <tr>
                <th class="tx-right wd-20p">Серия</th>
                <th>Номер</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="scansList">
            <wb-foreach wb="table=scans&bind=cms.list.scans&sort=sernum&size={{_sett.page_size}}"
                wb-filter="{{_var.filter}}">

                <tr data-id="{{id}}">
                    <td class="tx-right wd-20p">{{doc_ser}}</td>
                    <td>{{doc_num}}</td>
                    <td>
                        <input wb-module="swico" name="active"
                        data-ico-on="checkmark-circle-1" data-ico-off="thunder-lightning-circle.1"
                        data-color-on="10b759" data-color-off="dc3545"
                        onchange="wbapp.save($(this),{'table':'{{_form}}','id':'{{_id}}','field':'active','silent':'true'})">
                    </td>
                    <td class="tx-right">

                        <a href="javascript:" wb-if="'{{active}}'=='on'"
                            data-ajax="{'url':'/cms/ajax/form/docs/editpeoples/{{id}}?scan=true','html':'#yongerscans modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=10b759">
                        </a>

                        <a href="javascript:" wb-if="'{{active}}'==''"
                            data-ajax="{'url':'/cms/ajax/form/scans/edit/{{id}}','html':'#yongerscans modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232">
                        </a>
                        <a href="javascript:" wb-if="'{{_sess.user.role}}' == 'admin'"
                            data-ajax="{'url':'/ajax/rmitem/scans/{{id}}','update':'cms.list.scans','html':'#yongerscans modals'}"
                            class="d-inline">
                            <img src="/module/myicons/trash-delete-bin.2.svg?size=24&stroke=323232" class="d-inline">
                        </a>
                    </td>
                </tr>
            </wb-foreach>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>


    <modals></modals>
</div>
<wb-module wb="module=synapse&host={{_route.hostname}}&port=4000&project=migrant&room=scans">
    <script wb-app>
    $(document).off('modSynapse');
    $(document).one('modSynapse',function(e,synapse){
        $('#scansList').synapse = synapse;
        synapse.get = function(data) {
            if (!$('#scansList').length) {
                delete synapse;
                $(document).off('modSynapse');
                return;
            }
            let result = data.data;
            switch (data.type) {
                // System messages
                case 'sysmsg':
                    if (data.action == 'formsave') eventFormasave(data);
                    break;
                // Data messages
                case 'data':
                    if (result !== undefined && result.msg == 'scanblocks') {
                        $('#scansList').find('tr[data-id]').removeClass('d-none');
                        $(result.blocks).each(function(i,id){
                            $('#scansList').find('tr[data-id="'+id+'"]').addClass('d-none');
                        })
                    }
                    break;
            }
        }

        let eventFormasave = function(data) {
            let form = data.params.form;
            let item = data.params.item;
            let table = data.params.table;
            if (form !== '#docsEditForm' || !$('#scansList').length || !$('#docsEditForm meta[name=scan][content=true]').length) return;
            $('#scansList').find('tr[data-id="'+item+'"]').remove();
        }

        setTimeout(function(){
            synapse.put({'type':'ajax','url':document.location.origin+'/api/v2/func/scans/getblock','post':{},'cast':'room'});
        },200)


        $(document).on('wb-ajax-done',function(e,data){
            if (data !== undefined && data.url !== undefined && data.url == "/cms/ajax/form/scans/list") {
                synapse.put({'type':'ajax','url':document.location.origin+'/api/v2/func/scans/getblock','post':{},'cast':'room'});
            }
        })

        $(document).undelegate('#modalPeoplesEdit','hide.bs.modal');
        $(document).delegate('#modalPeoplesEdit','hide.bs.modal',function(){
            let id = $(this).data("id");
            synapse.put({'type':'ajax','url':document.location.origin+'/api/v2/func/scans/unblock','post':{'id':id},'cast':'room'});
        })

        $('#scansList').undelegate('a[data-ajax]',wbapp.evClick);
        $('#scansList').delegate('a[data-ajax]',wbapp.evClick,function(){
            let id = $(this).parents('tr').data('id');
            synapse.put({'type':'ajax','url':document.location.origin+'/api/v2/func/scans/block','post':{'id':id},'cast':'room'});
        })
    });
    </script>
</wb-module>

<script wb-app>
    $('#yongerscans').off('mod-filepicker-done');
    $('#yongerscans').on('mod-filepicker-done',function(ev,data){
        let synapse = $('#scansList').synapse;
        $('#yongerscans .yongerscans-wait').removeClass('d-none');
        if (data[0] !== undefined) {
            wbapp.post('/cms/ajax/form/scans/import',data[0],function(data){
                $('#yongerscans .yongerscans-wait').addClass('d-none');
                wbapp.render('#scansList');
                synapse.put({'type':'ajax','url':document.location.origin+'/cms/ajax/form/scans/block/getblock','post':{},'cast':'room'});
            });
        } else {
            wbapp.toast('Ошибка!', 'Загрузка файла не удалась, попробуйте снова',{ bgcolor: 'danger' });
        }
    });
</script>
<wb-lang>
    [ru]
    scans = Документы
    search = Поиск
    newDoc = Новый документ
    [en]
    scans = Documents
    search = Search
    newDoc = New document
</wb-lang>

</html>