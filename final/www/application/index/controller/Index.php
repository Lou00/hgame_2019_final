<?php
namespace app\index\controller;

use think\Request;

class Index
{
    public function index()
    {
        return '<h1>lou00 的极简图床API</h1>';
    }

    public function upload()
    {
        $file = request()->file('image');
        $info = $file->validate(['size'=>5242880,'ext'=>'jpg,png,gif,jpeg'])->move('./uploads');
        if($info){
            return  $info->getSaveName();
        }else{
            return  "upload filed";
        }
    }

    public function download(Request $request)
    {
        $url= $request->param("url");
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if(strstr($scheme,"php")){return "php:// not allow";}
        $maxSize = 5242880;
        $limitExtension = array_map(function ($ext) {
            return ltrim($ext, '.');
        }, [".png", ".jpg", ".gif",".jpeg"]);
        $allowTypes = array_map(function ($ext) {
            return "image/{$ext}";
        }, $limitExtension);
        $content = file_get_contents($url);
        $img = getimagesizefromstring($content);
        if ($img && in_array($img['mime'], $allowTypes))
        {
            $ext = explode('/',$img['mime'])[1];
            $size = strlen($content);
            if (in_array($ext, $limitExtension) && $size <= $maxSize) {
                $filename = \bin2hex(\random_bytes(10)) . '.' . $ext;
                file_put_contents("./uploads/{$filename}", $content);
                return "/uplods/{$filename}";
            } else {
                return "download fail";
            }
        } else {
            return "only images";
        }

    }
}