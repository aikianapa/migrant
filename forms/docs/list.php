<html>
<div class="m-3" id="yongerDocs" wb-allow="admin,partner,reg">

    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">{{_lang.docs}}</h3>
        <button class="navbar-toggler order-2" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="wd-20 ht-20 fa fa-ellipsis-v"></i>
        </button>

        <div class="collapse navbar-collapse order-2" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#" data-ajax="{'target':'#{{_form}}List','filter_remove': 'status'}">Все
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'status','filter_add':{'status':'new'}}">Новые</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'status','filter_add':{'status':'progress'}}">В
                        работе</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'status','filter_add':{'status':'ready'}}">Готовые</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#{{_form}}List','filter_remove': 'status','filter_add':{'status':'archive'}}">Архив</a>
                </li>
            </ul>
            <form class="form-inline mg-t-10 mg-lg-0">
                <div class="form-group">
                    <input class="form-control mg-r-10 col-auto" type="search" placeholder="Поиск..."
                        aria-label="Поиск..."
                        data-ajax="{'target':'#{{_form}}List','filter_add':{'$or':[{ 'doc_num' : {'$like' : '$value'} }, { 'fullname': {'$like' : '$value'} }]} }">

                    <a href="#" data-ajax="{'url':'/cms/ajax/form/docs/edit/_new','html':'#yongerDocs modals'}"
                        class="ml-auto order-2 float-right btn btn-primary">
                        <img src="/module/myicons/item-select-plus-add.svg?size=24&stroke=FFFFFF" /> {{_lang.newDoc}}
                    </a>
                </div>
            </form>
        </div>
    </nav>

    <wb-var date="" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']}}" />
    <wb-var filter="{'_site' : {'$in': [null,'{{_sett.site}}']},
            '$or' : [
                {'_role':'reg'},
                {'_creator':'{{_sess.user.id}}'}
            ]
    }" wb-if="in_array({{_sess.user.role}},['partner','',null])" />
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
                wb-filter="{{_var.filter}}">
                <tr wb-if="'{{_var.date}}'!=='{{date}}'" class="bg-transparent">
                    <td colspan="5">
                        <wb-var date="{{date}}" />
                        <div class="divider-text tx-primary">{{wbDate("d.m.Y",{{{{_created}}}})}}</div>
                    </td>
                </tr>


                <tr>
                    <td>{{fullname}}<br /><small>{{birth_date}}</small></td>
                    <td>{{doc_ser}} №{{doc_num}}</td>
                    <td>{{code}}</td>
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
                            data-ajax="{'url':'/cms/ajax/form/docs/edit/{{id}}','html':'#yongerDocs modals'}"
                            class="d-inline">
                            <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232">
                        </a>
                        <a href="javascript:"
                            data-ajax="{'url':'/ajax/rmitem/docs/{{id}}','update':'cms.list.docs','html':'#yongerDocs modals'}"
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