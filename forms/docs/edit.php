<html>
<div class="modal fade effect-scale show removable" id="modalDocsEdit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <div class="col-5">
                    <h5>Документ</h5>
                </div>
                <div class="col-7">
                    <h5 class='header'></h5>
                </div>
                <i class="fa fa-close r-20 position-absolute cursor-pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body pd-20">
                <form class="row" method="post" id="{{_form}}EditForm">
                    <input type="hidden" name="_id">
                    <script>
                        $('#{{_form}}EditForm .accordion').accordion({
                            heightStyle: 'content',
                            collapsible: true
                        });
                        $('#modalDocsEdit').delegate('.btn.print',wbapp.evClick,function(){
                            let data = $('#docsEditForm').serializeJson();
                            let $form = $("<form />");
                            $form.attr('method', 'POST').attr('target', '_blank').attr('action', '/module/printdocx');
                            $.each(data, function(k,v){
                                $form.append('<input type="hidden" name="' + k + '" value="' + v + '">');
                            });
                            console.log($form.html());
                            $form.appendTo('body').submit();
                            $form.remove();
                        })

                    </script>

                    <div class="col-lg-6">

                        <div class="form-group">
                            <label class="form-control-label">Ф.И.О.</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Ф.И.О." required>
                        </div>

                        <div class="accordion">
                            <h6>1. Основное</h6>
                            <div>
                                <div class="form-group">
                                    <label class="form-control-label">Дата рождения</label>
                                    <input type="date"  name="birth_date" class="form-control" placeholder="Дата рождения">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Место рождения</label>
                                    <input type="text" name="birth_place" class="form-control" placeholder="Место рождения">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Пол</label>
                                    <select name="gender" class="form-control" wb-select2 placeholder="Пол">
                                        <option value="М">Мужской</option>
                                        <option value="Ж">Женский</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label class="form-control-label">Гражданство</label>
                                    <select name="citizen" class="form-control select2" placeholder="Гражданство" wb-tree="item=countries&tpl=false">
                                        <option value="{{id}}">{{name}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Эл.почта</label>
                                    <input type="email" name="email" class="form-control" placeholder="Эл.почта">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Телефон</label>
                                    <input wb-module="mask" wb-mask="+7 (999) 999-99-99" name="phone" class="form-control" placeholder="Телефон">
                                </div>

                            </div>
                            <h6>2. Паспорт</h6>
                            <div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Серия</label>
                                        <input type="text" name="doc_ser" class="form-control" placeholder="Серия">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Номер</label>
                                        <input type="number" name="doc_num" class="form-control" placeholder="Номер">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Дата выдачи</label>
                                        <input type="date" name="doc_date" class="form-control" placeholder="Дата выдачи">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Дата окончания</label>
                                        <input type="date" name="doc_expire" class="form-control" placeholder="Дата окончания">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Кем выдан</label>
                                    <input type="text" name="doc_who" class="form-control" placeholder="Кем выдан">
                                </div>

                            </div>
                            <h6>3. Миграционная карта</h6>
                            <div>
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

                            </div>
                            <h6>4. Регистрация</h6>
                            <div>
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
                                    <div class="col-sm-4">
                                        <label class="form-control-label">Дом</label>
                                        <input type="number" name="reg_house" class="form-control" placeholder="Дом">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-control-label">Корпус</label>
                                        <input type="text" name="reg_corpse" class="form-control" placeholder="Корпус">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-control-label">Квартира</label>
                                        <input type="number" name="reg_flat" class="form-control" placeholder="Квартира">
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-control-label">Код конверта с картой</label>
                            <input type="text" name="code" class="form-control" placeholder="Код конверта с картой">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Источник</label>
                            <select name="team" class="form-control select2" placeholder="От кого" wb-tree="item=teams">
                                <option value="{{id}}">{{name}}</option>
                            </select>
                        </div>

                        <h5>Исходные</h5>
                        <div>
                            <wb-module name="attaches" wb="{
                                'module':'filepicker',
                                'mode':'multi',
                                'original': false
                            }" wb-path="/uploads/sources/{{wbDate()}}" />

                        </div>

                        <h5>Действия</h5>
                        <div class="form-group">
                            <a href="#" class="btn btn-outline-primary my-2">Обработать</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer pd-x-20 pd-b-20 pd-t-0 bd-t-0">
                <wb-include wb="{'form':'common_formsave.php'}" />
                <a href="javascript:void(0)" class="btn btn-primary print"><svg wb-module="myicons" class="mi mi-printer size-20" stroke="FFFFFF"></svg>&nbsp;Печать</a>                
            </div>
        </div>
    </div>
</div>

</html>