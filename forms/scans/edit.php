<html>
<div class="modal fade effect-scale show removable" id="modalPlacesEdit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header row">
                <div class="col-5">
                    <h5>Загрузка сканов</h5>
                </div>
                <div class="col-7">
                    <h5 class='header'></h5>
                </div>
                <i class="fa fa-close r-20 position-absolute cursor-pointer" data-dismiss="modal" aria-label="Close"></i>
            </div>
            <div class="modal-body pd-20">
                <form class="row" method="post" id="{{_form}}EditForm">
                    <div class="col-12">
                        <div class="divider-text">Паспорт</div>              
                    </div>

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

                            <div class="col-6 col-md-3">
                                <div class="divider-text">Паспорт</div>
                                <div id="uploadScan1" class="text-center">
                                    <wb-module name="img1" wb="{
                                        'module':'filepicker',
                                        'width':'200',
                                        'height':'200',
                                        'mode':'single',
                                        'original': true
                                    }" wb-ext="jpg,jpeg,png,webp" wb-path='/uploads/sources/{{wbDate("Ymd")}}' />
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="divider-text">Мигр.карта</div>
                                <div id="uploadScan2">
                                    <wb-module name="img2" wb="{
                                        'module':'filepicker',
                                        'width':'200',
                                        'height':'200',
                                        'mode':'single',
                                        'original': true
                                    }" wb-ext="jpg,jpeg,png,webp" wb-path='/uploads/sources/{{wbDate("Ymd")}}' />
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="divider-text">Рег. 1</div>
                                <div id="uploadScan3">
                                    <wb-module name="img3" wb="{
                                        'module':'filepicker',
                                        'width':'200',
                                        'height':'200',
                                        'mode':'single',
                                        'original': true
                                    }" wb-ext="jpg,jpeg,png,webp" wb-path='/uploads/sources/{{wbDate("Ymd")}}' />
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="divider-text">Рег. 2</div>
                                <div id="uploadScan4">
                                    <wb-module name="img4" wb="{
                                        'module':'filepicker',
                                        'width':'200',
                                        'height':'200',
                                        'mode':'single',
                                        'original': true
                                    }" wb-ext="jpg,jpeg,png,webp" wb-path='/uploads/sources/{{wbDate("Ymd")}}' />
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