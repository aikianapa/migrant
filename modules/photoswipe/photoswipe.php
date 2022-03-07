<?php
class modPhotoswipe
{
    public function __construct(&$dom)
    {
        set_time_limit(600);
        $this->app = &$dom->app;
        $this->dom = &$dom;
        $this->init();
    }

    function init() {
        $app = &$this->app;
        $dom = &$this->dom;
        $ui = $app->fromFile(__DIR__.'/photoswipe_ui.php');
        $set = isset($this->dom->params->imgset) ? $this->dom->params->imgset : $this->app->newId();
        $dom->addClass('photoswipe');
        $dom->find('a')->attr('data-fslightbox', $set);
        $dom->after($ui);
    }


}
?>