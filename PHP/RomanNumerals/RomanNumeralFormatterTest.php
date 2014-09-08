<?php

class RomanNumeralFormatterTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider nonIntProvider
	 */
	public function testGivenNonInt_exceptionIsThrown( $nonInt ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->decimalToRoman( $nonInt );
	}

	public function nonIntProvider() {
		return [
			[ null ],
			[ 4.2 ],
			[ [] ],
			[ '1' ],
			[ true ],
		];
	}

	public function testGivenOverThreeThousand_exceptionIsThrown() {
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->decimalToRoman( 3001 );
	}

	public function testGivenLessThanOne_exceptionIsThrown() {
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->decimalToRoman( 0 );
	}

	/**
	 * @dataProvider integerProvider
	 */
	public function testConvertDecimal( $integer, $roman ) {
		$this->assertSame( $roman, $this->decimalToRoman( $integer ) );
	}

	public function integerProvider() {
		return [
			[ 1, 'I' ],
			[ 5, 'V' ],
			[ 10, 'X' ],
			[ 50, 'L' ],
			[ 100, 'C' ],
			[ 500, 'D' ],
			[ 1000, 'M' ],

			[ 2, 'II' ],
			[ 3, 'III' ],
			[ 20, 'XX' ],
			[ 30, 'XXX' ],
			[ 3000, 'MMM' ],

			[ 6, 'VI' ],
			[ 7, 'VII' ],
			[ 8, 'VIII' ],
			[ 2763, 'MMDCCLXIII' ],

			[ 4, 'IV' ],
			[ 9, 'IX' ],
			[ 90, 'XC' ],
			[ 90, 'XC' ],
			[ 400, 'CD' ],
			[ 900, 'CM' ],

			[ 91, 'XCI' ],
			[ 97, 'XCVII' ],

			[ 94, 'XCIV' ],
			[ 2994, 'MMCMXCIV' ],
		];
	}

	private function decimalToRoman( $decimalNumber ) {
		$this->assertIsIntInRange( $decimalNumber );

		$romanSymbols = [
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1,
		];

		$result = '';

		foreach ( $romanSymbols as $symbol => $value ) {
			while ( $decimalNumber >= $value ) {
				$result .= $symbol;
				$decimalNumber -= $value;
			}
		}

		return $result;
	}

	private function assertIsIntInRange( $decimalNumber ) {
		if ( !is_int( $decimalNumber ) ) {
			throw new InvalidArgumentException( 'The argument is not an integer.' );
		}

		if ( $decimalNumber > 3000 ) {
			throw new InvalidArgumentException( 'The argument should not be bigger than 3000.' );
		}
		if ( $decimalNumber <= 0 ) {
			throw new InvalidArgumentException( 'The argument should be bigger than 0.' );
		}
	}

}