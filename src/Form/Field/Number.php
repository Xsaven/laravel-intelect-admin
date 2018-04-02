<?php

namespace Lia\Form\Field;

class Number extends Text
{
    protected static $js = [
        '/vendor/lia/number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $this->default((int) $this->default);

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}:not(.initialized)')
    .addClass('initialized')
    .bootstrapNumber({
        upClass: '',
        downClass: '',
        center: true
    });

EOT;

        $this->prepend('')->defaultAttribute('style', 'width: 100px');

        return parent::render();
    }
}
