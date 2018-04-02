<?php
namespace Help;

class FileClass
{
    public static function scanDir($drive, $path=false, $needleExts=[], $filesOrFoldersIgnore=[]){
        if(!$path) $path='';
        $return = [];
        $files = self::myScanDir($relPath = call_user_func($drive, $path));
        foreach ($files as $file){
            if(array_search($file, $filesOrFoldersIgnore) !== false ) continue;
            if(is_dir($relPath.'/'.$file)) $return = array_merge($return, self::scanDir($drive, $path.'/'.$file, $needleExts, $filesOrFoldersIgnore));
            else {
                $ext = array_last(explode('.', $file));
                if(count($needleExts) && array_search($ext, $needleExts) === false ) continue;
                $returnPath = str_replace('../', '', $path).'/'.$file;
                $return[$returnPath] = $returnPath;
            }
        }
        return $return;
    }

    public static function myScanDir($dir)
    {
        $list = scandir($dir);
        unset($list[0],$list[1]);
        return array_values($list);
    }

    public static function open($file){
        return file_get_contents($file);
    }

    public static function save($drive, $file, $value, $dump=false, $mode="w"){
        $relPath = call_user_func($drive, $file);
        if($dump && is_file($relPath)) self::dump($drive, $file);
        $fp = fopen($relPath, $mode);
        fwrite($fp, $value);
        fclose($fp);
        return $relPath;
    }

    public static function dump($drive, $file){
        $relPath = call_user_func($drive, $file);
        $value = self::open($relPath);
        $dumpName = time().'-'.md5($value);
        $relDumpPath = self::save('storage_path', 'dumps/'.$dumpName, $value, false);
        $dump = new \App\Models\DumpFiles();
        $dump->drive = $drive;
        $dump->file = $file;
        $dump->dump = $dumpName;
        $dump->save();
        return $relDumpPath;
    }
}



































