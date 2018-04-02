<?php

namespace Lia\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Collapse extends Widget implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'lia::widgets.collapse';

    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collapse constructor.
     */
    public function __construct()
    {

    }

    /**
     * Add item.
     *
     * @param string $title
     * @param string $content
     *
     * @return $this
     */
    public function add($title, $content)
    {
        $this->items[] = [
            'title'   => $title,
            'content' => $content,
        ];

        return $this;
    }

    protected function variables()
    {
        return [
            'id'         => $this->id,
            'items'      => $this->items,
            'attributes' => $this->formatAttributes(),
        ];
    }

    /**
     * Render Collapse.
     *
     * @return string
     */
    public function render()
    {
        return view($this->view, $this->variables())->render();
    }
}
