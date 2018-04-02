<?php

namespace Lia\Controllers;

use Illuminate\Http\Request;
use Lia\Addons\Themes\Contracts\ThemeContract;

class ThemeController {

    public function index(Request $request)
    {
        $themes = app(ThemeContract::class)->all();
        $output = [];
        foreach ($themes as $theme) {
            $output[] = [
                'Name'    => $theme->get('name'),
                'Author'  => $theme->get('author'),
                'version' => $theme->get('version'),
                'parent'  => $theme->get('parent'),
            ];
        }
        dd($output);
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
}