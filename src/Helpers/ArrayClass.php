<?php
namespace Help;

class ArrayClass
{
    public static function convert($model, $field='id', $fieldName=false, $emptyClear=false){
        $return = [];
        if(is_object($model)) {
            foreach ($model as $collect) $return[$collect->{$field}] = ($fieldName ? (isset($collect->{$fieldName}) ? $collect->{$fieldName} : '') : $collect);
        }else{
            foreach ($model as $collect) $return[$collect[$field]] = ($fieldName ? (isset($collect[$fieldName]) ? $collect[$fieldName] : '') : $collect);
        }
        if($emptyClear){
            foreach ($return as $key => $val) {
                if(empty($val)) unset($return[$key]);
            }
        }
        return $return;
    }

    public static function group($model, $field='id', $fieldName=false, $emptyClear=false){
        $return = [];
        if(is_object($model)) {
            foreach ($model as $collect) $return[$collect->{$field}][] = ($fieldName ? (isset($collect->{$fieldName}) ? $collect->{$fieldName} : '') : $collect);
        }else{
            foreach ($model as $collect) $return[$collect[$field]][] = ($fieldName ? (isset($collect[$fieldName]) ? $collect[$fieldName] : '') : $collect);
        }
        if($emptyClear){
            foreach ($return as $key => $val) {
                if(empty($val)) unset($return[$key]);
            }
        }
        return $return;
    }

    public static function array_tree($array, $parent_id = 'parent_id', $children = 'data') {
        $tree = [[$children => []]];
        $references = [&$tree[0]];

        foreach($array as $item) {
            if(isset($references[$item['id']])) {
                $item[$children] = $references[$item['id']][$children];
            }

            $references[$item[$parent_id]][$children][] = $item;
            $references[$item['id']] =& $references[$item[$parent_id]][$children][count($references[$item[$parent_id]][$children]) - 1];
        }

        return $tree[0][$children];
    }

    public static function clone($model, $cloneData=[], $removeCloned=false){
        $return = [];
        if(is_object($model)) $model = $model->toArray();
        foreach ($model as $keyReturn => $collect) {
            foreach ($cloneData as $key => $val) {
                $collect[$val] = isset($collect[$key]) ? $collect[$key] : "";
                if($removeCloned && isset($collect[$key])) unset($collect[$key]);
            }
            $return[$keyReturn] = $collect;
        }
        return $return;
    }
}




























