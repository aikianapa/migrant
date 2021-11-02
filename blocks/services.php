<html>
<view>
    <section class="service-section section-pt position-relative">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-8 mx-auto">
                    <div class="title-section mb-10 pb-10 text-center">
                        <span class="sub-title">{{top_header1}}</span>
                        <h3 class="title">
                            {{top_header2}}
                        </h3>
                        <p>{{top_text}}</p>
                    </div>
                </div>
            </div>

            <!-- second row start -->
            <div class="row mb-n7 align-items-center">
                <div class="col-md-6 col-xl-4 mb-7">
                    <div class="service-media-wrapper media-spacing-left">
                        <wb-foreach wb-from="left">
                            <div class="service-media">
                                <img class="logo" width="74" height="74" src="assets/images/service/icon/1.png"
                                    alt="{{header}}" wb-if="'{{image.0.img}}' == ''">
                                <img class="logo" width="74" height="74" src="/thumb/74x74/src/{{image.0.img}}"
                                    alt="{{header}}" wb-if="'{{image.0.img}}' > ''">
                                <div class="service-media-body">
                                    <h4 class="title">
                                        <a href="{{link}}">
                                            <p>{{header}}</p>
                                        </a>
                                    </h4>
                                    <p>{{text}}</p>
                                </div>
                            </div>
                        </wb-foreach>
                    </div>
                </div>
                <div class="col-xl-4 mb-7 order-md-1 order-xl-0">
                    <div class="service-media-img text-center">
                        <img class="logo" width="400" height="384" src="assets/images/service/media.png"
                            alt="{{top_header1}}" wb-if="'{{image.0.img}}' == ''">
                        <img class="logo" width="400" height="384" src="/thumb/400x384/src/{{image.0.img}}"
                            alt="{{top_header1}}" wb-if="'{{image.0.img}}' > ''">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 mb-7">
                    <div class="service-media-wrapper media-spacing-right">
                        <wb-foreach wb-from="right">
                            <div class="service-media">
                                <img class="logo" width="74" height="74" src="assets/images/service/icon/1.png"
                                    alt="{{header}}" wb-if="'{{image.0.img}}' == ''">
                                <img class="logo" width="74" height="74" src="/thumb/74x74/src/{{image.0.img}}"
                                    alt="{{header}}" wb-if="'{{image.0.img}}' > ''">
                                <div class="service-media-body">
                                    <h4 class="title"><a href="{{link}}">{{header}}</a></h4>
                                    <p>{{text}}</p>
                                </div>
                            </div>
                        </wb-foreach>
                    </div>

                </div>

            </div>
            <!-- second row end -->
        </div>

    </section>
</view>
<edit header="{{_lang.header}}">
    <div>
        <wb-include wb-src="/modules/yonger/common/blocks/common.inc.php" />
    </div>

    <wb-multilang wb-lang="{{_sett.locales}}" name="lang">

        <div class="accordion">
            <h6>Центральная часть</h6>
            <div>
                <div class="row">
                    <div class="col-lg-4">
                        <wb-module wb="module=filepicker&mode=single&width=200&height=200" name="image" />
                        <small>Центральное изображение</small>
                    </div>
                    <div class="col-lg-8">
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
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="top_text" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h6>Блоки слева</h6>
            <div>
                <wb-multiinput name="left" class="pl-2">
                    <div class="col-lg-4">
                        <wb-module wb="module=filepicker&mode=single&width=150&height=100" name="image" />
                    </div>
                    <div class="col-lg-7">
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.header}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="header" placeholder="{{_lang.header}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.link}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="link" placeholder="{{_lang.link}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="text" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </wb-multiinput>
            </div>
            <h6>Блоки справа</h6>
            <div>
                <wb-multiinput name="right" class="pl-2">
                    <div class="col-lg-4">
                        <wb-module wb="module=filepicker&mode=single&width=150&height=100" name="image" />
                    </div>
                    <div class="col-lg-7">
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.header}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="header" placeholder="{{_lang.header}}">
                            </div>
                        </div>
                        <div class="row">
                            <label class="form-control-label col-lg-4">{{_lang.link}}</label>
                            <div class="col-lg-8 mb-2">
                                <input class="form-control" type="text" name="link" placeholder="{{_lang.link}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <textarea class="form-control" name="text" rows="auto"
                                    placeholder="{{_lang.text}}"></textarea>
                            </div>
                        </div>
                    </div>
                </wb-multiinput>
            </div>
        </div>
        <script>
        $('.accordion').accordion({
            heightStyle: 'content'
        });
        </script>
    </wb-multilang>
    <wb-lang>
        [ru]
        header = Услуги
        text = Текст
        link = Ссылка
        button = Кнопка
        [en]
        header = Services
        text = Text
        link = Link
        button = Button
    </wb-lang>
</edit>

</html>