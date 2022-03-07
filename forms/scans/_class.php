<?php
class scansClass extends cmsFormsClass
{
    public function beforeItemSave(&$item)
    {
        $data = $this->app->Dot($item);
        if ($data->get('img1.0.img') > '' 
        && $data->get('img2.0.img') > '' 
        && $data->get('img3.0.img') > '' 
        && $data->get('img4.0.img') > '') {
            $data->set('active', 'on');
        } else {
            $data->set('active', '');
        }
    }
}
?>