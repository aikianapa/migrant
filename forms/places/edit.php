<html>
<div class="modal fade effect-scale show removable" id="modalPlacesEdit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <div class="col-5">
                    <h5>Адрес</h5>
                </div>
                <div class="col-7">
                    <h5 class='header'></h5>
                </div>
                <i class="fa fa-close r-20 position-absolute cursor-pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body pd-20">
                <form class="row" method="post" id="{{_form}}EditForm">
                            <div class="d-none"><select wb-select2></select></div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label class="form-control-label">Наименование</label>
                                        <input type="text" name="title" class="form-control" placeholder="Наименование">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Тип помещения</label>
                                        <select name="place" class="form-control select2" required wb-tree="item=locations&tpl=false&branch=square_type&parent=false">
                                        <option value="{{name}}">{{name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-control-label">Область</label>
                                        <input type="text" name="region" class="form-control" placeholder="Область/край/республика">
                                        <!--select name="region" class="form-control select2" wb-tree="item=locations&tpl=false&branch=regions&parent=false" placeholder="Область/край/республика">
                                            <option value="{{name}}">{{name}}</option>
                                        </select-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label class="form-control-label">Тип</label>
                                        <select name="reg_city_type" class="form-control select2" required wb-tree="item=locations&tpl=false&branch=city_type&parent=false">
                                        <option value="{{data.short}}">{{name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-9">
                                        <label class="form-control-label">Населённый пункт</label>
                                        <input type="text" name="reg_city" class="form-control" placeholder="Населённый пункт">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label class="form-control-label">Тип</label>
                                        <select name="reg_street_type" class="form-control select2" required wb-tree="item=locations&tpl=false&branch=street_type&parent=false">
                                        <option value="{{data.short}}">{{name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-9">
                                        <label class="form-control-label">Название улицы</label>
                                        <input type="text" name="reg_street" class="form-control" placeholder="Название улицы">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Тип</label>
                                        <input type="text" name="reg_house" class="form-control" placeholder="Тип">
                                        <!--select name="reg_house" class="form-control select2" required wb-tree="item=locations&tpl=false&branch=obj_type&parent=false">
                                        <option value="{{data.short}}">{{name}}</option>
                                        </select-->
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
                                        <input type="text" name="reg_flat" class="form-control" placeholder="Тип (кв/комн/пом)">
                                        <!--select name="reg_flat" class="form-control select2" required wb-tree="item=locations&tpl=false&branch=flat_type&parent=false">
                                        <option value="{{data.short}}">{{name}}</option>
                                        </select-->
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Номер</label>
                                        <input type="text" name="reg_flat_num" class="form-control" placeholder="Номер помещения">
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