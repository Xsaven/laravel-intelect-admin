<?php
namespace Help;

class SyntClass
{
    public $selector = "#";
    public $functionValueDelimetr = ":";
    public $functionValueDoneDelimetr = ";";

    public $replaces = [
        'pjax' => \Help\Synt\ComandsClass::class,
        'endpjax' => \Help\Synt\ComandsClass::class,
        'stacks' => \Help\Synt\ComandsClass::class,
        'header_included' => \Help\Synt\ComandsClass::class,
        'content_included' => \Help\Synt\ComandsClass::class,
        'footer_included' => \Help\Synt\ComandsClass::class,
        'header_included_blade' => \Help\Synt\ComandsClass::class,
        'content_included_blade' => \Help\Synt\ComandsClass::class,
        'footer_included_blade' => \Help\Synt\ComandsClass::class,
    ];



    public $functions = [];
    private $options = [];

    public function __construct($options=[])
    {
        $this->options = $options;
        foreach ($options as $key => $val) $this->{$key} = $val=='true' ? true : ($val=='false' ? false : $val);
    }

    public function render($value){
        $patern = str_replace([
            '{selector}',
            '{functionValueDelimetr}',
            '{functionValueDoneDelimetr}'
        ], [
            $this->selector,
            $this->functionValueDelimetr,
            $this->functionValueDoneDelimetr,
        ], '/{selector}+[a-zA-Z_{functionValueDelimetr}{functionValueDoneDelimetr} ]+{selector}/');


        $value = str_replace(['\r', '\n'], '', $value);
        preg_match_all($patern, $value, $matches, PREG_OFFSET_CAPTURE);
        return $this->findFunctions($matches[0], $value);
        //dd($matches, $patern, $value);
    }

    private function findFunctions($functions, $value){
        foreach ($functions as $function){
            $foolPattern = $function[0];
            $function = str_replace([$this->selector], '', $function[0]);
            $function = explode($this->functionValueDelimetr, $function);

            $name = $function[0];
            $params = isset($function[1]) ? explode($this->functionValueDoneDelimetr, $function[1]) : [];
            $comand = '';
            if(isset($this->replaces[$name])){
                $comand = (new $this->replaces[$name])->{$name}($params, $this->options);
            }
            $value = str_replace($foolPattern, $comand, $value);
        }

        return $value;
    }
}



































