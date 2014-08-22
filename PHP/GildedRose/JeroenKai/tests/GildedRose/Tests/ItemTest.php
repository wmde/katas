<?php

namespace GildedRose\Tests;

use GildedRose\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructor() {
		$item = new Item( [
			'name' => 'kittens',
			'sellIn' => 10,
			'quality' => 5,
		] );

		$this->assertEquals( 'kittens', $item->name );
		$this->assertEquals( 10, $item->sellIn );
		$this->assertEquals( 5, $item->quality );
	}

}

