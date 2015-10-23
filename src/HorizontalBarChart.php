<?php

namespace Fruit\ChartKit;

class HorizontalBarChart
{
    private $bars;
    public $width;

    public function __construct($width = 0)
    {
        $this->bars = array();
        if ($width < 1) {
            $width = exec('tput cols') - 2;
        }
        $this->width = $width;
    }

    public function add($name, $value)
    {
        $this->bars[] = new HorizontalBar($name, $value);
        return $this;
    }

    public function render()
    {
        $ret = "\n";
        $chartWidth = $this->width;
        $maxValue = 0;
        foreach ($this->bars as $bar) {
            if ($bar->value > $maxValue) {
                $maxValue = $bar->value;
            }
        }
        $chartWidth -= strlen($maxValue) + 1;

        foreach ($this->bars as $bar) {
            $ret .= "|\n";
            $plot = $bar->render($chartWidth, $maxValue);
            foreach ($plot as $line) {
                $ret .= sprintf("|%s\n", $line);
            }
        }

        $ret .= "|\n";
        return $ret;
    }
}
