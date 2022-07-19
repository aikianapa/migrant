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

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">Код конверта с картой</label>
                            <input type="text" name="code" class="form-control" placeholder="Код конверта с картой" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">Телефон</label>
                            <input wb-module="mask" wb-mask="89999999999" name="phone" class="form-control" placeholder="Телефон" required>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Фамилия</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Фамилия" required>
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Имя</label>
                            <input type="text" name="first_name" class="form-control" placeholder="Имя" required>
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
                            <select name="gender" class="form-control" wb-select2 placeholder="Пол" required>
                                <option value="М">Мужской</option>
                                <option value="Ж">Женский</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Гражданство</label>
                            <select name="citizen" class="form-control select2" placeholder="Гражданство" wb-tree="item=countries&tpl=false" required>
                                <option value="{{id}}">{{name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Дата рождения</label>
                            <input type="date" name="birth_date" class="form-control" placeholder="Дата рождения" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-control-label">Место рождения</label>
                            <input type="text" name="birth_place" class="form-control" placeholder="Место рождения" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divider-text">Паспорт</div>
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

                    <div class="col-12">


                        <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Кем выдан</label>
                            <div class="col-sm-9">
                                <input type="text" name="doc_who" class="form-control" placeholder="Кем выдан" required>
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Регистрация</label>
                            <div class="col-sm-3">
                                <wb-module wb="module=switch" name="reg_flag" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 location">
                        <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Место пребывания</label>
                            <div class="col-sm-9">
                                <select name="place" class="form-control select2" placeholder="Место пребывания" data-required>
                                    <option value="" data-employer="">Ввод вручную</option>
                                    <wb-foreach wb="table=places">
                                        <option value="{{id}}" data-employer="{{employer}}">{{title}}</option>
                                    </wb-foreach>
                                </select>
                                <input type="hidden" name="employer">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-3 form-control-label">Срок пребывания</label>
                            <div class="col-sm-9">
                                <input type="date" name="mc_expire" class="form-control" placeholder="Срок пребывания" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 address d-none">
                        <div class="divider-text">Регистрация</div>
                        <div class="form-group row">
                            <label class=" col-sm-3 form-control-label">Регион</label>
                            <div class="col-sm-9">
                                <select name="region" class="form-control select2" wb-tree="dict=locations&branch=regions&parent=false">
                                    <option value="{{name}}">{{name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-3">
                                Населённый пункт
                            </div>
                            <div class="form-group col-sm-4">
                                <select name="reg_city_type" class="form-control select2" wb-tree="item=locations&tpl=false&branch=city_type&parent=false" data-required>
                                    <option value="{{data.short}}">{{name}}</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-5">
                                <input type="text" name="reg_city" class="form-control" placeholder="Населённый пункт" data-required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                Название улицы
                            </div>
                            <div class="form-group col-sm-4">
                                <select name="reg_street_type" class="form-control select2" wb-tree="item=locations&tpl=false&branch=street_type&parent=false" data-required>
                                    <option value="{{data.short}}">{{name}}</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-5">
                                <input type="text" name="reg_street" class="form-control" placeholder="Название улицы" data-required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="form-control-label">Тип</label>
                                <select name="reg_house" placeholder="Дом/участок/владение" class="form-control select2" wb-tree="item=locations&tpl=false&branch=obj_type&parent=false" data-required>
                                    <option value="{{data.short}}">{{name}}</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="text" name="reg_house_num" class="form-control" placeholder="Номер дома" data-required>
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
                                <select name="reg_flat" class="form-control select2" placeholder="Тип помещения" wb-tree="item=locations&tpl=false&branch=flat_type&parent=false">
                                    <option value="{{data.short}}">{{name}}</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="text" name="reg_flat_num" class="form-control" placeholder="Номер помещения">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="divider-text">Миграционная карта</div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Серия</label>
                                <input type="text" name="mc_ser" class="form-control" placeholder="Серия" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Номер</label>
                                <input type="number" name="mc_num" class="form-control" placeholder="Номер" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Дата выдачи</label>
                                <input type="date" name="mc_date" class="form-control" placeholder="Дата выдачи" required>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-control-label">Дата окончания</label>
                                <input type="date" name="mc_expire" class="form-control" placeholder="Дата окончания" required>
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

    $('#modalPeoplesEdit').delegate('[name=reg_flag]', 'change', function(ev, data) {
        if ($('#modalPeoplesEdit div.address').hasClass('d-none')) {
            $('#modalPeoplesEdit [name=employer]').val('')
            $('#modalPeoplesEdit [name=place]').val('').trigger('change')
        }
        $('#modalPeoplesEdit div.address').toggleClass('d-none')
        $('#modalPeoplesEdit div.location').toggleClass('d-none')
        $('#modalPeoplesEdit [data-required]:visible').prop('required', true)
        $('#modalPeoplesEdit [data-required]:not(:visible)').prop('required', false)
    })

    $('#modalPeoplesEdit [data-required]:visible').prop('required', true)
    $('#modalPeoplesEdit [data-required]:not(:visible)').prop('required', false)
    var ser = $('#docsEditForm [name=doc_ser]').val();
    var num = $('#docsEditForm [name=doc_num]').val();

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
</script>

</html>