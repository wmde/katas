<?php

class FizzBuzzTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider nonIntegerProvider
	 */
	public function testGivenNonInteger_exceptionIsThrown( $nonInteger ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->fizzBuzz( $nonInteger );
	}

	public function nonIntegerProvider() {
		return [
			[ null ],
			[ '' ],
			[ false ],
			[ 4.2 ],
			[ [] ],
		];
	}

	public function testGivenNegativeInteger_exceptionIsThrown() {
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->fizzBuzz( -1 );
	}

	public function testGivenNumberNotDivisible_numberIsReturnedAsIs() {
		$this->assertSame( "1", $this->fizzBuzz( 1 ) );
		$this->assertSame( "2", $this->fizzBuzz( 2 ) );
		$this->assertSame( "4", $this->fizzBuzz( 4 ) );
		$this->assertSame( "7", $this->fizzBuzz( 7 ) );
	}

	public function testGivenNumberDivisibleByThree_fizzIsReturned() {
		$this->assertSame( "Fizz", $this->fizzBuzz( 3 ) );
		$this->assertSame( "Fizz", $this->fizzBuzz( 6 ) );
	}

	public function testGivenNumberDivisibleByFive_buzzIsReturned() {
		$this->assertSame( "Buzz", $this->fizzBuzz( 5 ) );
		$this->assertSame( "Buzz", $this->fizzBuzz( 10 ) );
	}

	public function testGivenNumberDivisibleByThreeAndFive_fizzBuzzIsReturned() {
		$this->assertSame( "FizzBuzz", $this->fizzBuzz( 15 ) );
		$this->assertSame( "FizzBuzz", $this->fizzBuzz( 30 ) );
	}

	public function fizzBuzz( $n ) {
		if ( !is_int( $n ) || $n < 0 ) {
			throw new InvalidArgumentException( 'Argument is not a positive Integer.' );
		}

		if ( $this->isDivisibleByThreeAndFive( $n ) ) {
			return 'FizzBuzz';
		}

		if ( $n % 3 === 0 ) {
			return 'Fizz';
		}

		if ( $n % 5 === 0 ) {
			return 'Buzz';
		}

		return (string)$n;
	}

	private function isDivisibleByThreeAndFive( $n ) {
		return $n % 15 === 0;
	}

}