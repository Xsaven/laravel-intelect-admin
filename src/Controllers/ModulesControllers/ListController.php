<?php

namespace Lia\Controllers\ModulesControllers;

use Illuminate\Http\Request;
use Lia\Addons\Modules\Facades\Module;
use Lia\Addons\Modules\Generators\FromModuleGenerator;

class ListController {

    public function index()
    {
        $list = [];
        foreach (Module::all() as $name => $module){
            $json = $module->json()->toArray();
            $json['id'] = $name;
            $list[] = $json;
        }
        return response($list);
        dd($list);
    }

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

    private function cmdDisableMethod(Request $request){
        Module::disable($request->module);
        return response(['status' => 'success', 'message' => "Module [{$request->module}] disabled!"]);
    }

    private function cmdEnableMethod(Request $request){
        Module::enable($request->module);
        return response(['status' => 'success', 'message' => "Module [{$request->module}] enabled!"]);
    }

    private function cmdEditDescriptionMethod(Request $request, $mod){
        if($request->description){
            $mod->json()->set('description', $request->description)->save();
            return response(['status' => 'success', 'message' => "Module [{$request->module}] changed!"]);
        }
    }

    public function create(Request $request)
    {
        $message = with(new FromModuleGenerator($request->name))
            ->setFilesystem(app('files'))
            ->setModule(app('modules'))
            ->setConfig(app('config'))
            ->setForce($request->force=='1')
            ->setPlain($request->plain=='1')
            ->generate();

        return response(['status' => 'success', 'message' => $message]);
    }

}