<?php

class docsClass extends cmsFormsClass
{
    public function beforeItemSave(&$item)
    {
        $item['reg_city_type'] = strtolower($item['reg_city_type']);
        $item['reg_street_type'] = strtolower($item['reg_street_type']);
        $item['reg_city'] = ucfirst($item['reg_city']);
        $item['reg_street'] = ucfirst($item['reg_street']);
        $item['checksum'] = $this->checksum($item);
    }

    public function afterItemRead(&$item) {
        $item ? null : $item=(array)$item;
        $data = $this->app->Dot($item);
        $data->get('status') ? null : $item['status'] = 'new';
        $data->get('source.0.img') > '' OR $data->get('code') > '' ? $item['status'] = 'progress' : null;
        $data->get('order.0.img') > '' AND $data->get('code') > '' ? $item['status'] = 'ready' : null;
        $data->get('archive') == 'on' ? $item['status'] = 'archive' : null;
    }

    public function beforeItemRemove(&$item)
    {
        foreach($item['attaches'] as $atc) {
            unlink($this->app->route->path_app.'/'.$atc['img']);
        }
    }

    public function beforeItemEdit(&$item)
    {
        return $item;
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
            ,23 => 'phone'
            ,27 => 'card_type' // 32
            ,28 => 'gender'
            ,29 => 'birth_place'
            ,33 => 'mc_type' // 39
            ,34 => 'mc_ser'
            ,35 => 'mc_num'
            ,36 => 'mc_date'
            ,37 => 'mc_expire'
            ,43 => 'tax_resident_outside' // нет
            ,70 => 'code'
        ];
    }
}

?>