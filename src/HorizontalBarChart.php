<?php

namespace Fruit\ChartKit;

use Fruit\Convas\Buffer;
use Fruit\Convas\Graphics;
use Fruit\Convas\WString;

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

    public function render($title = '')
    {
        $buf = new Buffer;
        $g = new Graphics($buf);

        $chartWidth = $this->width;
        $maxValue = 0;
        foreach ($this->bars as $bar) {
            if ($bar->value > $maxValue) {
                $maxValue = $bar->value;
            }
        }
        $chartWidth -= strlen($maxValue) + 1;

        $g->drawLine(0, 0, 0, count($this->bars) * 4 + 1)->transit(0, 1);

        foreach ($this->bars as $bar) {
            $bar->render($g, $chartWidth, $maxValue);
            $g->transit(0, 4);
        }

        if ($title != '') {
            $g->transit(0, -1);
            $g->drawLine(0, 0, $this->width, 0);
            $titleLen = WString::stringWidth($title);
            $x = ceil(($this->width - $titleLen) / 2);
            $g->drawString($x, 1, $title);
        }

        return implode("\n", $buf->exportAll()) . "\n";
    }
}
