<?php
namespace Lia\Controllers\RemoteDataControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Lia\Addons\Scaffold\ControllerCreator;
use Lia\Addons\Scaffold\MigrationCreator;
use Lia\Addons\Scaffold\ModelCreator;
use Lia\Addons\Scaffold\FactoryCreator;

use Illuminate\Support\Facades\Artisan;

class RemoteScaffoldController extends Controller{

    public function get(Request $request)
    {

    }

    public function select(Request $request)
    {

    }

    public function run_factory(Request $request){
        if(is_file(base_path($request->target))){
            $data = \Help\FileClass::open(base_path($request->target));
            preg_match('/factory->define\((.*?),/',$data,$result);
            $model = trim(str_replace(["::class","'","\""," ","\n"],'',$result[1]));
            $num = (int)$request->factory_num;
            if($num>0){
                $count = factory($model, $num)->create();
                return response(['status' => 'success', 'message' => "Factory added (".count($count).")"]);
            }else{
                return response(['status' => 'error', 'message' => 'count factory num']);
            }
        }
        return response(['status' => 'error', 'message' => 'File not found!']);
    }

    public function insert(Request $request)
    {
        $paths = [];
        $message = '';

        if($request->run_factory && $request->run_factory=='1'  && $request->new_run_factory){
            // 6. Run factory
            if(isset($request->run_factory) && $request->run_factory=='1' && isset($request->run_factory_num) && is_numeric($request->run_factory_num)){
                $count = factory($request->model_name, (int)$request->run_factory_num)->create();
                $message .= "Factory added (".count($count).")";
                return response($message);
            }
        }

        try {

            // 1. Create model.
            if (isset($request->create['model']) && $request->create['model']=='1') {
                $modelCreator = new ModelCreator($request->table_name, $request->model_name);

                $paths['model'] = $modelCreator->create(
                    $request->primary_key,
                    $request->timestamps == '1',
                    $request->soft_deletes == '1',
                    json_decode($request->fields, true)
                );
            }

            // 2. Create controller.
            if (isset($request->create['controller']) && $request->create['controller']=='1') {
                $paths['controller'] = (new ControllerCreator($request->controller_name))
                    ->create($request->model_name);
            }

            // 3. Create migration.
            if (isset($request->create['migration']) && $request->create['migration']=='1') {
                $migrationName = 'create_'.$request->table_name.'_table';

                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                    json_decode($request->fields, true),
                    $request->get('primary_key', 'id'),
                    $request->timestamps == '1',
                    $request->soft_deletes == '1'
                )->create($migrationName, database_path('migrations'), $request->table_name);
            }

            // 4. Run migrate.
            if (isset($request->create['migrate']) && $request->create['migrate']=='1') {
                Artisan::call('migrate');
                $message = Artisan::output();
            }

            // 5. Create Factory
            if(isset($request->create['factory']) && $request->create['factory']=='1'){
                $factoryCreator = new FactoryCreator($request->table_name, $request->model_name);

                $paths['factory'] = $factoryCreator->create(
                    $request->timestamps == '1',
                    $request->soft_deletes == '1',
                    json_decode($request->fields, true)
                );
            }
        } catch (\Exception $exception) {

            // Delete generated files if exception thrown.
            app('files')->delete($paths);

            return $this->backWithException($exception);
        }

        return $this->backWithSuccess($paths, $message);
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

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}