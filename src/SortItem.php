<?php

namespace Konsulting\Laravel\Sorting;

class SortItem
{
    protected $field = '';
    protected $order = '';

    protected $map = [
        'asc'  => 'asc',
        'desc' => 'desc',
        '+'    => 'asc',
        '-'    => 'desc',
    ];

    protected $urlIndicators = [
        'asc'  => '+',
        'desc' => '-',
        'none' => '',
    ];

    protected $nextOrder = [
        'none' => 'asc',
        'asc'  => 'desc',
        'desc' => '',
    ];

    protected $arrows = [
        'asc'  => '&uarr;',
        'desc' => '&darr;',
        'none' => '',
    ];

    /**
     * Create the sort item based on field name and order. Order may be specified as part of the field name (e.g. +name
     * or -name) or it may be passed in as the second argument.
     *
     * @param string $field
     * @param string $order
     */
    public function __construct($field, $order = null)
    {
        if (isset($order)) {
            $this->setField($field);
            $this->setOrder($order);
        } else {
            $this->fromString($field);
        }
    }

    /**
     * Create the sort item from a string which contains a field name and optionally a sort order.
     *
     * @param string $input
     * @return $this
     */
    public function fromString($input)
    {
        $field = trim($input);

        if (empty($field)) {
            return $this;
        }

        if (in_array($field[0], ['-', '+'])) {
            $this->setField(substr($field, 1));
            $this->setOrder($field[0]);

            return $this;
        }

        if (substr($field, -5) == ' desc') {
            $this->setField(substr($field, 0, -5));
            $this->setOrder('desc');

            return $this;
        }

        if (substr($field, -4) == ' asc') {
            $this->setField(substr($field, 0, -4));
            $this->setOrder('asc');

            return $this;
        }

        $this->setField($field);
        $this->setOrder('none');

        return $this;
    }

    /**
     * Set the field name.
     *
     * @param $field
     */
    public function setField($field)
    {
        $this->field = trim($field);
    }

    /**
     * Get the field name.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get the sort order.
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the sort order.
     *
     * @param string $order
     */
    public function setOrder($order)
    {
        $order = trim($order);
        $this->order = isset($this->map[$order]) ? $this->map[$order] : 'none';
    }

    /**
     * Get the field name and order as an array.
     *
     * @return array
     */
    public function getArrayPair()
    {
        return [$this->field, $this->order];
    }

    /**
     * E.g. +name for an ascending sort on the name field.
     *
     * @return string
     */
    public function getUrlPart()
    {
        $indicator = $this->getUrlIndicator();

        return $indicator . $this->field;
    }

    /**
     * Get the URL indicator: + for asc, - for desc and blank string for no sort order.
     *
     * @return string
     */
    protected function getUrlIndicator()
    {
        return $this->urlIndicators[$this->order] ?? '';
    }

    /**
     * Return the HTML code for an arrow corresponding to the sort order.
     *
     * @return string
     */
    public function getArrow()
    {
        return $this->arrows[$this->order] ?? '';
    }

    /**
     * Change the sort order to the 'next' order: see the nextOrder property for details.
     *
     * @return static
     */
    public function getNext()
    {
        $order = $this->nextOrder[$this->getOrder()] ?? '';

        return new static($this->getField(), $order);
    }

    public function getRelationAndColumn()
    {
        $temp = explode('.', $this->field);

        return sizeof($temp) > 1 ? $temp : [null, $temp[0]];
    }
}
