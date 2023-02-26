<?php

namespace App\Admin\Resource;

use App\Admin\General\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'src',
        'thumbnail_src',
        'file_type',
        'file_extension',
        'customer_id',
        'user_created_id',
        'important',
        'description',
        'note',
    ];
    public $rules       = [
        'name'              => '',
        'src'               => 'required',
        'thumbnail_src'     => '',
        'file_type'         => '',
        'file_extension'    => '',
        'customer_id'       => 'required',
        'user_created_id'   => 'required',
        'important'         => 'required',
        'description'       => '',
        'note'              => '',
    ];
    public function newQuery($excludeDeleted = true) {
        $query = parent::newQuery($excludeDeleted);
        if (\Account::has_permission(['route'=>'attachment','permission_name'=>'access-all']))
            return $query;
        return $query->where('user_created_id', \Auth::user()->id);
    }

    public static function boot(){
        parent::boot();

        self::created(function($model){
            if (str_contains($model->file_type, 'image'))
                return self::generate_thumbnail($model);
            return $model;
        });

        // self::updated(function($model){
        //     // ... code here
        // });

        self::deleted(function($model){
            // if (!empty($model->src))
            //     self::unlink(public_path($model->src));
            // if (!empty($model->thumbnail_src))
            //     self::unlink(public_path($model->thumbnail_src));
            return $model;
        });
    }

    protected static $photo_formats = ['jpeg', 'bmp', 'cod', 'gif', 'ief', 'jpg', 'jfif', 'tif', 'ras', 'cmx', 'ico', 'pnm', 'pbm', 'pgm', 'ppm', 'rgb', 'xbm', 'xpm', 'xwd', 'png', 'jps', 'fh'];


    public static function get_customer_attachment($customer_id){
        return self::where('customer_id', $customer_id)->get();
    }

    public static function generate_thumbnail($model){
        try {
            $thumbnail_link = self::generate_thumbnail_image(public_path($model->src));
            return $model->update(['thumbnail_src' => $thumbnail_link]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function generate_thumbnail_image($source_path, $thumbnail_dir=null, $width=null, $height=null, $force=true){
        if (empty($source_path) || !\File::exists($source_path))
            return false;
        $filename       = \File::name( $source_path );
        $filesize       = \File::size( $source_path );
        $extension      = \File::extension( $source_path );

        $thumbnail_dir  = $thumbnail_dir ? $thumbnail_dir : config('cms.customer-thumbnail');
        if(!file_exists($thumbnail_dir)) mkdir($thumbnail_dir, 0777, true);
        $thumbnail_link = $thumbnail_dir.'/thumbnail-'.implode('.', [$filename.\Str::random(5), $extension]);
        $thumbnail_path = public_path($thumbnail_link);
        try {
            $resize_width   = $width  ? $width  : config('cms.thumbnail-config.width');
            $resize_height  = $height ? $height : config('cms.thumbnail-config.height');
            \Image::make( $source_path )
                    ->resize( $resize_width, $resize_height, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save( $thumbnail_path );
            return $thumbnail_link;
        } catch (Exception $e) {
            if (!$force)
                throw new Exception($e->getMessage());
            return false;
        }
    }

    public static function unlink($file_path){
        if (is_dir($file_path))
            return \File::deleteDirectory($file_path);
        return \File::delete($file_path);
    } 
}

