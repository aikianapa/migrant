<?php
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

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
        $this->dir = $app->route->path_app. $this->path;
        $this->faximile = $this->app->route->path_app.'/ocr/faximile.png';
    }

    public function init()
    {
    }

    public function extract()
    {
        $app = &$this->app;
        $pdf = array_pop(explode('/', $app->vars('_post.pdf')));
        // output file
        $target = $app->vars('_post.name') > '' ? $app->vars('_post.name').'.jpg' : $app->newid().'.jpg';
        // create a command string
	exec('cd '.$this->dir.' && /usr/bin/convert -verbose -scale 1024 -density 150 -depth 8 -quality 100  "'.$pdf .'"  "'.$target.'" 2>&1', $output);
        $files = [];
        foreach ((array)$output as $out) {
            preg_match_all('/=>(.*)\[/m', $out, $matches, PREG_PATTERN_ORDER);
	    if ($matches[1][0] > '')  {
            $file = $matches[1][0];
            $files[] = $this->path.'/'.$file;
            $this->faximile($this->dir.'/'.$file);
	    }
        }
        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode($files);
        die;
    }

    public function attach()
    {
        $app = &$this->app;
        $pdfsrc = str_replace('//', '/', $app->route->path_app.$app->vars('_post.pdf'));
        $srcpdf = $app->vars('_post.srcpdf');
        $sources = $app->vars('_post.sources');
        $dstpdf = $app->vars('_post.dstpdf');
        $images = '';
        foreach ($sources as $img) {
            $img = str_replace($this->path.'/', '', $img);
            $images .= $img.' ';
        }
        $tmpsrc = 'tmp_'.$this->app->newId().'.pdf';
        exec('cd '.$this->dir.' && convert '.$images.' '.$tmpsrc);
        // sudo apt install poppler-utils
        exec('cd '.$this->dir.' && rm -f '.$dstpdf.' && pdfunite '.$pdfsrc.' '.$tmpsrc.' '.$dstpdf.' && rm '.$tmpsrc);
        //unlink($pdfsrc);
        rename($this->dir.'/'.$dstpdf, $app->route->path_app.$this->orders.'/'.$dstpdf);
        //foreach ($sources as $img) unlink($app->route->path_app.$img);
        //foreach ($srcpdf as $sp) unlink($app->route->path_app.$sp['img']);
        header('Content-Type: charset=utf-8');
        header('Content-Type: application/json');
        echo json_encode(['pdf'=>$this->orders.'/'.$dstpdf]);

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
        $im->compositeImage($wm, Imagick::COMPOSITE_OVER, 100+random_int(0, 50), 30+random_int(0, 50));
        $im->writeImage($file);
    }
}
?>