<html>
<div class="modal fade effect-scale show removable" id="modalEmployersEdit" data-backdrop="static" tabindex="-1"
    role="dialog" aria-hidden="true" wb-allow="admin,partner">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <div class="col-5">
                    <h5>Работодатель</h5>
                </div>
                <div class="col-7">
                    <h5 class='header'></h5>
                </div>
                <i class="fa fa-close r-20 position-absolute cursor-pointer" data-dismiss="modal"
                    aria-label="Close"></i>
            </div>
            <div class="modal-body pd-20">
                <form class="row" method="post" id="{{_form}}EditForm">
                    <div class="form-group col-7">
                        <label class="form-control-label">Наименование организации</label>
                        <input type="text" name="title" class="form-control" placeholder="Наименование">
                    </div>

                    <div class="form-group col-5">
                        <label class="form-control-label">ИНН</label>
                        <input type="text" wb-mask="999999999999" name="inn" class="form-control" placeholder="ИНН">
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Фамилия</label>
                            <input type="text" name="last_name" class="form-control" required placeholder="Фамилия">
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Имя</label>
                            <input type="text" name="first_name" class="form-control" required placeholder="Имя">
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Отчество</label>
                            <input type="text" name="middle_name" class="form-control" required placeholder="Отчество">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Префикс ЭЦП</label>
                            <div class="input-group">
                                <input type="text" name="sign_prefix" class="form-control" required placeholder="Префикс ЭЦП">
                                <div class="input-group-append">
                                    <span class="input-group-text">/{{date("y")}}/0000</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Ключ ЭЦП</label>
                            <input type="text" name="sign_key" class="form-control" required placeholder="Ключ ЭЦП">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="form-control-label">Действителен до</label>
                            <input type="datepicker" wb-module="datetimepicker" name="sign_expire" class="form-control" required placeholder="Действителен до">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-control-label">Факсимиле</label>
                            <div id="uploadFaximile">
                                <wb-module name="faximile" wb="{
                                'module':'filepicker',
                                'mode':'single',
                                'width':'100',
                                'height':'100',
                                'original': false
                            }" wb-path="/uploads/employers/" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-control-label">Печать</label>
                            <div id="uploadFaximile">
                                <wb-module name="stamp" wb="{
                                'module':'filepicker',
                                'mode':'single',
                                'width':'100',
                                'height':'100',
                                'original': false
                            }" wb-path="/uploads/employers/" />
                            </div>
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