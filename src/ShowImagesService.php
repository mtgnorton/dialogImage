<?php
namespace Mtg\ShowImage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ShowImagesService {

    protected $storage;

    public function __construct(){

        $this->storage = Storage::disk(config('showImages.disk'));

    }


    public function getImages(){

        $recursion = $type = $cacheTime = '';
        extract($this->getConfig());

        return Cache::remember('images',$cacheTime,function () use ($recursion,$type){
           return collect($this->storage->files('/',$recursion))->map( function ($image) use ($type){
                $name  =$this->transferCode($image);

                if (!in_array(pathinfo($name,PATHINFO_EXTENSION),$type))
                    return null;

                return [
                    'name'=>$this->getLimitName($name),
                    'url'=>$this->storage->url($name),
                    'path'=>$name
                ];

            })->filter();
        });

    }

    public function getImagesNumber(){

        $recursion = $type = $cacheTime = '';

        extract($this->getConfig());

        return Cache::remember('images-number',$cacheTime,function() use ($recursion){

        return count($this->storage->files('/',$recursion));

        });

    }

    public function getConfig(){

        $recursion = config('showImages.recursion') ?? true;

        $type      = config('showImages.type') ?? ['gif','jpg','png' ];

        $cacheTime = config('showImages.cache_time') ?? 5;

        return compact('recursion','type','cacheTime');

    }

    public function transferCode($str,$target = "utf-8"){

        $type = mb_detect_encoding($str , array('UTF-8','GBK','LATIN1','BIG5')) ;

        if( $type != $target){

            $str = mb_convert_encoding($str ,$target, $type);

        }

        return $str;
    }


    public function getLimitName($name){

        return str_limit(pathinfo($name,PATHINFO_BASENAME),10).'...'.pathinfo($name,PATHINFO_EXTENSION);

    }

    public function deleteImages($images){

        $rs = array_map(function($image){

            return $this->transferCode($image,'gb2312');

        },explode(',',$images));

        $rs =  $this->storage->delete($rs);

        if ($rs){

            Cache::forget('images-number');

            Cache::forget('images');

            return ['status'=>true];

        }

        return ['status'=>false];


    }

    public  static  function getView(){
    return view('showimages::showimages');
    }

    public static function js(){
        $except = config('showImages.js-except');
        $js = [
            '/vendor/showimages/jQuery-2.1.4.min.js',
            '/vendor/showimages/bootstrap.min.js',
            '/vendor/showimages/vue.js',
            '/vendor/showimages/showphotos.js',
            '/vendor/showimages/sweetalert.min.js'
        ];
       if ($except)
       $js = static::ignore($js,$except);
        return view('showimages::showimages-js',['data'=>$js]);

    }

    public  static  function css(){
        $except = config('showImages.css-except');
        $css = [
            '/vendor/showimages/bootstrap.min.css',
            '/vendor/showimages/font-awesome.min.css',
            '/vendor/showimages/sweetalert.css'
        ];
        if ($except)
        static::ignore($css,$except);
        return view('showimages::showimages-css',['data'=>$css]);
    }


    public static  function ignore($target,$except){

        foreach ($target as $key => $val) {
            foreach ($except as $k=>$v) {
                if (strpos(strtolower($val),$v))
                    unset($target[$key]);
            }
        }
        return $target;
    }
}