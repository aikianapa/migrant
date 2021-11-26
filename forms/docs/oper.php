<html>
    <div class="m-3" id="yongerDocs" wb-allow="admin,oper">
    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Список клиентов</h3>

        <div class="collapse navbar-collapse order-2" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#docsListOper','filter': 'clear','filter_add':{'status':'archive'}}">Свободные</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#"
                        data-ajax="{'target':'#docsListOper','filter':{'status':'archive','oper':'{{_sess.user.id}}'}}">Обработанные</a>
                </li>
            </ul>
            <form class="form-inline mg-t-10 mg-lg-0">
                <div class="form-group">
                    <input class="form-control mg-r-10 col-auto" type="search" placeholder="Поиск..."
                        aria-label="Поиск..."
                        data-ajax="{'target':'#docsListOper','filter_add':{'$or':[{ 'doc_num' : {'$like' : '$value'} }, { 'fullname': {'$like' : '$value'} }]} }">

                    <a href="#" data-ajax="{'url':'/cms/ajax/form/docs/edit/_new','html':'#yongerDocs modals'}"
                        class="ml-auto order-2 float-right btn btn-primary">
                        <img src="/module/myicons/item-select-plus-add.svg?size=24&stroke=FFFFFF" /> {{_lang.newDoc}}
                    </a>
                </div>
            </form>
        </div>

    </nav>


    <table class="table table-striped table-hover tx-15">
        <thead>
            <tr>
                <th>Ф.И.О.</th>
                <th>Паспорт</th>
                <th>Телефон</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="docsListOper">
            <wb-foreach wb="table=docs&sort=_created:d&bind=cms.list.operdocs&sort=_created:d&size={{_sett.page_size}}"
                wb-filter="{
                    'status': 'archive',
                    'oper': {'$lt':' '}
                }">
                <tr wb-if="'{{_var.date}}'!=='{{date}}'" class="bg-transparent">
                    <td colspan="5">
                        <wb-var date="{{date}}" />
                        <div class="divider-text tx-primary">{{wbDate("d.m.Y",{{{{_created}}}})}}</div>
                    </td>
                </tr>
                <tr>
                    <td>{{fullname}}<br /><small>{{wbDate("d.m.Y",{{birth_date}})}}</small></td>
                    <td>{{doc_ser}} №{{doc_num}}</td>
                    <td>{{phone}}</td>
                    <td>
                        <img data-src="/module/myicons/zip-archive-circle.svg?size=24&stroke=10b759"
                            wb-if="'{{status}}' == 'archive' AND '{{oper}}'==''">
                            <img data-src="/module/myicons/zip-archive-circle.svg?size=24&stroke=dc3545"
                            wb-if="'{{status}}' == 'archive' AND '{{oper}}'!==''">
                    </td>
                    <td>
                        <img src="/module/myicons/content-edit-pen.svg?size=24&stroke=323232" class="cursor-pointer"
                        data-id="{{id}}" data-oper="{{_sess.user.id}}" onClick="operGetWork(this)">
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
    </div>
    <script>
        var operGetWork = function(that) {
            let data = $(that).data();
            wbapp.post('/cms/ajax/form/docs/opergetwork',data,function(res){
                if (res.error) {
                    wbapp.toast('Внимание!','Данный клиент уже взят в работу другим оператором.',{bgcolor:'warning'});
                    wbapp.storage('cms.list.operdocs.'+data.id, null);
                } else {
                    window.open(res.pdf, '_blank');
                }
            });
        }
    </script>
</html>