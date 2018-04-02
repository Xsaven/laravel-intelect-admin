<?php

namespace Lia\Form\Field;

class Rate extends Text
{
    public function render()
    {
        $this->prepend('%')
            ->defaultAttribute('placeholder', 0);

        return parent::render();
    }
}
