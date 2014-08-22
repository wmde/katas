<?php

namespace GildedRose;

class Item
{
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var int
	 */
    public $sellIn;

	/**
	 * @var int
	 */
    public $quality;

    public function __construct(array $parts)
    {
        foreach ($parts as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
}

