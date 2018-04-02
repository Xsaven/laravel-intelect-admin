<?php

namespace Lia\Grid\Displayers;

use Lia\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        Admin::script($this->script());

        return <<<EOT
<input type="checkbox" class="grid-row-checkbox" data-id="{$this->getKey()}" />
EOT;
    }

    protected function script()
    {
        return <<<'EOT'
$('.grid-row-checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {
    if (this.checked) {
        $(this).closest('tr').css('background-color', '#191919');
    } else {
        $(this).closest('tr').css('background-color', '');
    }
});
EOT;
    }
}
