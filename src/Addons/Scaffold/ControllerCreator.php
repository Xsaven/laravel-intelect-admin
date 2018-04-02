<?php

namespace Lia\Addons\Scaffold;

use Illuminate\Http\Request;
use Lia\Auth\Database\Menu;

class ControllerCreator
{
    /**
     * Controller full name.
     *
     * @var string
     */
    protected $name;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * ControllerCreator constructor.
     *
     * @param string $name
     * @param null   $files
     */
    public function __construct($name, $files = null)
    {
        $this->name = $name;

        $this->files = $files ?: app('files');
    }

    /**
     * Create a controller.
     *
     * @param string $model
     *
     * @throws \Exception
     *
     * @return string
     */
    public function create($model, Request $request)
    {
        $settings = json_decode($request->controller_settings);
        $fields = json_decode($request->fields, true);

        //dd($request->all());

        $path = $this->getpath($this->name);

        if ($this->files->exists($path)) {
            throw new \Exception("Controller [$this->name] already exists!");
        }

        $stub = $this->files->get($this->getStub());

        $stub = $this->replace($stub, $this->name, $model)
                        ->replaceDisableds($stub, $settings)
                        ->replaceTimestamps($stub, $settings)
                        ->replaceGrid($stub, $fields)
                        ->replaceForm($stub, $fields)
                        ->replaceHeader($stub, $settings);

        //dd($settings, $stub);

        $this->createMenu($settings);

        $this->createRout($settings);

        $this->files->put($path, $stub);

        return $path;
    }

    protected function createMenu($settings)
    {
        if($settings->createMenu){
            Menu::create([
                'title' => $settings->header,
                'icon' => 'fa-file',
                'uri' => $settings->link,
                'type' => 'link'
            ]);
        }
    }

    protected function createRout($settings)
    {
        if(!empty($settings->link)){
            $class = str_replace($this->getNamespace($this->name).'\\', '', $this->name);
            $rout = <<<ROUTE
Route::group(['namespace' => '{$this->getNamespace($this->name)}', 'prefix'=>config('lia.route.prefix'), 'middleware'=>config('lia.route.middleware')], function (Router \$router) {
    \$router->resource('{$settings->link}', {$class}::class);
});

ROUTE;
            \Help\FileClass::save('app_path', 'Admin/routes.php', $rout, false, 'a');
        }
    }

    protected function replaceForm(&$stub, $fields)
    {
        $f = "";
        $s = str_repeat(' ', 12);
        foreach ($fields as $field){
            if(isset($field['admin']['form']) && $field['admin']['form']['add']==1){
                $form = $field['admin']['form'];
                $f .= "$s\$form->{$form['type']}(\"{$field['name']}\"".(!empty($form['field_name']) ? ",\"{$form['field_name']}\"":"").")".
                    (!empty($form['default']) ? "->default(\"{$form['default']}\")":"").
                    (!empty($form['help']) ? "->help(\"{$form['help']}\")":"").
                    (!empty($form['placeholder']) ? "->placeholder(\"{$form['placeholder']}\")":"").
                    ($form['required'] ? "->rules('required')":"");
                if($form['type']=='select' || $form['type']=='multipleSelect' || $form['type']=='listbox' || $form['type']=='radio' || $form['type']=='checkbox') $f .= "->options([])";
                $f .= ";\n";
            }
        }
        $stub = str_replace('DummyFormFields', $f, $stub);
        return $this;
    }

    protected function replaceGrid(&$stub, $fields)
    {
        $f = "";
        $s = str_repeat(' ', 12);
        foreach ($fields as $field){
            if(isset($field['admin']['grid']) && $field['admin']['grid']['add']==1){
                $grid = $field['admin']['grid'];
                $f .= "$s\$grid->{$field['name']}(".(!empty($grid['name']) ? "\"{$grid['name']}\"" : "").")".
                    ($grid['editable']!='none' ? ($grid['editable']=='text' ? "->editable()" : "->editable(\"".($grid['editable']!='select' ? $grid['editable'] : "{$grid['editable']}, []")."\")") : "").
                    ($grid['sortable'] ? "->sortable()":"");
                $f .= ";\n";
            }
        }
        $stub = str_replace('DummyGridFields', $f, $stub);
        return $this;
    }

    protected function replaceTimestamps(&$stub, $settings)
    {
        $ts = "";
        $s = str_repeat(' ', 12);
        if($settings->created_at!='none'){
            if($settings->created_at=='field') $ts .= "$s\$grid->created_at();\n";
            else $ts .= "$s\$grid->created_at()->sortable();\n";
        }
        if($settings->updated_at!='none'){
            if($settings->updated_at=='field') $ts .= "$s\$grid->updated_at();\n";
            else $ts .= "$s\$grid->updated_at()->sortable();\n";
        }
        $stub = str_replace('DummyTimestamps', $ts, $stub);
        return $this;
    }

    protected function replaceDisableds(&$stub, $settings)
    {
        $disableds = "";
        $s = str_repeat(' ', 12);
        if($settings->disableCreateButton) $disableds .= "$s\$grid->disableCreateButton();\n";
        if($settings->disablePagination) $disableds .= "$s\$grid->disablePagination();\n";
        if($settings->disableFilter) $disableds .= "$s\$grid->disableFilter();\n";
        if($settings->disableExport) $disableds .= "$s\$grid->disableExport();\n";
        if($settings->disableRowSelector) $disableds .= "$s\$grid->disableRowSelector();\n";
        if($settings->disableActions) $disableds .= "$s\$grid->disableActions();\n";
        if($settings->orderable) $disableds .= "$s\$grid->orderable();\n";
        if(str_replace(' ','',$settings->perPages)!='10,20,30,40,50') $disableds .= "$s\$grid->perPages([{$settings->perPages}]);\n";
        if($settings->paginate!=15) $disableds .= "$s\$grid->paginate({$settings->paginate});\n";
        if($settings->id!='none'){
            if($settings->id=='field') $disableds .= "$s\$grid->id('ID');\n";
            else $disableds .= "$s\$grid->id('ID')->sortable();\n";
        }

        $stub = str_replace('DummyDisabled', $disableds, $stub);
        return $this;
    }

    protected function replaceHeader(&$stub, $settings)
    {
        $stub = str_replace('DummyHeader', $settings->header, $stub);
        return $stub;
    }

    /**
     * @param string $stub
     * @param string $name
     * @param string $model
     *
     * @return string
     */
    protected function replace(&$stub, $name, $model)
    {
        $this->replaceClass($stub, $name);

        $stub = str_replace(
            ['DummyModelNamespace', 'DummyModel'],
            [$model, class_basename($model)],
            $stub
        );

        return $this;
    }

    /**
     * Get controller namespace from giving name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace(['DummyClass', 'DummyNamespace'], [$class, $this->getNamespace($name)], $stub);
    }

    /**
     * Get file path from giving controller name.
     *
     * @param $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $segments = explode('\\', $name);

        array_shift($segments);

        return app_path(implode('/', $segments)).'.php';
    }

    /**
     * Get stub file path.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__.'/stubs/controller.stub';
    }
}
