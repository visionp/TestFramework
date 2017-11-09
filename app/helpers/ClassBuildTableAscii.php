<?php
/**
 * Created by PhpStorm.
 * User: vision
 * Date: 09.11.17
 * Time: 16:31
 */

namespace app\helpers;


class ClassBuildTableAscii
{
    public $newLineDelimiter = "<br>";
    public $topBorderDelimiter = "-";
    public $sideDelimiter = "|";
    public $space = "&nbsp;";
    public $angle = "+";
    protected $padding = 1;
    protected $data;
    protected $columns = null;


    public function __construct($data) {
        $this->data = $data;
    }
    //Задаем ширину столбцов (это значение в пробелах прибавляется к масимально возможному значению)
    public function setWidthCols($v) {
        if($v < 0){
            throw new \Exception('Значение ширины должно быть больше 0');
        }
        $this->padding = $v;
    }
    public function asHtml() {
        $html = '<span style="font-family:Courier;">';
        $html .= $this->build();
        $html .= "</span>";
        return $html;
    }
    public function asText() {
        $this->newLineDelimiter = "\r\n";
        $this->space = " ";
        return $this->build();
    }
    /*строим таблицу*/
    protected function build() {
        $html = $this->border();
        $html .= $this->getHead();
        foreach($this->data as $d) {
            $html .= $this->getBody($d);
        }
        $html .= $this->newLineDelimiter . $this->border();
        return $html;
    }
    /*строим границу между строками*/
    protected function border() {
        $html = $this->angle;
        $head = $this->getColumns();
        foreach($head as $k => $a) {
            $html .= str_repeat($this->topBorderDelimiter, $a + $this->padding *2) . $this->angle;
        }
        $html .= $this->newLineDelimiter;
        return $html;
    }
    /*строим тело*/
    protected function getBody($d) {
        $html = $this->newLineDelimiter . $this->border();
        $html .= $this->sideDelimiter;
        foreach($this->columns as $key => $l){
            $text = array_key_exists($key, $d) ? $d[$key] : '' ;
            $html .= $this->cell($this->columns[$key], $text, $key);
        }
        return $html;
    }
    /*строим первую строку*/
    protected function getHead() {
        $html = $this->sideDelimiter;
        foreach($this->getColumns() as $k => $n) {
            $html .= $this->cell($n, $k, 'head');
        }
        return $html;
    }
    /*строим одну ячейку*/
    protected function cell($n, $t, $column) {
        $koeff = $this->padding + ($n - strlen($t))/2;
        $html = '';
        $koeff = $this->padding + ($n - strlen($t))/2;
        //чтобы красиво выглядело зададим поправку отступа справа
        $koeff_right = is_float($koeff) ? $koeff + 1 : $koeff;
        $separator = str_repeat($this->space, $koeff);
        $separator_right = str_repeat($this->space, $koeff_right);
        $html .= $separator . $t . $separator_right . $this->sideDelimiter;
        return $html;
    }
    /*получаем имена столбцов в качестве ключей и макс. кол-во символов в этом столбце*/
    protected function getColumns(){
        if(empty($this->columns)) {
            $this->columns = [];
            //комбинируем массивы чтобы посчитать макс кол-во элементов и в заголовке
            $combi = array_combine($this->getColumnNames(), $this->getColumnNames());
            $in_data = array_merge([$combi], $this->data);
            //считаем
            foreach($this->getColumnNames() as $name) {
                $this->columns[$name] = array_reduce($in_data, function($a, $b) use($name) {
                    $c = array_key_exists($name, $b) ? strlen($b[$name]) : 0;
                    return $a > $c ? $a : $c;
                });
            }
        }
        return $this->columns;
    }
    /*Получаем имена столбцов*/
    protected function getColumnNames(){
        $names = [];
        foreach($this->data as $d){
            $names = array_merge($names, array_keys($d));
        }
        return array_unique($names);
    }
}