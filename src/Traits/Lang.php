<?php

namespace Lia\Traits;

trait Lang
{
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes) || $this->hasGetMutator($key)) {
            $data = $this->getAttributeValue($key);

            if(empty($data)) $data = isset($this->getOriginal()[$key]) ? $this->getOriginal()[$key] : $data;

            if(is_array($data)) {
                $return = [];
                foreach ($data as $key_v => $val) {
                    if(is_null($val)) {
                        $val = '';
                    }
                    $return[$key_v] = $val;
                }
                return isset($return[\App::getLocale()]) ? $return[\App::getLocale()]: $return;
            }
            return $data;
        }
        return $this->getRelationValue($key);
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

