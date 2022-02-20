<html>
<div class="m-3" id="yongerPlaces" wb-allow="admin,partner">

    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Адреса</h3>
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

                    <a href="#" data-ajax="{'url':'/cms/ajax/form/places/edit/_new','html':'#yongerPlaces modals'}"
                        class="ml-auto order-2 float-right btn btn-primary">
                        <img src="/module/myicons/item-select-plus-add.svg?size=24&stroke=FFFFFF" /> Новый
                    </a>
                </div>
            </form>
        </div>
    </nav>

    <wb-var date="" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']}}" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']},'_creator':'{{_sess.user.id}}'}" wb-if="in_array({{_sess.user.role}},['partner','',null])" />
    <table class="table table-striped table-hover tx-15">
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Адрес</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="placesList">
            <wb-foreach wb="table=places&sort=_created:d&bind=cms.list.places&sort=_created:d&size={{_sett.page_size}}"
                wb-filter="{{_var.filter}}">

                <tr>
                    <td>{{title}}</td>
                    <td>{{address}}</td>
                    <td>
                        <img data-src="/module/myicons/thunder-lightning-circle.1.svg?size=24&stroke=666666"
                            wb-if="'{{status}}' == 'new'">
                        <img data-src="/module/myicons/loading-checkmark-status-circle.svg?size=24&stroke=ffc107"
                            wb-if="'{{status}}' == 'progress'">
                        <img data-src="/module/myicons/checkmark-circle-1.svg?size=24&stroke=10b759"
                            wb-if="'{{status}}' == 'ready'">
                        
                        <input wb-module="swico" name="archive" wb-if="'{{status}}' == 'archive'"
                        data-ico-on="zip-archive-circle" data-ico-off="checkmark-circle-1"
                        data-color-on="dc3545" data-color-off="10b759"
                        onchange="wbapp.save($(this),{'table':'{{_form}}','id':'{{_id}}','field':'archive','silent':'true'})">


                        <!--img data-src="/module/myicons/zip-archive-circle.svg?size=24&stroke=dc3545"
                            wb-if="'{{status}}' == 'archive'"-->
                    </td>
                    <td>
                        <a href="javascript:"
                            data-ajax="{'url':'/cms/ajax/form/places/edit/{{id}}','html':'#yongerPlaces modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232">
                        </a>
                        <a href="javascript:"
                            data-ajax="{'url':'/ajax/rmitem/places/{{id}}','update':'cms.list.places','html':'#yongerPlaces modals'}"
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
    places = Документы
    search = Поиск
    newDoc = Новый документ
    [en]
    places = Documents
    search = Search
    newDoc = New document
</wb-lang>

</html>