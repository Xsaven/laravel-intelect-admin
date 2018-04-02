<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Help\Filemanager\PHPFileSystem;

class RemoteFileController extends Controller{

    private $api = false;

    public function __construct()
    {
        $this->api = new PHPFileSystem(base_path());
    }

    public function get(Request $request)
    {
        if($request->dirSize && is_dir(base_path($request->dirSize))){
            return $this->bytesToHuman($this->dir_size(base_path($request->dirSize)));
        }

        if($request->getContent && is_file(base_path($request->getContent))){
            if($request->type && $request->type=='image')
                return base64_encode(file_get_contents(base_path($request->getContent)));
            else
                return file_get_contents(base_path($request->getContent));
        }

        $this->api->virtualRoot("Files");
        $data = $this->api->ls(($request->source ? $request->source : "/"), false, "branch");
        $files = [];
        if(!is_null($request->source) && $request->source!='/'){
            $back = explode('/', $request->source);
            unset($back[count($back)-1]);
            $back = implode('/', $back);
            $add = [[
                'date' => '',
                'id' => empty($back) ? '/' : $back,
                'size' => 'UP',
                'type' => 'folder',
                'value' => '..',
                'select' => $request->source
            ]];
            if(!$request->see) $files = array_merge($add, $files);
        }
        foreach ($data[0]['data'] as $file){
            $file['icon'] = $this->setIcon($file['type']);
            $file['value'] = $this->setColor($file['value'], $file['type']);
            $file['size'] = $file['type']=='folder' ? $file['type'] : $this->bytesToHuman($file['size']);
            $file['date'] = !empty($file['date']) ? date('d.m.Y H:i:s', $file['date']) : "";
            unset($file['webix_branch'], $file['webix_child_branch']);
            $files[] = $file;
        }
        return response($files, 200);
    }

    public function select(Request $request)
    {
        if($request->extractTo && $request->extractFrom){
            $zip = new \ZipArchive;
            $zip->open(base_path($request->extractFrom));
            $zip->extractTo(base_path($request->extractTo));
            $zip->close();
            return response(['status' => 'ok']);
        }

        return response(['status' => 'error'], 500);
    }

    public function update(Request $request)
    {

        if($request->action && $request->action=='upload') {
            $result = $this->api->upload(
                $request->target,
                $_FILES['upload']['name'],
                $_FILES['upload']['tmp_name']);

            return response($result);
        }

        if($request->saveFile && is_file(base_path($request->saveFile))){
            \Help\FileClass::save('base_path', $request->saveFile, $request->saveValue);
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='new_file'){
            if(is_file(base_path($request->target.'/'.$request->source))) $source = str_replace(['-',' ',':'],['_','_',''],now()).'_'.$request->source;
            else $source = $request->source;
            \Help\FileClass::save('base_path', $request->target.'/'.$source, '');
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='new_folder'){
            $this->api->mkdir($request->source, $request->target);
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='remove'){
            foreach (explode(',', $request->source) as $source) {
                if(is_file(base_path($source)) || is_dir(base_path($source))) $this->api->batch($source, array($this->api, "rm"));
            }
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='copy'){
            foreach (explode(',', $request->source) as $source) {
                if(is_file(base_path($source)) || is_dir(base_path($source))) $this->api->batch($source, array($this->api, "cp"), $request->target);
            }
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='move'){
            foreach (explode(',', $request->source) as $source) {
                if(is_file(base_path($source)) || is_dir(base_path($source))) $this->api->batch($source, array($this->api, "mv"), $request->target);
            }
            return response(['status' => 'ok']);
        }

        if($request->action && $request->action=='rename'){
            if($request->target!=$request->source)
                return response($this->api->rename($request->source, $request->target));
            return response(['noupdate'], 200);
        }
    }

    public function setColor($value, $type)
    {
        $colors = [
            'folder' => 'white; font-weight: bold',
            'code' => 'lime',
            'text' => '#f1f1f1',
            '' => 'red',
            'image' => '#8a8aff',
            'archive' => '#e200fb',
        ];
        $color = isset($colors[$type]) ? $colors[$type] : '#f1f1f1';
        return "<span style='color: {$color};'>{$value}</span>";
    }

    public function setIcon($type)
    {
        $icons = [
            'folder' => 'folder-o',
            'code' => 'file-code-o',
            'text' => 'file-text-o',
            'image' => 'file-image-o',
            'archive' => 'file-zip-o',
            'audio' => 'file-audio-o',
            'video' => 'file-movie-o',
            'excel' => 'file-excel-o',
            'doc' => 'file-word-o'
        ];
        if(isset($icons[$type])) return $icons[$type];
        else return 'file-o';
    }

    public function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function dir_size($dir) {
        $totalsize=0;
        if ($dirstream = @opendir($dir)) {
            while (false !== ($filename = readdir($dirstream))) {
                if ($filename!="." && $filename!="..")
                {
                    if (is_file($dir."/".$filename))
                        $totalsize+=filesize($dir."/".$filename);

                    if (is_dir($dir."/".$filename))
                        $totalsize+=$this->dir_size($dir."/".$filename);
                }
            }
        }
        closedir($dirstream);
        return $totalsize;
    }
}