<?php

class docsClass extends cmsFormsClass
{
    public function beforeItemSave(&$item)
    {
        $item['checksum'] = $this->checksum($item);
    }

    public function beforeItemRemove(&$item)
    {
        foreach($item['attaches'] as $atc) {
            unlink($this->app->route->path_app.'/'.$atc['img']);
        }
    }

    public function checklist() {
        $list = $this->app->itemList('docs');
        $list = $this->app->json($list)->from('list')->column('checksum');
        return $list;
    }

    public function checksum($item) {
        return md5($item['fullname'].$item['doc_ser'].$item['doc_num'].$item['birth_date']);
    }

    public function schemaXls() {
        return [
             0  => 'fullname'
            ,1  => 'birth_date'
            ,2  => 'tfl' // тип физ.лица
            ,3  => 'citizen'
            ,4  => 'doc_type' // тип док-та = 31
            ,5  => 'doc_ser'
            ,6  => 'doc_num'
            ,7  => 'doc_date'
            ,8  => 'doc_who'
            ,9  => 'doc_code'
            ,10 => 'doc_expire'
            ,11 => 'country' // 643
            ,12 => 'region' // 78
            ,13 => 'district'
            ,14 => 'reg_city_type'
            ,15 => 'reg_city'
            ,16 => 'reg_street_type'
            ,17 => 'reg_street'
            ,18 => 'reg_house'
            ,19 => 'reg_corpse'
            ,20 => 'reg_flat'
            ,27 => 'card_type' // 32
            ,28 => 'gender'
            ,29 => 'birth_place'
            ,33 => 'mc_type' // 39
            ,34 => 'mc_ser'
            ,35 => 'mc_num'
            ,36 => 'mc_date'
            ,37 => 'mc_expire'
            ,70 => 'code'
        ];
    }
}

?>