<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require __DIR__ . '/fpdf.php';
require __DIR__ . '/fpdf_rotate.php';


use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

class modPdfer
{
    public function __construct($app)
    {
        set_time_limit(600);
        $this->app = $app;
        $this->path = '/uploads/sources';
        $this->orders = '/uploads/orders';
        $this->dir = $app->route->path_app . $this->path;
        $this->faximile = $this->app->route->path_app . '/ocr/faximile.png';
    }

    public function init()
    {
    }

    public function extract()
    {
        $app = &$this->app;
        $pdf = array_pop(explode('/', $app->vars('_post.pdf')));
        // output file
        $target = $app->vars('_post.name') > '' ? $app->vars('_post.name') . '.jpg' : $app->newid() . '.jpg';
        // create a command string
        exec('cd ' . $this->dir . ' && /usr/bin/convert -verbose -scale 1024 -density 150 -depth 8 -quality 100  "' . $pdf . '"  "' . $target . '" 2>&1', $output);
        $files = [];
        $output = implode("\n\r", $output);
        preg_match_all('/=>(.*)\[/m', $output, $matches, PREG_PATTERN_ORDER);
        $len = count($matches[1]);
        foreach ($matches[1] as $i => $file) {
            $files[] = $this->path . '/' . $file;
            //if ($i+1 <= $len-2) $this->faximile($this->dir.'/'.$file);
        }
        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode($files);
        die;
    }

    public function recover()
    {
        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        $app = $this->app;
        $err = $app->jsonEncode(['error'=>true,'msg'=>'Ошибка. Нет данных.']);
        if (!isset($app->route->params[0])) {
            echo $err;
            die;
        } else {
            $id = $app->route->params[0];
        }
        $item = $app->itemRead('docs', $id);
        @$doc = isset($item['order'][0]['img']) ? $item['order'][0]['img'] : null;
        if (is_file($app->vars('_env.path_app') . $doc)) {
            $did = date('dmY', strtotime($item['_created'])) . '_' . $item['doc_ser'] . $item['doc_num'];
            $tmp = 'sign_' . $did . '.pdf';
            $src = $app->vars('_env.path_app') . $doc;
            $dir = $app->route->path_app . '/uploads/tmp/';
            exec("cp {$src} {$dir}{$tmp}");
            exec("cd {$dir} && pdfseparate -f 1 -l 2 {$tmp} %d_{$did}.pdf && pdfunite 1_{$did}.pdf 2_{$did}.pdf {$app->vars('_env.path_app')}{$this->orders}/{$tmp}");
            $_POST['sources'] = $item['sources'];
            $_POST['pdf'] = $this->orders . '/' . $tmp;
            $_POST['dstpdf'] = $did . '.pdf';
            $this->attach();
            unlink($tmp);
            die;
        }
        echo $err;
        die;
    }



    public function attach()
    {

        $app = &$this->app;
        $pdfsrc = str_replace('//', '/', $app->route->path_app . $app->vars('_post.pdf'));
        $srcpdf = $app->vars('_post.srcpdf');
        $sources = $app->vars('_post.sources');
        $dstpdf = $app->vars('_post.dstpdf');
        $images = '';
        $pdf = new PDF('P', 'mm', 'A4');
        // размеры в миллиметрах
        $sizes = [
            0 => ['ox' => rand(10, 60), 'oy' => rand(50, 100), 'x' => 125, 'y' => 180, 'r' => rand(-1.5, 1.5)],
            1 => ['ox' => rand(10, 60), 'oy' => rand(50, 100), 'x' => 85, 'y' => 125, 'r' => rand(-1.5, 1.5)],
            2 => ['ox' => rand(5, 40), 'oy' => rand(190, 230), 'x' => 170, 'y' => 170, 'r' => 90 + rand(-1.5, 1.5)],
            3 => ['ox' => rand(5, 40), 'oy' => rand(190, 230), 'x' => 170, 'y' => 170, 'r' => 90 + rand(-1.5, 1.5)],
            4 => ['ox' => rand(5, 40), 'oy' => rand(190, 230), 'x' => 165, 'y' => 120, 'r' => 90 + rand(-1.5, 1.5)],
            5 => ['ox' => rand(20, 60), 'oy' => rand(50, 100), 'x' => 130, 'y' => 180, 'r' => rand(-1.5, 1.5)],
        ];
        $fax = $app->route->path_app . '/ocr/faximile.png';
        $len = count($sources);
        foreach ($sources as $i => $img) {
            $fx = ['ox' => rand(15, 70), 'oy' => rand(10, 40), 'x' => 50, 'y' => 20, 'r' => rand(-5, 5)];
            $rotate = 0;
            $img = wbNormalizePath($app->route->path_app . $img);
            exec("/usr/bin/convert  -scale 1024 -depth 32 -trim '{$img}' '{$img}'");
            $pdf->AddPage();
            $pdf->RotatedImage($img, $sizes[$i]['ox'], $sizes[$i]['oy'], $sizes[$i]['x'], $sizes[$i]['y'], $sizes[$i]['r']);
            if ($i < ($len - 2)) {
                if (in_array($i, [2, 3])) {
                    $fx['oy'] = rand(240, 260);
                }
                $pdf->RotatedImage($fax, $fx['ox'], $fx['oy'], $fx['x'], $fx['y'], $fx['r']);
            }
            $img = basename($img);
            $images .= $img . ' ';
        }

        $tmpsrc = $app->route->path_app . '/uploads/tmp/' . 'tmp_' . $this->app->newId() . '.pdf';
        $pdf->output('F', $tmpsrc);
        //exec('cd '.$this->dir.' && convert '.$images.' '.$tmpsrc);
        // sudo apt install poppler-utils
        exec('cd ' . $this->dir . ' && rm -f ' . $dstpdf . ' && pdfunite ' . $pdfsrc . ' ' . $tmpsrc . ' ' . $dstpdf . ' && rm ' . $tmpsrc);
        //unlink($pdfsrc);
        rename($this->dir . '/' . $dstpdf, $app->route->path_app . $this->orders . '/' . $dstpdf);
        //foreach ($sources as $img) unlink($app->route->path_app.$img);
        //foreach ($srcpdf as $sp) unlink($app->route->path_app.$sp['img']);
        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode(['pdf' => $this->orders . '/' . $dstpdf]);
        //       echo json_encode(['pdf'=>'/uploads/tmp/'.$tmpsrc]);

    }

    public function faximile($file)
    {
        $im = new Imagick($file);
        $wm = new Imagick();
        $wm->setBackgroundColor(new ImagickPixel('transparent'));
        $wm->readImage($this->faximile);
        $wm->setImageFormat('png32');
        $wm->scaleImage(300, 150);
        $wm->rotateImage(new ImagickPixel('#00000000'), random_int(-10, 10));
        $im->compositeImage($wm, Imagick::COMPOSITE_OVER, 100 + random_int(0, 50), 30 + random_int(0, 50));
        $im->writeImage($file);
    }
}
