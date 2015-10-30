<?php

namespace Fruit\ChartKit;

use Fruit\Convas\Graphics;
use Fruit\Convas\WString;

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

    public function render(Graphics $g, $chartWidth, $maxValue)
    {
        $size = $this->width($chartWidth, $maxValue);

        $g->drawLine(0, 0, $size, 0);
        $g->drawLine(0, 2, $size, 2);
        $g->drawLine($size, 0, $size, 2);
        $valStr = $this->value . '';

        $lblWidth = WString::stringWidth($this->label);

        if ($size - 1 >= $lblWidth + 2) {
            $g->drawString($size - 2 - $lblWidth, 1, $this->label);
        } else {
            $valStr .= ' ' . $this->label;
        }

        $g->drawString($size + 2, 1, $valStr);
        return;
    }
}
