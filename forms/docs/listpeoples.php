<html>
<div class="m-3" id="yongerPeoples" wb-allow="admin,reg">

    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Люди</h3>
        <button class="navbar-toggler order-2" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="wd-20 ht-20 fa fa-ellipsis-v"></i>
        </button>

        <div class="collapse navbar-collapse order-2 ml-2" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#" data-ajax="{'target':'#{{_form}}List','filter_remove': 'inprint'}">Все
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'inprint','filter_add':{'inprint':''}}">Не напечатаны</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'inprint','filter_add':{'inprint':'on'}}">Напечатаны</a>
                </li>
            </ul>
            <form class="form-inline mg-t-10 mg-lg-0">
                <div class="form-group">
                    <input class="form-control mg-r-10 col-auto" type="search" placeholder="Поиск..."
                        aria-label="Поиск..."
                        data-ajax="{'target':'#{{_form}}List','filter_add':{'$or':[{ 'doc_num' : {'$like' : '$value'} }, { 'fullname': {'$like' : '$value'} }]} }">

                    <a href="#" data-ajax="{'url':'/cms/ajax/form/docs/editpeoples/_new','html':'#yongerPeoples modals'}"
                        class="ml-auto order-2 float-right btn btn-primary">
                        <img src="/module/myicons/item-select-plus-add.svg?size=24&stroke=FFFFFF" /> Новый
                    </a>
                </div>
            </form>
        </div>
    </nav>

    <wb-var date="" />
    <wb-var filter="{'inprint':'','_site' : {'$in': [null,'{{_sett.site}}']}}" />
    <wb-var filter="{'inprint':'','_site' : {'$in': [null,'{{_sett.site}}']},'_creator':'{{_sess.user.id}}'}" wb-if="in_array({{_sess.user.role}},['reg','',null])" />
    <table class="table table-striped table-hover tx-15">
        <thead>
            <tr>
                <th>Ф.И.О.</th>
                <th>Паспорт</th>
                <th>Статус</th>
                <th class="text-right">
                    
                <input wb-module="swico" data-size="26"
                        data-ico-on="interface-essential-113" data-ico-off="square"
                        data-color-on="323232" data-color-off="323232"
                        onclick="$(this).parents('table').find('.print[type=checkbox]').prop('checked',$(this).prop('checked'));">
                        <a href="javascript:" class='btn btn-sm btn-success btn-print'><img src="/module/myicons/printer.svg?size=18&stroke=FFFFFF"> Печать</a>
            </tr>
        </thead>
        <tbody id="{{_form}}List">
            <wb-foreach wb="table=docs&sort=_created:d&bind=cms.list.docs&size={{_sett.page_size}}"
                wb-filter="{{_var.filter}}">
                <tr wb-if="'{{_var.date}}'!=='{{date}}'" class="bg-transparent">
                    <td colspan="5">
                        <wb-var date="{{date}}" />
                        <div class="divider-text tx-primary">{{wbDate("d.m.Y",{{{{_created}}}})}}</div>
                    </td>
                </tr>


                <tr>
                    <td>{{fullname}}<br /><small>{{birth_date}}</small></td>
                    <td>{{doc_ser}} №{{doc_num}}<br><small>{{wbdate("Y-m-d H:i:s",{{_created}})}}</small></td>
                    <td>

                        <input wb-module="swico" name="inprint"
                        data-ico-on="printer-print-checkmark" data-ico-off="printer-print-delite"
                        data-color-on="10b759" data-color-off="666666"
                        onchange="wbapp.save($(this),{'table':'{{_form}}','id':'{{_id}}','silent':'true'})">
                    </td>
                    <td class="text-right">
                        <a href="javascript:" class="d-inline">
                            <input wb-module="swico" class="print" data-size="26" data-id="{{_id}}"
                        data-ico-on="interface-essential-113" data-ico-off="square"
                        data-color-on="323232" data-color-off="323232">
                        </a>
                        <a href="javascript:"
                            data-ajax="{'url':'/cms/ajax/form/docs/editpeoples/{{id}}','html':'#yongerPeoples modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232">
                        </a>
                        <a href="javascript:"
                            data-ajax="{'url':'/ajax/rmitem/docs/{{id}}','update':'cms.list.docs','html':'#yongerPeoples modals'}"
                            class="d-inline">
                            <img src="/module/myicons/trash-delete-bin.2.svg?size=24&stroke=323232" class="d-inline">
                        </a>
                    </td>
                </tr>
            </wb-foreach>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <modals></modals>
</div>
    <modals>

    <div class="modal" tabindex="-1" data-backdrop="static" role="dialog" id="modalInprintWait" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ожидайте</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tx-center wait">Идёт подготовка документов...
                        <br>
                        <br>
                        <span class="spinner-border spinner-border" role="status" aria-hidden="true"></span>
                    </div>
                    <div class="tx-center ready d-none">
                        <div>Нажмите кнопку, чтобы скачать архив документов</div>
                        <a class='btn btn-success' href='#' target='_blank'>Скачать</a>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

    </modals>
<script wb-app remove>
        $('#yongerPeoples thead .btn-print').off('click');
        $('#yongerPeoples thead .btn-print').on('click', function() {
            let data = {};
            data.items = [];
            $('#{{_form}}List tr td input.print[data-id]:checked').each(function(i) {
                data.items[i] = $(this).data('id')
            });
            if (data.items.length == 0) {
                wbapp.toast('Предупреждение', 'Необходимо выбрать хотя бы один документ.', {
                    bgcolor: 'danger'
                });
                return false;
            } else {
                $('modals #modalInprintWait').modal('show');
            }
            $.ajax({
                type: 'POST',
                url: '/module/export/inprint/',
                data: data,
                dataType: 'json'
            }).done(function(data) {
                if (data.error == false) {
                    var $a = $('&lt;a>');
                    $a.attr("href", data.pdf);
                    $a.attr("target", "_blank");
                    $a.addClass("d-none");
                    $("body").append($a);
//                    $a.attr("download", "Report.xls");
                    $a[0].click();
                    $a.remove();
                } else {
                    wbapp.toast('Ошибка', 'Что-то пошло не так!', {
                        bgcolor: 'danger',
                        delay: 10000
                    });
                }
                $('modals #modalInprintWait').modal('hide');
            });
        });
</script>
<wb-lang>
    [ru]
    docs = Документы
    search = Поиск
    newDoc = Новый документ
    [en]
    docs = Documents
    search = Search
    newDoc = New document
</wb-lang>

</html>