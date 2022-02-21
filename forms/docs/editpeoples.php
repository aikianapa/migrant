<html>
<div class="modal fade effect-scale show removable" id="modalPeoplesEdit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-hidden="true">
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
                            <input type="text" name="middle_name" class="form-control" required placeholder="Отчество">
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
                            <select name="citizen" class="form-control select2" placeholder="Гражданство" wb-tree="item=countries&tpl=false">
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

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Страна рождения</label>
                            <input type="text" name="birth_place" class="form-control" placeholder="Страна рождения">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="form-control-label">Место рождения</label>
                            <input type="text" name="birth_city" class="form-control" placeholder="Место рождения">
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

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Место пребывания</label>
                            <select name="place" class="form-control select2" placeholder="Место пребывания" required>
                                <wb-foreach wb="table=places">
                                    <option value="{{id}}">{{title}}</option>
                                </wb-foreach>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Работодатель</label>
                            <select name="employer" class="form-control select2" placeholder="Работодатель" required>
                                <wb-foreach wb="table=employers">
                                <option value="{{id}}">{{title}}</option>
                                </wb-foreach>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Срок пребывания</label>
                            <input type="date" name="mc_expire" class="form-control" required placeholder="Срок пребывания" required>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer pd-x-20 pd-b-20 pd-t-0 bd-t-0">
                <wb-include wb="{'form':'common_formsave.php'}" />
            </div>
        </div>
    </div>
</div>


</html>