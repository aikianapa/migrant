<html>
<div class="modal fade effect-scale show removable" id="modalPeoplesEdit" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" data-id="{{id}}">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <div class="col-5">
                    <h5>Мигрант</h5>
                </div>
                <div class="col-7">
                    <h5 class='header'></h5>
                </div>
                <i class="fa fa-close r-20 position-absolute cursor-pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body pd-20">
                <form class="row" method="post" id="{{_form}}EditForm">
                    <meta name="scan" content="{{_route.params.scan}}">
                    <input type="hidden" name="inprint">
                    <input type="hidden" name="status" value='new' wb-if="'{{status}}'==''">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Фамилия</label>
                            <input type="text" name="last_name" class="form-control" required placeholder="Фамилия">
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Имя</label>
                            <input type="text" name="first_name" class="form-control" required placeholder="Имя">
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Отчество</label>
                            <input type="text" name="middle_name" class="form-control" placeholder="Отчество">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Пол</label>
                            <select name="gender" class="form-control" wb-select2 placeholder="Пол">
                                <option value="М">Мужской</option>
                                <option value="Ж">Женский</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Гражданство</label>
                            <select name="citizen" class="form-control select2" required placeholder="Гражданство" wb-tree="item=countries&tpl=false">
                                <option value="{{id}}">{{name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Дата рождения</label>
                            <input type="date" name="birth_date" class="form-control" required placeholder="Дата рождения">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">Место рождения</label>
                            <input type="text" name="birth_place" class="form-control" placeholder="Место рождения">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Документ</label>
                            <select name="doc_type" readonly class="form-control select2" placeholder="Документ">
                                <option value="Паспорт">Паспорт</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-control-label">Серия</label>
                                    <input type="text" name="doc_ser" class="form-control" placeholder="Серия">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label class="form-control-label">Номер</label>
                                    <input type="number" name="doc_num" class="form-control" placeholder="Номер" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Дата выдачи</label>
                            <input type="date" name="doc_date" class="form-control" placeholder="Дата выдачи" required>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Дата окончания</label>
                            <input type="date" name="doc_expire" class="form-control" placeholder="Дата окончания">
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="form-control-label">Место пребывания</label>
                            <select name="place" class="form-control select2" placeholder="Место пребывания">
                                <option value="" data-employer="">Ввод вручную</option>
                                <wb-foreach wb="table=places">
                                    <option value="{{id}}" data-employer="{{employer}}">{{title}}</option>
                                </wb-foreach>
                            </select>
                            <input type="hidden" name="employer">
                        </div>
                    </div>
                    <div class="col-sm-1">
                    <label class="form-control-label">&nbsp;</label>
                        <a href="javascript:void(0)" class="btn btn-primary btn-address-toggle"><i class="fa fa-home white"></i></a>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">Срок пребывания</label>
                            <input type="date" name="mc_expire" class="form-control" required placeholder="Срок пребывания" required>
                        </div>
                    </div>

                    <div class="col-12 address d-none" >
                        <div class="divider-text">Миграционная карта</div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Серия</label>
                                <input type="text" name="mc_ser" class="form-control" placeholder="Серия">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="number" name="mc_num" class="form-control" placeholder="Номер">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Дата выдачи</label>
                                <input type="date" name="mc_date" class="form-control" placeholder="Дата выдачи">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Дата окончания</label>
                                <input type="date" name="mc_expire" class="form-control" placeholder="Дата окончания">
                            </div>
                        </div>


                        <div class="divider-text">Регистрация</div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-control-label">Область</label>
                                <input type="text" name="region" class="form-control" placeholder="Область">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label class="form-control-label">Тип</label>
                                <input type="text" name="reg_city_type" class="form-control" placeholder="Тип">
                            </div>

                            <div class="form-group col-sm-9">
                                <label class="form-control-label">Населённый пункт</label>
                                <input type="text" name="reg_city" class="form-control" placeholder="Населённый пункт">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label class="form-control-label">Тип</label>
                                <input type="text" name="reg_street_type" class="form-control" placeholder="Тип улицы">
                            </div>

                            <div class="form-group col-sm-9">
                                <label class="form-control-label">Название улицы</label>
                                <input type="text" name="reg_street" class="form-control" placeholder="Название улицы">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-control-label">Тип</label>
                                <input type="text" name="reg_house" class="form-control" placeholder="Дом/участок/владение">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="text" name="reg_house_num" class="form-control" placeholder="Номер дома">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-control-label">Корпус</label>
                                <input type="text" name="reg_corpse" class="form-control" placeholder="Корпус">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-control-label">Строение</label>
                                <input type="text" name="reg_build" class="form-control" placeholder="Строение">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-control-label">Тип (кв/комн/пом)</label>
                                <input type="text" name="reg_flat" class="form-control" placeholder="Тип помещения">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="text" name="reg_flat_num" class="form-control" placeholder="Номер помещения">
                            </div>
                        </div>
                    </div>
                    <textarea type="json" class="d-none" name="sources"></textarea>
                </form>
                <div class="divider-text">Сканы</div>
                <div class="row mt-3" wb="module=photoswipe&imgset=migreg">
                    <wb-foreach wb="from=sources&tpl=false">
                        <a href="{{_val}}" class="col-3" wb-if="_val > ''">
                            <img data-src="/thumbc/100x150/src{{_val}}">
                        </a>
                    </wb-foreach>
                </div>

            </div>
            <div class="modal-footer pd-x-20 pd-b-20 pd-t-0 bd-t-0">
                <wb-include wb="{'form':'common_formsave.php'}" />
            </div>
        </div>
    </div>
</div>
<script wb-app remove>
    (function($){
    $('#modalPeoplesEdit select[name=place]').on('select2:select', function(e) {
        let data = e.params.data;
        let employer = $(data.element).attr('data-employer');
        $('#modalPeoplesEdit [name=employer]').val(employer).attr('value', employer);
    })

    $('#modalPeoplesEdit').delegate('.btn-save', 'wb-save-done', function(ev, data) {
        if ($('#docsEditForm meta[name=scan]').attr('content') == "true") {
            $('#scansList tr[data-id="' + data.data.id + '"]').remove();
        }
    })

    $('#modalPeoplesEdit').delegate('.btn-address-toggle', 'click', function(ev, data) {
        if ($('#modalPeoplesEdit div.address').hasClass('d-none')) {
            $('#modalPeoplesEdit [name=employer]').val('')
            $('#modalPeoplesEdit [name=place]').val('').trigger('change')

        }
        $('#modalPeoplesEdit div.address').toggleClass('d-none')
    })

    let ser = $('#docsEditForm [name=doc_ser]').val();
    let num = $('#docsEditForm [name=doc_num]').val();

    if ($('#docsEditForm meta[name=scan]').attr('content') == "true" && num > '') {
        wbapp.post('/api/v2/list/docs/?@return=doc_ser;doc_num&@limit=1', {
            'filter': {
                'doc_ser': ser,
                'doc_num': num
            }
        }, function(data) {
            if (data[0] !== undefined) {
                let sernum = data[0].doc_ser + '' + data[0].doc_num;
                if ((ser + '' + num) == sernum) {
                    $('#modalPeoplesEdit .btn-save').remove();
                    wbapp.toast('Внимание!', 'Данный номер паспорта уже зарегистрирован в системе!', {
                        'bgcolor': 'danger',
                        'delay': 9999
                    });
                }
            }
        })
    }
})
</script>

</html>