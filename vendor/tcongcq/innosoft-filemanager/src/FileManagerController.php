<?php

namespace InnoSoft\FileManager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    protected $auth_config;

    public function __construct() {
        $user_model = \App::make(config('auth.providers.users.model'));
        if (!(method_exists($user_model, 'allowed_filemanager'))) {
            abort(404);
        } else {
            $this->auth_config = $user_model->allowed_filemanager();
            if(empty($this->auth_config)) abort (404);
        }
    }

    public function index() {
        if (!Hash::check(env('APP_KEY'), request()->secret)) {
            abort(404);
        }
        return view('filemanager::dialog')->with('auth_config', $this->auth_config);
    }

    public function store() {
        return FileManagement::scandir($this->auth_config);
    }

    public function postCreateDir() {
        if(!in_array('create_folders', $this->auth_config['permissions'])) return ['status'=>'error', 'message'=>trans('filemanager::filemanager.create_dir_denied')];
        $path = $this->auth_config['upload_dir'] . \Request::get('path');
        $dirname = Str::slug(\Request::get('dirname'), '-');
        try {
            FileManagement::createDir($path.'/'.$dirname);
            return ['status'=>'success', 'message'=>trans('filemanager::filemanager.created_dir', ['Dirname'=>$dirname])];
        } catch (\Exception $e) {
            return ['status'=>'error', 'message'=> $e->getMessage()];
        }
    }

    public function postDelete() {
        $can_delete_folder = in_array('delete_folders', $this->auth_config['permissions']);
        $can_delete_file = in_array('delete_files', $this->auth_config['permissions']);
        if(!$can_delete_folder && !$can_delete_file) {
            return ['status'=>'error', 'message'=>trans('filemanager::filemanager.delete_denied')];
        }
        $inp = \Request::all();
        $folders = 0;
        $files = 0;
        try {
            foreach ($inp['ids'] as $file) {
                if($file['type'] == 'folder' && $can_delete_folder){
                    FileManagement::rrmdir($file['path']);
                    $folders++;
                }else if($can_delete_file){
                    FileManagement::unlink($file['path']);
                    $files++;
                }
            }
            $message = trans('filemanager::filemanager.deleted');
            if($folders) $message .= (' '.$folders.' '. ($folders > 1 ? trans('filemanager::filemanager.folders'):trans('filemanager::filemanager.folder')));
            if($files) $message .= (( $folders>0 ?',':'' ).' '.$files.' '. ($files > 1 ? trans('filemanager::filemanager.files'):trans('filemanager::filemanager.file')));
            return ['status'=>'success', 'message'=>$message];
        } catch (\Exception $e) {
            return ['status'=>'error', 'message'=> $e->getMessage()];
        }
    }

    public function postUpload() {
        $can_upload_file = in_array('upload_files', $this->auth_config['permissions']);
        if(!$can_upload_file) {
            return ['status'=>'error', 'message'=>trans('filemanager::filemanager.upload_denied')];
        }
        if(!\Request::hasFile('file')) return ['status'=>'error', 'message'=>trans('filemanager::filemanager.file_not_valid'), 'note'=>'file not found'];
        $inp = \Request::all();
        $client_size = $inp['file']->getSize();
        // check max_size_upload
        if($client_size > ($this->auth_config['max_size_upload'] * 1048576)) return ['status'=>'warning', 'message'=>trans('filemanager::filemanager.max_size_upload_denied', ['Size'=>$this->auth_config['max_size_upload']])];
        // check disk_total_space of current user
        $disk_total_space = FileManagement::disk_total_space($this->auth_config['upload_dir']);
        if(($disk_total_space + $client_size) > ($this->auth_config['max_size_dir'] * 1048576)) return ['status'=>'warning', 'message'=>trans('filemanager::filemanager.max_size_dir_denied', ['Size'=>$this->auth_config['max_size_dir']])];

        // try upload
        try{
            $path = public_path( $this->auth_config['upload_dir'] . $inp['path'] );
            return FileManagement::upload($inp['file'], $path, $this->auth_config['allowed_extensions']);
        } catch (\Exception $e){
            return ['status'=>'error', 'code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }
    }

    public function postRename() {
        $inp = \Request::all();
        try {
            $path = public_path( $this->auth_config['upload_dir'] . $inp['path'] );
            switch ($inp['file']['type']) {
                case 'folder':
                    $can_rename_folder = in_array('rename_folders', $this->auth_config['permissions']);
                    if(!$can_rename_folder) {
                        return ['status'=>'error', 'message'=>trans('filemanager::filemanager.rename_folder_denied')];
                    }
                    rename($path.'/'.$inp['file']['filename'], $path.'/'.Str::slug($inp['new_filename']));
                    break;
                default:
                    $can_rename_file = in_array('rename_files', $this->auth_config['permissions']);
                    if(!$can_rename_file) {
                        return ['status'=>'error', 'message'=>trans('filemanager::filemanager.rename_file_denied')];
                    }
                    rename($path.'/'.$inp['file']['filename'].'.'.$inp['file']['extension'], $path.'/'.Str::slug($inp['new_filename']).'.'.$inp['file']['extension']);
                    break;
            }
            return ['status'=>'success', 'message'=>trans('filemanager::filemanager.saved')];
        } catch (\Exception $e) {
            return ['status'=>'error', 'code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }
    }

    public function postSavePhotoEditor() {
        $can_photo_edit = in_array('photo_editor', $this->auth_config['permissions']);
        if(!$can_photo_edit) {
            return ['status'=>'error', 'message'=>trans('filemanager::filemanager.photo_editor_denied')];
        }
        if(!\Request::hasFile('file')) return ['status'=>'error', 'message'=>trans('filemanager::filemanager.file_not_valid'), 'note'=>'file not found'];
        $inp = \Request::all();
        // try upload
        try{
            $path = public_path( $this->auth_config['upload_dir'] . $inp['path'] );
            $inp['file']->move($path, $inp['filename'].'_edit_'.time().'.'.$inp['extension']);
            return ['status'=>'success', 'message'=>trans('filemanager::filemanager.saved')];
        } catch (\Exception $e){
            return ['status'=>'error', 'code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }
    }
}
