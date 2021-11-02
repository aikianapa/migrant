<html>
<view>
    <section class="feature-section position-relative section-pt">
        <img class="path-img" src="assets/images/feature/shape.png" alt="images_not_found">
        <div class="container custom-container">
            <div class="row g-0 align-items-center">
                <!-- title section start -->
                <div class="col-xl-6 col-lg-8 mx-auto">
                    <div class="title-section mb-3 text-center">
                        <span class="sub-title">{{top_header1}}</span>
                        <h3 class="title">
                            {{top_header2}}
                        </h3>
                        <p class="mb-10">
                            {{top_text}}
                        </p>
                        <a href="{{top_link}}" class="btn btn-lg btn-dark btn-hover-dark"
                            wb-if="top_button > ''">{{top_button}}</a>
                    </div>
                </div>
                <!-- title section end -->

                <!-- feature start -->
                <div class="col-12">
                    <div id="grid" class="grid row mb-n7">
                        <div class="grid-item mb-7">
                            <div class="feature-card bg-light">
                                <span class="card-shape card-shape-light"></span>
                                <span class="card-shape card-shape-dark"></span>
                                <img class="logo" width="74" height="74" src="assets/images/feature/logo/1.png"
                                    alt="{{header_blk1}}" wb-if="'{{image_blk1.0.img}}' == ''">
                                <img class="logo" width="74" height="74" src="/thumb/74x74/src/{{image_blk1.0.img}}"
                                    alt="{{header_blk1}}" wb-if="'{{image_blk1.0.img}}' > ''">
                                <h4 class="title my-6">
                                    <a href="{{link_blk1}}">{{header_blk1}}</a>
                                </h4>
                                <p>
                                    {{text_blk1}}
                                </p>
                            </div>
                        </div>
                        <div class="grid-item card-mt-75 mb-7">
                            <div class="feature-card bg-light active">
                                <span class="card-shape card-shape-light"></span>
                                <span class="card-shape card-shape-dark"></span>
                                <img class="logo" width="74" height="74" src="assets/images/feature/logo/2.png"
                                    alt="{{header_blk2}}" wb-if="'{{image_blk2.0.img}}' == ''">
                                <img class="logo" width="74" height="74" src="/thumb/74x74/src/{{image_blk2.0.img}}"
                                    alt="{{header_blk2}}" wb-if="'{{image_blk2.0.img}}' > ''">
                                <h4 class="title my-6">
                                    <a href="{{link_blk2}}">{{header_blk2}}</a>
                                </h4>
                                <p>
                                    {{text_blk2}}
                                </p>
                            </div>
                        </div>
                        <div class="grid-item mb-7">
                            <div class="feature-card bg-light">
                                <span class="card-shape card-shape-light"></span>
                                <span class="card-shape card-shape-dark"></span>
                                <img class="logo" width="74" height="74" src="assets/images/feature/logo/3.png"
                                    alt="{{header_blk3}}" wb-if="'{{image_blk3.0.img}}' == ''">
                                <img class="logo" width="74" height="74" src="/thumb/74x74/src/{{image_blk3.0.img}}"
                                    alt="{{header_blk3}}" wb-if="'{{image_blk3.0.img}}' > ''">
                                <h4 class="title my-6">
                                    <a href="{{link_blk3}}">{{header_blk3}}</a>
                                </h4>
                                <p>
                                    {{text_blk3}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- feature end -->
            </div>
        </div>

    </section>
</view>
<edit header="{{_lang.header}}">
    <div>
        <wb-include wb-src="/modules/yonger/common/blocks/common.inc.php" />

        <div class="divider-text">Иконки блоков</div>
        <div class="row">
            <div class="col-md-4">
                <wb-module wb="module=filepicker&mode=single&width=150&height=100" name="image_blk1" />
                <small>Левая</small>
            </div>
            <div class="col-md-4">
                <wb-module wb="module=filepicker&mode=single&width=150&height=100" name="image_blk2" />
                <small>Центральная</small>
            </div>
            <div class="col-md-4">
                <wb-module wb="module=filepicker&mode=single&width=150&height=100" name="image_blk3" />
                <small>Правая</small>
            </div>
        </div>
    </div>

    <wb-multilang wb-lang="{{_sett.locales}}" name="lang">

        <div class="accordion">
            <h6>Верхняя часть</h6>
            <div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label class="form-control-label col-lg-4">Заголовок</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="top_header1" placeholder="Заголовок">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">Подзаголовок</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="top_header2" placeholder="Подзаголовок">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.text}}</label>
                            <div class="col-lg-8 mb-2">
                                <textarea class="form-control" name="top_text" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4">Кнопка/ссылка</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" name="top_button">
                            </div>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" name="top_link">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Левый блок</h6>
            <div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.header}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="header_blk1"
                                    placeholder="{{_lang.header}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.link}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="link_blk1" placeholder="{{_lang.link}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="text_blk1" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Средний блок</h6>
            <div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.header}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="header_blk2"
                                    placeholder="{{_lang.header}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.link}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="link_blk2" placeholder="{{_lang.link}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="text_blk2" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Правый блок</h6>
            <div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.header}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="header_blk3"
                                    placeholder="{{_lang.header}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.link}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="link_blk3" placeholder="{{_lang.link}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="text_blk3" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
            $('.accordion').accordion({
                heightStyle: 'content'
            });
            </script>
        </div>

    </wb-multilang>
    <wb-lang>
[ru]
header = "Особенности (3 блока)"
text = Текст
link = Ссылка
button = Кнопка
[en]
header = "Features (3 blocks)"
text = Text
link = Link
button = Button
    </wb-lang>
</edit>
</html>