<?php

namespace Konsulting\Laravel\Sorting;

class SortItem
{
    protected $field = '';
    protected $order = '';

    protected $map = [
        'asc' => 'asc',
        'desc' => 'desc',
        '+' => 'asc',
        '-' => 'desc',
    ];

    protected $urlIndicators = [
        'asc' => '+',
        'desc' => '-',
        'none' => '',
    ];

    protected $nextOrder = [
        'none' => 'asc',
        'asc' => 'desc',
        'desc' => '',
    ];

    protected $arrows = [
        'asc' => '&uarr;',
        'desc' => '&darr;',
        'none' => '',
    ];

    public function __construct()
    {
        $args = func_get_args();

        if (count($args) > 1) {
            $this->setField($args[0]);
            $this->setOrder($args[1]);
        } else {
            $this->fromString($args[0]);
        }
    }

    public function fromString($input)
    {
        $field = trim($input);

        if (empty($field)) {
            return;
        }

        if (in_array($field[0], ['-', '+'])) {
            $this->setField(substr($field, 1));
            $this->setOrder($field[0]);
            return;
        }

        if (substr($field, -5) == ' desc') {
            $this->setField(substr($field, 0, -5));
            $this->setOrder('desc');
            return;
        }

        if (substr($field, -4) == ' asc') {
            $this->setField(substr($field, 0, -4));
            $this->setOrder('asc');
            return;
        }

        $this->setField($field);
        $this->setOrder('none');
    }

    public function setField($field)
    {
        $this->field = trim($field);
    }

    public function getField()
    {
        return $this->field;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $order = trim($order);
        $this->order = isset($this->map[$order]) ? $this->map[$order] : 'none';
    }

    public function getArrayPair()
    {
        return [$this->field, $this->order];
    }

    public function getUrlPart()
    {
        $indicator = $this->getUrlIndicator();

        return $indicator . $this->field;
    }

    protected function getUrlIndicator()
    {
        return isset($this->urlIndicators[$this->order]) ? $this->urlIndicators[$this->order] : '';
    }

    public function getArrow()
    {
        return isset($this->arrows[$this->order]) ? $this->arrows[$this->order] : '';
    }

    public function getNext()
    {
        $order = isset($this->nextOrder[$this->getOrder()]) ? $this->nextOrder[$this->getOrder()] : '';

        return new static($this->getField(), $order);
    }

    public function getRelationAndColumn()
    {
        $temp = explode('.', $this->field);

        return sizeof($temp) > 1 ? $temp : [null, $temp[0]];
    }
}
