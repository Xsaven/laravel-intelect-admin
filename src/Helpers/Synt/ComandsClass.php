<?php

namespace Help\Synt;

use Help\SyntClass;

class ComandsClass extends SyntClass
{

    public function pjax($params, $options){
        //$layout = \App\Models\Layouts::find($options['temp_id']);
        return $options['pjax']=='on' ? '@if(!request()->header(\'x-pjax\'))' : '';
    }

    public function endpjax($params, $options){
        //$layout = \App\Models\Layouts::find($options['temp_id']);
        return $options['pjax']=='on' ? '@endif' : '';
    }

    public function header_included_blade($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedBlade($options['header_included_blade']);
    }

    public function content_included_blade($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedBlade($options['content_included_blade']);
    }

    public function footer_included_blade($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedBlade($options['footer_included_blade']);
    }

    public function includedBlade($data){
        $html = "";
        foreach ($data as $path){
            $path = str_replace('/', '.', str_replace(['views/','.blade.php'], '', $path));
            $html .= "@include('{$path}')\n";
        }
        return $html;
    }

    public function header_included($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedCssAndJs($options['header_included']);
    }

    public function content_included($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedCssAndJs($options['content_included']);
    }

    public function footer_included($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        return $this->includedCssAndJs($options['footer_included']);
    }

    public function includedCssAndJs($data){
        $templates = [
            'js' => '<script type="text/javascript" src="{path}"></script>',
            'css' => '<link rel="stylesheet" href="{path}">'
        ];
        $html = "";
        foreach ($data as $path){
            $ext = array_last(explode('.', array_last(explode('/', $path))));
            $html .= str_replace('{path}', asset($path), $templates[$ext])."\n";
        }
        return $html;
    }

    public function stacks($params, $options){
        $layout = \App\Models\Layouts::find($options['temp_id']);
        $stacks = explode(',', $layout->stacks);
        $findedStacks = [];
        foreach ($stacks as $stack){
            $stack = explode(':',$stack);
            $name = isset($stack[1]) ? $stack[0] : 'none';
            $val = isset($stack[1]) ? $stack[1] : $stack[0];
            $findedStacks[$name][] = $val;
        }
        $html = "";
        $mode = isset($params[0]) ? $params[0] : 'none';
        if(isset($findedStacks[$mode])) {
            foreach ($findedStacks[$mode] as $sName) {
                //dump($sName);
                $html .= "@stack('".$sName."') \n";
            }
        }
        return $html;
    }
}