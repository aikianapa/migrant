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
        $data = $this->app->Dot($item);
        if ($data->get('fullname') == '' && $data->get('first_name')>'') {
            $data->set('fullname', implode(' ', [$data->get('last_name'),$data->get('first_name'),$data->get('middle_name')]));
        }
        if ($data->get('sign_num') =='' && $data->get('employer') > '') {
            $emplr = $this->app->itemRead('employers', $data->get('employer'));
            $year = date('y');
            $start = ($year == 22 && (isset($emplr['sign_start']))) ? intval($emplr['sign_start']) : 0;
            $ai = $this->app->module('autoinc');
            $counter=$data->get('employer').'_'.$year;
            $item['sign_num'] = $ai->inc('sign_num', $counter, $start);
        }
        if ($data->get('reg_street') =='' && $data->get('place') > '') {
            $place = $this->app->itemRead('places', $data->get('place'));
            foreach($place as $k => $v) {
                substr($k, 0, 4) == 'reg_' ? $item[$k] = $v : null;
            }
        }
    }

    public function afterItemRead(&$item) {
        $item ? null : $item=(array)$item;
        $data = $this->app->Dot($item);
        $data->get('phone') ? $item['phone'] = wbDigitsOnly(str_replace('+7', '8', $item['phone'])) : null;
        $data->get('phone_alt') ? $item['phone_alt'] = wbDigitsOnly(str_replace('+7', '8', $item['phone_alt'])) : null;
        $data->get('status') ? null : $item['status'] = 'new';
        $data->get('source.0.img') > '' OR $data->get('code') > '' ? $item['status'] = 'progress' : null;
        $data->get('order.0.img') > '' AND $data->get('code') > '' ? $item['status'] = 'ready' : null;
        $data->get('archive') == 'on' ? $item['status'] = 'archive' : null;
        isset($item['_created']) ? null : $item['_created'] = date('Y-m-d');
        $item['date'] = date('Y-m-d', strtotime($item['_created']));
        $item['pasp'] = preg_replace('/[^a-zA-Z0-9]/ui', '', $data->get('doc_ser').$data->get('doc_num'));
        if ($data->get('fullname') > '' && $data->get('last_name') == '') {
            $tmp = explode(' ', $data->get('fullname'));
            isset($tmp[0]) ? $data->set('last_name', $tmp[0]) : null;
            isset($tmp[1]) ? $data->set('first_name', $tmp[1]) : null;
            unset($tmp[0]); unset($tmp[1]);
            $tmp = implode(' ',$tmp); 
            $data->set('middle_name', $tmp);
        } else if ($data->get('fullname') == '' && $data->get('first_name')>'') {
            $data->set('fullname', implode(' ', [$data->get('last_name'),$data->get('first_name'),$data->get('middle_name')]));
        }
    }



    public function beforeItemShow(&$item) {
        if ($this->app->vars('_route.action') !== 'edit') {
            $item ? null : $item=(array)$item;
            $data = $this->app->Dot($item);
            $data->get('region') > '' ? $data->set('reg_city_type', $data->get('region').' область, '.$data->get('reg_city_type')) : null; // Область + тип города


            if ($this->app->vars('_route.module') == 'export' && $this->app->vars('_route.mode') == 'process') {
                $data->get('reg_build') > '' ? $data->set('reg_corpse', $data->get('reg_corpse').', стр. '.$data->get('reg_build')) : null; // Корпус + строение
                $data->set('reg_house', $data->get('reg_house_num')); // номер дома
                $data->set('reg_flat', trim($data->get('reg_flat').' '.$data->get('reg_flat_num'))); // тип квартиры + номер квартиры

            } else {

                $data->get('reg_corpse') > ' ' ? $item['reg_corpse'] = 'корп.'.$item['reg_corpse'] : null;
                $data->get('reg_build') > '' ? $data->set('reg_corpse', $data->get('reg_corpse').', стр. '.$data->get('reg_build')) : null; // Корпус + строение
                $data->set('reg_house', trim($data->get('reg_house').' '.$data->get('reg_house_num'))); // тип дома + номер дома
                $data->set('reg_flat', trim($data->get('reg_flat').' '.$data->get('reg_flat_num'))); // тип квартиры + номер квартиры

            }
            $item['birth_date'] = wbDate('d.m.Y', $item['birth_date']);
        }
    }


    public function beforeItemRemove(&$item)
    {
        foreach($item['attaches'] as $atc) {
            unlink($this->app->route->path_app.'/'.$atc['img']);
        }
    }

    public function beforeItemEdit(&$item)
    {
        /*
        if (isset($item['id']) && !in_array($item['id'],['','_new'])) {
            $ai = $this->app->module('autoinc');
            $item['sign_start'] = $ai->get('docs', $item['id'].'_'.date('y'));
        }
        */
        return $item;
    }


    public function norm() {
        $list = $this->app->itemList('docs', $this->filter);
        foreach ($list['list'] as $item) {
            $item = (object)$item;
            $item->reg_corpse = str_replace(', корп.', '', $item->reg_corpse);
            $item->region > '' && isset($item->reg_city_type) ? $item->reg_city_type = str_replace('Ленинградская область, ','',$item->reg_city_type) : null;
            $item->reg_build > '' ? $item->reg_corpse = str_replace(', стр. '.$item->reg_build,'',$item->reg_corpse) : null;
            $item->reg_house_num > '' ? $item->reg_house = str_replace(" {$item->reg_house_num}",'', $item->reg_house) : null;
            $item->reg_flat_num > '' ? $item->reg_flat = str_replace(" {$item->reg_flat_num}", '', $item->reg_flat) : null;
            $item = (array)$item;
            //$this->afterItemShow($item);
            //$item = (object)$item;
            //print_r([$item->region,$item->reg_city_type,$item->reg_city,$item->reg_build,$item->reg_house,$item->reg_house_num,$item->reg_flat]);
            echo "<br>";
            wbItemSave('docs', $item, false);
        }
        wbTableFlush('docs');
    }

    public function operGetWork() {
        $app = &$this->app;
        $data = $app->vars('_post');
        $item = $app->itemRead('docs', $data['id']);
        $res = false;
        if ($item AND (!isset($item['oper']) OR $data['oper'] == $item['oper'])) {
            if (!isset($item['oper'])) {
                $data['opertime'] = $item['opertime'] = date('Y-m-d H:i:s');
                $item['oper'] = $data['oper'];
                $app->itemSave('docs', $item, true);
                $item = $app->itemRead('docs', $data['id']);
                if ($item['opertime'] == $data['opertime'] && $item['oper'] == $data['oper']) $res = true;
            } else {
                $res = true;
            }
            @$data['pdf'] = $item['order'][0]['img'];
        }
        header('Content-Type: application/json; charset=utf-8');
        if ($res) {
            echo json_encode(['error'=>false,'pdf'=>$data['pdf']]);
        } else {
            echo json_encode(['error'=>true]);
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
            ,22 => 'phone_alt'
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