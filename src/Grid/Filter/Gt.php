<?php

namespace Lia\Grid\Filter;

class Gt extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected $view = 'lia::filter.gt';

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|void
     */
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (is_null($value)) {
            return;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, '>=', $this->value);
    }
}
