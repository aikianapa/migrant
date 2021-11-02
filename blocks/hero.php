<html>
<view>
    <section class="section position-relative">
        <!-- hero-shape one start -->
        <div class="hero-shape1">
            <img src="assets/images/slider/shape/shape1.png" alt="shape" />
        </div>
        <!-- hero-shape one end -->
        <!-- hero-shape two start -->
        <div class="hero-shape2">
            <img src="assets/images/slider/shape/shape2.png" alt="shape" />
        </div>
        <!-- hero-shape two end -->
        <!-- hero-slider start -->
        <div class="hero-slider">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="hero-slide-content">
                            <h2 class="title animated">
                                {{header1}} <br />
                                {{header2}}
                            </h2>
                            <a href="{{link1}}" wb-if="button1 > ''"
                                class="btn btn-lg animated delay1 btn-dark btn-hover-dark me-4 mb-3 mb-sm-0">{{button1}}</a>
                            <a href="{{link2}}"
                                class="btn btn-lg animated delay2 btn-secondary btn-hover-secondary mb-3 mb-sm-0">{{button2}}</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-img scene mt-10 mt-lg-0">
                            <div data-depth="0.2">
                                <img class="animated" src="assets/images/slider/slide2.png" alt="" wb-if="'{{image.0.img}}' == ''" />
                                <img class="animated" src="{{image.0.img}}" alt="" wb-if="'{{image.0.img}}' > ''" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hero-slider end -->
    </section>
</view>
<edit header="Hero Slider">
    <div>
        <wb-include wb-src="/modules/yonger/common/blocks/common.inc.php" />
    </div>
    <wb-multilang wb-lang="{{_sett.locales}}" name="lang">
        <div class="form-group row">
            <label class="col-lg-3">Заголовок</label>
            <div class="col-lg-9">
                <input class="form-control" type="text" name="header1">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-3">Подзаголовок</label>
            <div class="col-lg-9">
                <input class="form-control" type="text" name="header2">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-3">Кнопка/ссылка 1</label>
            <div class="col-lg-4">
                <input class="form-control" type="text" name="button1">
            </div>
            <div class="col-lg-5">
                <input class="form-control" type="text" name="link1">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-3">Кнопка/ссылка 2</label>
            <div class="col-lg-4">
                <input class="form-control" type="text" name="button2">
            </div>
            <div class="col-lg-5">
                <input class="form-control" type="text" name="link2">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-3">Изображение</label>
            <div class="col-lg-6">
                <wb-module wb="module=filepicker&mode=single&width=300&height=200" name="image" />
            </div>
        </div>

    </wb-multilang>
</edit>

</html>