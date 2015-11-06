<?php

namespace Fruit\ChartKit;

use Fruit\Convas\Buffer;
use Fruit\Convas\Graphics;
use Fruit\Convas\WString;

/**
 * SimpleTable is *really simple* table without outer border.
 */
class SimpleTable
{
    private $data;
    private $align;

    public function __construct(array $data, $align = 'center')
    {
        $this->data = $data;
        $this->setAlign($align);
    }

    public function setAlign($align)
    {
        $align = strtolower($align);
        switch ($align) {
            case 'left':
            case 'right':
                $this->align = $align;
                break;
            default:
                $this->align = 'center';
        }
    }

    public function render($title = '')
    {
        $colWidth = array();
        $colMaxWidth = array();
        foreach ($this->data as $rid => $row) {
            $rowWidths = array();
            foreach ($row as $cid => $col) {
                $w = WString::stringWidth($col);
                $rowWidths[$cid] = $w;
                if (!isset($colMaxWidth[$cid]) or $colMaxWidth[$cid] < $w) {
                    $colMaxWidth[$cid] = $w;
                }
            }
            $colWidth[$rid] = $rowWidths;
        }

        $totalWidth = count($colMaxWidth)-1 - 1;
        foreach ($colMaxWidth as $w) {
            $totalWidth += $w;
        }
        $totalHeight = count($this->data)*2-1 - 1;

        $buf = new Buffer;
        $g = new Graphics($buf);

        $f = "drawTextBlockCenter";
        switch ($this->align) {
            case "left":
                $f = "drawTextBlock";
                break;
            case "right":
                $f = "drawTextBlockRight";
                break;
        }

        if ($title != '') {
            $g->drawTextBlockCenter(0, 0, $totalWidth, 0, $title)
              ->drawLine(0, 1, $totalWidth, 1)
              ->transit(0, 2);
        }

        foreach ($this->data as $rid => $row) {
            $pos = 0;
            foreach ($row as $cid => $col) {
                $g->$f($pos, $rid*2, $pos+$colMaxWidth[$cid], $rid*2, $col);
                $pos += $colMaxWidth[$cid] + 1;
            }
        }

        // draw vertical lines
        $pos = 0;

        for ($i = 0; $i < count($colMaxWidth) - 1; $i++) {
            $pos += $colMaxWidth[$i];
            $g->drawLine($pos, 0, $pos, $totalHeight);
            $pos++;
        }

        // draw horizontal lines
        for ($i = 1; $i < count($this->data); $i++) {
            $g->drawLine(0, $i*2-1, $totalWidth, $i*2-1);
        }

        return implode("\n", $buf->exportAll()) . "\n";
    }
}
