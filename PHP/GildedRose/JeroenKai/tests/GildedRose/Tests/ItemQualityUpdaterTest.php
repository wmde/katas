<?php

namespace GildedRose\Tests;

use GildedRose\Item;
use GildedRose\Program;

class ItemQualityUpdaterTest extends \PHPUnit_Framework_TestCase
{
	public function testUpdateQualityWithNoItems() {
		$program = new Program( [] );
		$program->UpdateQuality();

		$this->assertSame( [], $program->getItems() );
	}

	public function testItemIsValidAfterQualityUpdate() {
		$item = $this->createTestItem();
		$this->assertItemIsValid( $this->getItemWithUpdatedQuality( $item ) );
	}

	private function createTestItem() {
		return new Item( [ 'name' => 'myItem', 'sellIn' => 20, 'quality' => 50 ] );
	}

	public function testWhenSellInIsNotZero_qualityDecreasesByOne() {
		$item = new Item( [ 'name' => 'myItem', 'sellIn' => 20, 'quality' => 50 ] );
		$itemAfterUpdate = $this->getItemWithUpdatedQuality( $item );

		$this->assertSame( 49, $itemAfterUpdate->quality );
	}

	public function testWhenSellInIsZero_qualityDecreasesTwiceAsFast() {
		$item = new Item( [ 'name' => 'myItem', 'sellIn' => 0, 'quality' => 50 ] );
		$itemAfterUpdate = $this->getItemWithUpdatedQuality( $item );

		$this->assertSame( 48, $itemAfterUpdate->quality );
	}

	public function testWhenSellInIsNotZero_SellInDecreasesByOne() {
		$item = new Item( [ 'name' => 'myItem', 'sellIn' => 20, 'quality' => 50 ] );
		$itemAfterUpdate = $this->getItemWithUpdatedQuality( $item );

		$this->assertSame( 19, $itemAfterUpdate->sellIn );
	}

	public function testWhenQualityIsZero_qualityStaysZero() {
		$item = new Item( [ 'name' => 'myItem', 'sellIn' => 20, 'quality' => 0 ] );
		$itemAfterUpdate = $this->getItemWithUpdatedQuality( $item );

		$this->assertSame( 0, $itemAfterUpdate->quality );
	}

	/**
	 * @dataProvider itemProvider
	 */
	public function testQualityOfAgedBrieIsIncreased( $startQuality, $endQuality ) {
			$item = new Item( [ 'name' => 'Aged Brie', 'sellIn' => 20, 'quality' => $startQuality ] );

			$this->assertSame( $endQuality, $this->getItemWithUpdatedQuality( $item )->quality );
	}

	public function itemProvider() {
		return [
			[ 0, 1 ],
			[ 20, 21 ],
		];
	}

	private function getItemWithUpdatedQuality( Item $item ) {
		$program = new Program( [ $item ] );
		$program->UpdateQuality();
		return $program->getItems()[0];
	}

	private function assertItemIsValid( Item $item ) {
		$this->assertInternalType( 'int', $item->sellIn );
		$this->assertInternalType( 'int', $item->quality );
		$this->assertInternalType( 'string', $item->name );

		$this->assertGreaterThanOrEqual( 0, $item->quality );
		$this->assertTrue( $item->quality <= 50 || $item->quality === 80 );

		$this->assertGreaterThanOrEqual( 0, $item->sellIn );
	}


}

