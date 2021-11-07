<html>
<div class="m-3" id="yongerQuotes">
    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">{{_lang.docs}}</h3>
        <a href="#" data-ajax="{'url':'/cms/ajax/form/docs/edit/_new','html':'#yongerQuotes modals'}"
            class="ml-auto order-2 float-right btn btn-primary">
            <img src="/module/myicons/item-select-plus-add.svg?size=24&stroke=FFFFFF" /> {{_lang.newDoc}}
        </a>
    </nav>

    <table class="table table-striped table-hover tx-15">
        <thead>
            <tr>
                <th>Ф.И.О.</th>
                <th>Паспорт</th>
                <th>Код</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="docsList">
            <wb-foreach wb="table=docs&sort=_created:d&bind=cms.list.docs&sort=_created:d&size={{_sett.page_size}}"
                wb-filter="{'login':'{{_sess.user.login}}' }">
                <tr>
                    <td>{{fullname}}<br/><small>{{wbDate("d.m.Y",{{birth_date}})}}</small></td>
                    <td>{{doc_ser}} №{{doc_num}}</td>
                    <td>{{code}}</td>
                    <td>
                        <img data-src="/module/myicons/pill-clock.svg?size=24&stroke=666666" wb-if="'{{order.0.img}}' == ''">
                        <img data-src="/module/myicons/checkmark-circle-1.svg?size=24&stroke=10b759" wb-if="'{{order.0.img}}' > '' AND '{{code}}'>''">
                    </td>
                    <td>
                        <a href="javascript:"
                            data-ajax="{'url':'/cms/ajax/form/docs/edit/{{id}}','html':'#yongerQuotes modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232">
                        </a>
                        <a href="javascript:"
                            data-ajax="{'url':'/ajax/rmitem/docs/{{id}}','update':'cms.list.docs','html':'#yongerQuotes modals'}"
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
<script wb-app>

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