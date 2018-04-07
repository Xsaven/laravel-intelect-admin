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
                'name'    => $theme->get('name'),
                'author'  => $theme->get('author'),
                'description'  => $theme->get('description'),
                'parent'  => $theme->get('parent'),
            ];
        }
        //dd($output);

        return response($output);
    }

    public function create(Request $request)
    {
        $themePath = config('theme.theme_path');
        $theme = [];
        $theme['name'] = strtolower($request->name);
        $theme['description'] = $request->description ? $request->description : '';
        $createdThemePath = $themePath.'/'.$theme['name'];

        if (\File::isDirectory($createdThemePath)) {
            return response(['status' => 'error', 'message' => 'Sorry Boss '.ucfirst($theme['name']).' Theme Folder Already Exist !!!']);
        }

        $theme['parent'] = $request->parent ? strtolower($request->parent) : '';
        $themeFolders = config('theme.folders');
        $themeStubPath = __DIR__ . '/../Addons/Themes/Console/stubs';

        $themeStubFiles = config('theme.files');
        $themeStubFiles['theme'] = 'theme.php';

        $this->makeDir($createdThemePath);

        foreach ($themeFolders as $key => $folder) {
            $this->makeDir($createdThemePath.'/'.$folder);
        }

        $this->createStubs($themeStubPath, $theme, $themeStubFiles, $createdThemePath);

        return response(['status' => 'success', 'message' => ucfirst($theme['name']).' Theme Folder Successfully Generated !!!']);
    }

    public function createStubs($themeStubPath, $theme, $themeStubFiles, $createdThemePath)
    {
        foreach ($themeStubFiles as $filename => $storePath) {
            if ($filename == 'css' || $filename == 'js') {
                $theme[$filename] = ltrim($storePath,
                    rtrim(config('theme.folders.assets'), '/').'/');
            }
        }
        foreach ($themeStubFiles as $filename => $storePath) {
            $themeStubFile = $themeStubPath.'/'.$filename.'.stub';
            $this->makeFile($theme, $themeStubFile, $createdThemePath.'/'.$storePath);
        }
    }


    private function replaceStubs($theme, $contents)
    {
        $mainString = [
            '[NAME]',
            '[TITLE]',
            '[DESCRIPTION]',
            '[AUTHOR]',
            '[PARENT]',
            '[CSSNAME]',
        ];
        $replaceString = [
            $theme['name'],
            'Theme '.$theme['name'],
            $theme['description'],
            env('APP_NAME'),
            $theme['parent'],
            $theme['css'],
        ];

        $replaceContents = str_replace($mainString, $replaceString, $contents);

        return $replaceContents;
    }

    public function makeFile($theme, $file, $storePath)
    {
        if (\File::exists($file)) {
            $content = $this->replaceStubs($theme, \File::get($file));

            \File::put($storePath, $content);
        }
    }

    private function makeDir($directory)
    {
        if (!\File::isDirectory($directory)) {
            \File::makeDirectory($directory, 0777, true);
        }
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