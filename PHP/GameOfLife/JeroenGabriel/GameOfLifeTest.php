<?php

// PHP 7

error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 1 );

class GameOfLifeTest extends \PHPUnit_Framework_TestCase {

	public function testParsingOfAllDeadGrid() {
		$input =
			".....\n" .
			".....\n" .
			".....\n";

		$expected = [
			[false, false, false, false, false],
			[false, false, false, false, false],
			[false, false, false, false, false],
		];

		$this->assertSame( $expected, ( new GridIO() )->stringGridToArray( $input ) );
	}

	public function testParsingOfAllLiveGrid() {
		$input =
			"*****\n" .
			"*****\n" .
			"*****\n";

		$expected = [
			[true, true, true, true, true],
			[true, true, true, true, true],
			[true, true, true, true, true],
		];

		$this->assertSame( $expected, ( new GridIO() )->stringGridToArray( $input ) );
	}

	public function testParsingMixedGrid() {
		$input =
			"*.*.*\n" .
			".*.*.\n" .
			"**.**\n";

		$expected = [
			[true, false, true, false, true],
			[false, true, false, true, false],
			[true, true, false, true, true],
		];

		$this->assertSame( $expected, ( new GridIO() )->stringGridToArray( $input ) );
	}

	public function testOutputMixedGrid() {
		$input = [
			[true, false, true, false, true],
			[false, true, false, true, false],
			[true, true, false, true, true],
		];

		$expected =
			"*.*.*\n" .
			".*.*.\n" .
			"**.**\n";


		$this->assertSame( $expected, ( new GridIO() )->arrayToStringGrid( $input ) );
	}

	public function testDeadCellsStayDead() { // no zombies!
		$deadGrid =
			".....\n" .
			".....\n" .
			".....\n";

		$this->assertSame(
			$deadGrid,
			( new GameOfLife() )->evolve( $deadGrid )
		);
	}

	public function testLiveCellWithFewerThanTwoLiveNeighbors_cellDies() {
		$inputGrid =
			".....\n" .
			"..*..\n" .
			".....\n";

		$deadGrid =
			".....\n" .
			".....\n" .
			".....\n";

		$this->assertSame(
			$deadGrid,
			( new GameOfLife() )->evolve( $inputGrid )
		);
	}

	public function testCellsWithThreeNeighbours_becomesAlive() {
		$inputGrid =
			"**...\n" .
			"*....\n" .
			".....\n";

		$expectedGrid =
			"**...\n" .
			"**...\n" .
			".....\n";

		$this->assertSame(
			$expectedGrid,
			( new GameOfLife() )->evolve( $inputGrid )
		);
	}

	public function testCellsWithMoreThanThreeNeighbours_die() {
		$inputGrid =
			"*****\n" .
			"*****\n" .
			"*****\n";

		$expectedGrid =
			"*...*\n" .
			".....\n" .
			"*...*\n";

		$this->assertSame(
			$expectedGrid,
			( new GameOfLife() )->evolve( $inputGrid )
		);
	}

	public function testGlidersCanGlide() {
		$inputGrid =
			".*...\n" .
			"..*..\n" .
			"***..\n" .
			".....\n";

		$expectedGrid =
			".....\n" .
			"*.*..\n" .
			".**..\n" .
			".*...\n";

		$this->assertSame(
			$expectedGrid,
			( new GameOfLife() )->evolve( $inputGrid )
		);
	}

}

class GameOfLife {

	/**
	 * @var GridIO
	 */
	private $gridIo;

	private $inputGrid;

	public function __construct() {
		$this->gridIo = new GridIO();
	}

	public function evolve( string $input ): string {
		$this->inputGrid = $this->gridIo->stringGridToArray( $input );
		$outputGrid = [];

		foreach ( $this->inputGrid as $row => $rowCells ) {
			$outputGrid[] = $this->evolveRow( $row );
		}

		return $this->gridIo->arrayToStringGrid( $outputGrid );
	}

	private function evolveRow( int $row ): array {
		return array_map(
			function( int $col ) use ($row) {
				return $this->evolveCell( $row, $col );
			},
			array_keys( $this->inputGrid[$row] )
		);
	}

	private function evolveCell( int $row, int $col ): bool {
		$aliveNeighbours = $this->countAliveNeighbours( $row, $col );
		return $aliveNeighbours === 3 || ( $this->inputGrid[$row][$col] && $aliveNeighbours === 2 );
	}

	private function countAliveNeighbours( int $row, int $col ) {
		$validRows = [ $row ];

		if ( $row > 0 ) {
			$validRows[] = $row -1;
		}

		if ( $row < count( $this->inputGrid ) -1 ) {
			$validRows[] = $row +1;
		}

		$validCols = [ $col ];

		if ( $col > 0 ) {
			$validCols[] = $col -1;
		}

		if ( $col < count( $this->inputGrid[0] ) -1 ) {
			$validCols[] = $col +1;
		}

		$aliveNeighbors = 0;
		foreach ( $validRows as $currentRow ) {
			foreach( $validCols as $currentCol ) {
				if ( $currentCol == $col && $currentRow == $row ) {
					continue;
				}
				$aliveNeighbors += $this->inputGrid[$currentRow][$currentCol] ? 1 : 0;
			}
		}

		return $aliveNeighbors;
	}

}

class GridIO {
	public function stringGridToArray( string $input ): array {
		$grid = [];

		foreach ( explode( "\n", $input ) as $line ) {
			if ( trim( $line) ) {
				$grid[] = $this->stringLineToGridLine( $line );
			}
		}

		return $grid;
	}

	/**
	 * @param $line
	 *
	 * @return array
	 */
	private function stringLineToGridLine( string $line ): array {
		return array_map(
				function( string $char ) {
					return $char === '*';
				},
				str_split( $line )
		);
	}

	public function arrayToStringGrid( array $input ): string {
		$string = '';

		foreach ( $input as $row ) {
			$string .= implode( array_map(
							function( bool $isAlive ) {
								return $isAlive ? '*' : '.';
							},
							$row
					) ) . "\n";
		}

		return $string;
	}
}
