<?php

namespace InnoSoft\FileManager;

use Illuminate\Support\Str;

class FileManagement
{
    protected static $photo_formats = ['jpeg', 'bmp', 'cod', 'gif', 'ief', 'jpg', 'jfif', 'tif', 'ras', 'cmx', 'ico', 'pnm', 'pbm', 'pgm', 'ppm', 'rgb', 'xbm', 'xpm', 'xwd', 'png', 'jps', 'fh'];
    protected static $video_formats = ['avi', 'mov', 'mp4', 'mpeg', 'mpg', 'webm', 'wma'];
    protected static $audio_formats = ['ac3', 'aiff', 'm4a', 'mid', 'mp3', 'ogg', 'wav'];

    public static function scandir($auth_config) {
        $inp     = \Request::all();
        $dir     = $auth_config['upload_dir'] . $inp['path'];
        $files   = [];
        $folders = [];
        $videos  = [];
        $audios  = [];
        $photos  = [];
        if(!file_exists($dir)) mkdir($dir, 0777, true);
        foreach (new \DirectoryIterator($dir) as $file) {
            if (!$file->isDot()) {
                if ($file->isDir()) {
                    if(!in_array($file->getFilename(), $auth_config['hidden_folders'])){
                        array_push($folders, [
                            'type'     => 'folder',
                            'filename' => $file->getFilename(),
                            'path'     => str_replace('\\', '/', $file->getPathname())
                        ]);
                    }
                } else {
                    $ex = $file->getExtension();
                    if (in_array($ex, self::$photo_formats)) {
                        array_push($photos, [
                            'type'     => 'photo',
                            'extension' => $ex,
                            'filename' => $file->getBasename('.'.$ex),
                            'path' => str_replace('\\', '/', $file->getPathname()),
                            'size' => floor($file->getSize() / 1024) // KB
                        ]);
                    } else if (in_array($ex, self::$video_formats)) {
                        array_push($videos, [
                            'type'     => 'video',
                            'extension' => $ex,
                            'filename' => $file->getBasename('.'.$ex),
                            'path' => str_replace('\\', '/', $file->getPathname()),
                            'size' => floor($file->getSize() / 1024) // KB
                        ]);
                    } else if (in_array($ex, self::$audio_formats)) {
                        array_push($audios, [
                            'type'     => 'audio',
                            'extension' => $ex,
                            'filename' => $file->getBasename('.'.$ex),
                            'path' => str_replace('\\', '/', $file->getPathname()),
                            'size' => floor($file->getSize() / 1024) // KB
                        ]);
                    }else{
                        array_push($files, [
                            'type'     => 'file',
                            'extension' => $ex,
                            'filename' => $file->getBasename('.'.$ex),
                            'path' => str_replace('\\', '/', $file->getPathname()),
                            'size' => floor($file->getSize() / 1024) // KB
                        ]);
                    }
                }
            }
        }

        //create breadcrumb
        $paths      = explode('/', $inp['path']);
        $breadcrumb = [];
        $path_tmp   = '/';
        foreach ($paths as $p) {
            if ($p !== '/' && $p !== '') {
                $path_tmp .= $p . '/';
                array_push($breadcrumb, [
                    'dir'  => $p,
                    'path' => $path_tmp
                ]);
            }
        }
        $disk_space_usage = (float) $inp['disk_space_usage'];
        if ($inp['disk_space_usage_computed'] == 'true') {
            $disk_space_usage = round(self::disk_total_space($auth_config['upload_dir']) / 1048576, 2);
        }
        
        // orderby filename
        usort($files, function($left, $right){
            return strcmp($left['filename'], $right['filename']) > 0;
        });
        usort($folders, function($left, $right){
            return strcmp($left['filename'], $right['filename']) > 0;
        });
        usort($videos, function($left, $right){
            return strcmp($left['filename'], $right['filename']) > 0;
        });
        usort($audios, function($left, $right){
            return strcmp($left['filename'], $right['filename']) > 0;
        });
        usort($photos, function($left, $right){
            return strcmp($left['filename'], $right['filename']) > 0;
        });
        
        return [
            'status'           => 'success',
            'paths'            => $breadcrumb,
            'files'            => $files,
            'folders'          => $folders,
            'videos'           => $videos,
            'audios'           => $audios,
            'photos'           => $photos,
            'disk_space'       => $auth_config['max_size_dir'],
            'disk_space_usage' => min($disk_space_usage , $auth_config['max_size_dir'])
        ];
    }

    public static function disk_total_space($dir) {
        $totalSize = 0;
        foreach (new \DirectoryIterator($dir) as $file) {
            if ($file->isFile()) {
                $totalSize += $file->getSize();
            } else {
                if (!$file->isDot()) {
                    $totalSize += self::disk_total_space($dir . '/' . $file->getFilename());
                }
            }
        }
        return $totalSize;
    }

    public static function createDir($path){
        if(!file_exists($path)) mkdir($path, 0777, true);
    }

    public static function rrmdir($dir){
        if (is_dir($dir)) {
            foreach (new \DirectoryIterator($dir) as $file) {
                if (!$file->isDot()) {
                    if ($file->isDir()) self::rrmdir($file->getPathname());
                    else self::unlink($file->getPathname());
                }
            }
            rmdir($dir);
        }
    }

    public static function unlink($file_path){
        if(is_file($file_path)) {
            return unlink($file_path);
        }
        return false;
    }

    public static function upload($file, $dir, $allowed = null, $file_name = null) {
        if(!$file->isValid()) return ['status'=>'error', 'message'=>trans('filemanager::filemanager.file_not_valid'), 'note'=>'not valid'];
        $filename = Str::slug(basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()), '-');
        $extension = strtolower($file->getClientOriginalExtension());
        if ( ($allowed != null) && (!in_array($extension, $allowed)) ) {
            return ['status'=>'warning', 'message'=>trans('filemanager::filemanager.extension_not_allowed')];
        }
        $filename = $file_name == null ? ($filename.'.'.$extension) : ($file_name.'.'.$extension);
        $file->move($dir, $filename);
        return ['status'=>'success', 'filename'=>$filename];
    }

}
