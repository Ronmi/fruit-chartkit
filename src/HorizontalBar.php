<?php

namespace Fruit\ChartKit;

class HorizontalBar
{
    public $label;
    public $value;

    public function __construct($label, $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    private function width($chartWidth, $maxValue)
    {
        // compute the value per block
        $unit = $maxValue / (float)$chartWidth;
        $ret = round($this->value / $unit);
        if ($ret < 1) {
            $ret = 1;
        }
        return $ret;
    }

    private function renderInside($size)
    {
        // |---------------------+
        // |                name | 100
        // |---------------------+
        $space = $size - 1 - strlen($this->label) - 1;
        return str_repeat(' ', $space) . $this->label . ' | ' . $this->value;
    }

    public function render($chartWidth, $maxValue)
    {
        $size = $this->width($chartWidth, $maxValue);

        $ret = array();
        $ret[0] = str_repeat('-', $size - 1) . '+';

        // |--+
        // |  | 100  name
        // |--+
        $ret[1] = str_repeat(' ', $size - 1) . '| ' . $this->value . '  ' . $this->label;

        if ($size - 1 >= strlen($this->label) + 2) {
            $ret[1] = $this->renderInside($size);
        }

        $ret[2] = $ret[0];

        return $ret;
    }
}
