<html>
<view>
    <wb-foreach wb-from='_parent.blocks'>
        
    
    
<div class="modal fade offcanvas-modal" id="menuModal" wb-before="header" wb-if="'{{name}}' == 'sitemenu'">

            <div class="modal-dialog offcanvas-dialog">
                <div class="modal-content">
                    <div class="modal-header offcanvas-header">
                        <a href="/">
                            <img src="assets/images/logo/logo.png" alt="{{_sett.header}}"
                                wb-if="'{{logo.0.img}}'==''" />
                            <img src="/thumbc/220x41/src/{{logo.0.img}}" width="220" height="41" alt="{{_sett.header}}"
                                wb-if="'{{logo.0.img}}'!==''" />
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- offcanvas-mobile-menu start -->
                        <nav id="offcanvasNav" class="offcanvas-menu">
                        <ul>
                            <wb-foreach wb-from="menu">
                                <li><a href="{{link}}">{{button.{{_sess.lang}}}}</a></li>
                            </wb-foreach>
                            <li>
                            <a href="javascript:void(0)">{{_sess.lang}}</a>
                            <ul>
                                <wb-foreach wb-call="explode(',','{{_sett.locales}}')">
                                    <li>
                                        <a class="sub-menu-link" href="/module/lang/{{_val}}/set">{{_val}}</a>
                                    </li>
                                </wb-foreach>
                            </ul>
                            </li>
                        </ul>



                    </nav>
                    <!-- offcanvas-mobile-menu end -->
                </div>
            </div>

        </div>
        </wb-foreach>
</view>
<edit header="Мобильное меню">

</edit>
</html>