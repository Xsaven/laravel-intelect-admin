<?php

namespace Lia\Controllers\ModulesControllers;

use Illuminate\Http\Request;
use Lia\Addons\Modules\Facades\Module;
use Help\Filemanager\PHPFileSystem;

use Lia\Addons\Scaffold\ControllerCreator;
use Lia\Addons\Scaffold\MigrationCreator;
use Lia\Addons\Scaffold\ModelCreator;
use Lia\Addons\Scaffold\FactoryCreator;

use Illuminate\Support\Facades\Artisan;

class IdeController {

    public function cmd(Request $request)
    {
        if($request->cmd){
            if(!($mod = Module::find($request->module))){
                return response(['status' => 'error', 'message' => "Module [{$request->module}] not found!"]);
            }
            if (method_exists($this, $method = 'cmd' . ucfirst(studly_case(strtolower($request->cmd))) . 'Method')) {
                return $this->$method($request, $mod);
            } else {
                return response(['status' => 'error', 'message' => 'CMD not found!']);
            }
        }
    }

    private function cmdRunFactoryMethod(Request $request, $mod){
        $model_name = config("modules.namespace").'\\'.$mod->getName().'\\Models\\'.ucfirst($request->model_name);
        if(isset($request->run_factory_num) && is_numeric($request->run_factory_num)){
            $count = factory($model_name, (int)$request->run_factory_num)->create();
            $message = "Factory added (".count($count).")";
            return response($message);
        }
        return response('ERROR!');
    }

    private function cmdCreateScaffoldMethod(Request $request, $mod){
        $paths = [];
        $message = '';
        $model_name = config("modules.namespace").'\\'.$mod->getName().'\\Models\\'.ucfirst($request->model_name);

        try {
            // 1. Create model.
            if (isset($request->create['model']) && $request->create['model']=='1') {
                $modelCreator = new ModelCreator($request->table_name, $model_name);

                $paths['model'] = $modelCreator->create(
                    $request->primary_key,
                    $request->route_key_name,
                    $request->timestamps == '1',
                    $request->soft_deletes == '1',
                    json_decode($request->fields, true)
                );
            }
            // 2. Create migration
            if (isset($request->create['migration']) && $request->create['migration']=='1') {
                $migrationName = 'create_'.$request->table_name.'_table';

                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                    json_decode($request->fields, true),
                    $request->get('primary_key', 'id'),
                    $request->timestamps == '1',
                    $request->soft_deletes == '1'
                )->create($migrationName, $mod->getPath().'/Database/Migrations', $request->table_name);
            }
            // 3. Run migrate.
            if (isset($request->create['migrate']) && $request->create['migrate']=='1') {
                Artisan::call('migrate');
                $message = Artisan::output();
            }
            // 4. Create Factory
            if(isset($request->create['factory']) && $request->create['factory']=='1'){
                $factoryCreator = new FactoryCreator($request->table_name, $model_name, $mod->getPath().'/Database/factories');

                $paths['factory'] = $factoryCreator->create(
                    $request->timestamps == '1',
                    $request->soft_deletes == '1',
                    json_decode($request->fields, true)
                );
            }
            // 5. Create controller.
            if (isset($request->create['controller']) && $request->create['controller']=='1') {
                $paths['controller'] = (new ControllerCreator($request->controller_name))
                    ->create($model_name, $request);
            }
        } catch (\Exception $exception) {
            app('files')->delete($paths);
            return $this->backWithException($exception);
        }

        return $this->backWithSuccess($paths, $message);
    }

    private function cmdSelectTableFieldsMysqlMethod(Request $request){
        if(!$request->table && empty($request->table)) return response(['status' => 'error', 'message' => 'Enter a table!']);

        $data = \DB::select("SHOW COLUMNS FROM {$request->table}");
        $return = [];
        foreach ($data as $value) {
            if($value->Field == 'created_at' || $value->Field == 'updated_at' || $value->Field == 'deleted_at') continue;
            $return[] = $value->Field;
        }
        return response($return);
    }

    private function cmdSelectTableMysqlMethod(Request $request){
        if(!$request->table && empty($request->table)) return response(['status' => 'error', 'message' => 'Enter a table!']);
        if(!$request->field && empty($request->field)) return response(['status' => 'error', 'message' => 'Enter a field!']);
        $data = array_map('reset', \DB::select("select {$request->field} from {$request->table} limit 100"));
        foreach ($data as $key=>$val) $data[$key] = (string)$data[$key];
        return response($data);
    }

    private function cmdGetTablesMysqlMethod(){
        $tables = array_map('reset', \DB::select('SHOW TABLES'));
        return response($tables);
    }

    private function cmdEditCfgMethod(Request $request, $mod){
        $data = json_decode($request->data, true);
        $convert = [];
        foreach ($data as $val) {
            $value = $val["value"];
            if($value=='[]') $value = [];
            if($value=='array()') $value = [];
            if($value=='false') $value = false;
            if($value=='true') $value = true;
            if(is_numeric($value)) $value = preg_match("/^\\d+\\.\\d+$/", $value) === 1 ? (float)$value : (int)$value;
            if(!empty($val['name'])) array_set($convert, $val['name'], $value);
        }
        $output = "<?php\n\nreturn " . var_export($convert, true) . ";".\PHP_EOL;
        $output = str_replace(['array (','),',');'],['[','],','];'],$output);
        if(file_put_contents($mod->getPath().'/Config/config.php', $output))
            return response(['status' => 'success', 'message' => 'File config saved!']);
        else
            return response(['status' => 'error', 'message' => 'File config do not saved!']);
    }

    private function cmdGetCfgMethod(Request $request, $mod){
        $data = array_dot(config($mod->getLowerName()));
        $return = [];
        foreach ($data as $key=>$val) $return[] = ['name' => $key, 'value' => is_array(config($mod->getLowerName().'.'.$key)) && !count(config($mod->getLowerName().'.'.$key)) ? '[]' : $val];
        return response($return);
    }

    private function cmdSaveFileMethod(Request $request){
        $path = module_path($request->module);
        if(!is_file($path.'/'.$request->target)) return response(['status' => 'error', 'message' => 'File "'.$request->target.'" not found!']);
        if(file_put_contents($path.'/'.$request->target, $request->value))
            return response(['status' => 'success', 'message' => 'File "'.$request->target.'" saved!']);
        else
            return response(['status' => 'error', 'message' => 'File "'.$request->target.'" do not saved!']);
    }

    private function cmdGetFileMethod(Request $request){
        $path = module_path($request->module);
        return file_get_contents($path.'/'.$request->getContent);
    }

    private function cmdGetLogMethod(Request $request){
        $path = module_path($request->module);
        if(!is_file($path.'/output.log')) return response([]);
        $log = file_get_contents($path.'/output.log');
        $return = [];
        foreach (explode("\n", $log) as $line){
            $line = explode(';', $line);
            if(isset($line[1])) $return[] = ['date' => $line[0], 'message' => $line[1]];
        }
        return response(array_reverse($return));
    }

    private function cmdLogWriteMethod(Request $request){
        $path = module_path($request->module);
        $file = $path.'/output.log';
        $fp = fopen($file, 'a');
        fwrite($fp, now().";".$request->message."\n");
        fclose($fp);
        return response(['date' => now()->toDateTimeString(), 'message' => $request->message]);
    }

    private function cmdGetFilesystemMethod(Request $request, $mod){
        $file = new PHPFileSystem(module_path($request->module));
        $file->virtualRoot($request->module);
        $data = $file->ls(($request->parent ? $request->parent : '/'), false, "branch");
        $files = [];
        foreach ($data[0]['data'] as $file){
            if($file['value']=='output.log') continue;
            $file['icon'] = $this->setIcon($file['type']);
            $file['value'] = $this->setColor($file['value'], $file['type']);
            if($file['type']=='folder') $file['webix_kids'] = 1;
            unset($file['webix_branch'], $file['webix_child_branch']);
            $files[] = $file;
        }
        $data = false;
        if($request->parent) {
            $data['data'] = $files;
            $data['parent'] = $request->parent;
        }
        return response($data ? $data : $files);
        dd($files);
    }

    private function setIcon($type)
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

    private function setColor($value, $type)
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

    private function backWithException(\Exception $exception)
    {
        return response(['status' => 'error', 'messages' => $exception->getMessage()]);
    }

    private function backWithSuccess($paths, $message)
    {
        $messages = [];
        foreach ($paths as $name => $path) {
            $messages[] = ucfirst($name).": $path";
        }
        $messages[] = "<br>".$message;
        return response(['status' => 'success', 'messages' => implode('<br>', $messages)]);
    }

}