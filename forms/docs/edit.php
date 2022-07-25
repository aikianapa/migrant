<html>
<div class="modal fade effect-scale show removable" id="modalDocsEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" wb-allow="admin,partner,reg,doc">
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
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="form-control-label col-sm-3">Ф.И.О.</label>
                            <div class="col-sm-9">
                                <input type="text" name="fullname" class="form-control" placeholder="Ф.И.О." required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-control-label col-sm-3">Метка</label>
                            <div class="col-sm-9">
                                <span wb="module=switch" name="flag"></span>
                            </div>
                        </div>

                        <div class="accordion">
                            <h6>1. Основное</h6>
                            <div>
                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Дата рождения</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="birth_date" class="form-control" required placeholder="Дата рождения">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Место рождения</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="birth_place" class="form-control" placeholder="Место рождения">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Пол</label>
                                    <div class="col-sm-8">
                                        <select name="gender" class="form-control" wb-select2 placeholder="Пол">
                                            <option value="М">Мужской</option>
                                            <option value="Ж">Женский</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Гражданство</label>
                                    <div class="col-sm-8">
                                        <select name="citizen" class="form-control select2" placeholder="Гражданство" wb-tree="item=countries&tpl=false">
                                            <option value="{{id}}">{{name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Эл.почта</label>
                                    <div class="col-sm-8">
                                        <input type="email" name="email" class="form-control" placeholder="Эл.почта">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Телефон</label>
                                    <div class="col-sm-8">
                                        <input wb-module="mask" wb-mask="89999999999" name="phone" class="form-control" placeholder="Телефон">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-control-label col-sm-4">Телефон (дополнительный)</label>
                                    <div class="col-sm-8">
                                        <input wb-module="mask" wb-mask="89999999999" name="phone_alt" class="form-control" placeholder="Телефон">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label">Срок пребывания</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_out" class="form-control" placeholder="Срок пребывания" required>
                                    </div>
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
                                    <div class="form-group col-sm-12">
                                        <label class="form-control-label">Регион</label>
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
                        </div>



                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="form-control-label col-sm-4">Код конверта с картой</label>
                            <div class="col-sm-8">
                                <input type="text" name="code" class="form-control" placeholder="Код конверта с картой">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-control-label col-sm-4">Источник</label>
                            <div class="col-sm-8">
                                <select name="team" class="form-control select2" placeholder="От кого" wb-tree="item=teams">
                                    <option value="{{id}}">{{name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="divider-text">Исходные документы</div>
                        <p class="tx-12">Загрузите PDF файл с исходными документами и <a href="javascript:void(0);" onclick="$('#modalDocsEdit .btn.print').trigger('click');">распечатайте Договор.</p>
                        <div id="uploadSources">
                            <wb-module name="attaches" wb="{
                                'module':'filepicker',
                                'mode':'single',
                                'width':'120',
                                'height':'100',
                                'original': false
                            }" wb-path="/uploads/sources/{{wbDate()}}" />
                            <textarea class="d-none" type="json" name="sources"></textarea>
                        </div>
                        <div id="listSources" class="row mt-2" wb="module=photoswipe&imgset=migreg">
                            <div class="srcList">
                                {{#each srcList}}
                                    <a data-href="{{.}}?{{~/uid}}" class="col-3">
                                        <img data-src="/thumbc/70x70/src{{.}}?{{~/uid}}" class="mb-2">
                                    </a>
                                {{/each}}
                            </div>
                        </div>

                        <div class="divider-text">Договор</div>
                        <p class="tx-12">Загрузите PDF файл с подписанным <a href="javascript:void(0);" id="docViewPdf">Договором</a>.</p>
                        <div id="uploadOrder">
                            <wb-module name="order" wb="{
                                'module':'filepicker',
                                'width':'100',
                                'height':'60',
                                'original': false
                            }" wb-ext="pdf" wb-path="/uploads/sources/{{wbDate()}}" />
                        </div>
                        <input type="hidden" name="_created">
                        <!--
                        <div class="divider-text">Действия</div>
                        <div class="form-group">
                            <a href="#" class="btn btn-outline-primary my-2">Обработать</a>
                        </div>
-->
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
<script wbapp remove src="/forms/docs/edit.js"></script>

</html>