<?php
class scansClass extends cmsFormsClass
{
    public function afterItemRead(&$item)
    {
        if (!$item) return $item;
        $data = $this->app->Dot($item);
        if ($data->get('sources.0') > '' 
        && $data->get('sources.1') > '') {
            $data->set('active', 'on');
        } else {
            $data->set('active', '');
        }
        $item['sernum'] = $item['doc_ser'].$item['doc_num'];
    }

    function block() {
        $block = wbItemRead('tmp', 'blockedscans');
        $block ? null : $block = ['_id'=>'blockedscans','blocks'=>[]];
        if ($this->app->vars('_post.id') > '') {
            $block['blocks'][$this->app->vars('_post.id')] = date('Y-m-d H:i:s');
            $block = wbItemSave('tmp', $block, true);
        }
        header("Content-type:application/json");
        return ['msg'=>'scanblocks','blocks'=>array_keys($block['blocks'])];
    }

    function unblock() {
        $block = wbItemRead('tmp', 'blockedscans');
        $block ? null : $block = ['_id'=>'blockedscans','blocks'=>[]];
        if ($this->app->vars('_post.id') > '') {
            if (isset($block['blocks'][$this->app->vars('_post.id')])) {
                unset($block['blocks'][$this->app->vars('_post.id')]);
            }
            $block = wbItemSave('tmp', $block, true);
        }
        header("Content-type:application/json");
        return ['msg'=>'scanblocks','blocks'=>array_keys($block['blocks'])];
    }

    function getblock() {
        $block = wbItemRead('tmp', 'blockedscans');
        $block ? null : $block = ['_id'=>'blockedscans','blocks'=>[]];
        foreach ($block['blocks'] as $id => $time) {
            // тут бы удалить из блока записи, которым более получаса
            if (time() > strtotime($time.'+10 min')) {
                unset($block['blocks'][$id]);
            }
        }
        $block = wbItemSave('tmp', $block, true);
        header("Content-type:application/json");
        return ['msg'=>'scanblocks','blocks'=>array_keys($block['blocks'])];
    }

    function import() {
        $file = $this->app->normalizePath($this->app->vars('_env.path_app').$this->app->vars('_post.img'));
        if (is_file($file)) {
            $zip = new ZipArchive();
            $path = '/uploads/tmp';
            $dir = $this->app->vars('_env.path_app').$path;
            if (!is_dir($dir)) mkdir($dir,0777);
            if ($zip->open($file)) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $fn = $zip->getNameIndex($i);
                    $name = substr($fn, 0, -4);
                    $ext = strtolower(substr($fn, -4));
                    $dir = $this->app->vars('_env.path_app').$path.'/'.$name;
                    if ($ext == '.pdf' && !strpos(' '.$name,'MACOSX')) {
                        if (is_numeric(substr($name, 0, 2))) {
                            $ser = '';
                            $num = $name;
                        } else {
                            $ser = substr($name, 0, 2);
                            $num = substr($name, 2);
                        }
                        $zip->extractTo($dir, $fn);
                        $target = $this->app->vars('_env.path_app').'/uploads/sources/';
                        if (!is_dir($target)) {
                            mkdir($dir, 0777);
                        }
                        exec('cd '.$dir.' && /usr/bin/convert -scale 1024 -density 150 -grayscale average -quality 80 -trim +repage "'.$dir.'/'.$fn .'"  "'.$target.'/'.$name.'.jpg" 2>&1');
//                        echo('cd '.$dir.' && /usr/bin/convert -scale 1024 -density 150 -grayscale average -quality 80  "'.$dir.'/'.$fn .'"  "'.$target.'/'.$name.'.jpg" 2>&1');
                        unlink($dir.'/'.$fn);
                        $sources = glob($target."/{$name}*.jpg");
                        foreach ($sources as &$src) {
                            $src = str_replace($this->app->vars('_env.path_app'), '', $src);
                        }
                        $id = 'id_'.$ser.$num;
                        $scan = [
                        'id' => $id,
                        'sources' => $sources,
                        'doc_ser' => $ser,
                        'doc_num' => $num
                        ];
                        $this->app->itemSave('scans', $scan, false);
                    }
                    //echo $ser.'-'.$num;
                }
                //$zip->extractTo('.'.$path);
                $zip->close();
                $this->app->tableFlush('scans');
            }

            
        }
    }

    private function extractImages($pdf) {
        $url = $this->app->route->host.'/module/pdfer/extract/';
        $name = array_pop(explode('/',$pdf));
        $name = array_shift(explode('.',$name));
        $post = [
            'pdf' => $pdf,
            'name' => $name,
            '__token' => $this->app->vars('_sess.token')
        ];
        $res = $this->app->authPostContents($url, $post);
        return json_decode($res,true);
    }
}
?>