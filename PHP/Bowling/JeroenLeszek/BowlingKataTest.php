<?php

class Bowling {

	public function getScoreForTurns( $turns ) {
		$score = 0;

		$turnsArray = str_split($turns);
		$lastTurnWasStrikeOrSpare = false;
		$lastTurnWasStrike = false;
		$twoTurnsBackWasStrike = false;

		for ( $i = 0; $i < count( $turnsArray ); $i++) {
			$currentTurn = $turnsArray[$i];
			$scoreForCurrentTurn = $this->getScoreForSingleTurn( $currentTurn );

			if ( !$this->nextThrowIsSpare( $i, $turnsArray ) ) {
				$score += $scoreForCurrentTurn;
			}

			if ( $lastTurnWasStrikeOrSpare ) {
				$score += $scoreForCurrentTurn;
			}
			if ( $twoTurnsBackWasStrike ) {
				$score += $scoreForCurrentTurn;
			}

			$twoTurnsBackWasStrike = $lastTurnWasStrike;
			$lastTurnWasStrikeOrSpare = $this->shouldNextTurnDouble( $currentTurn );
			$lastTurnWasStrike = $this->isStrike( $currentTurn );
		}

		return $score;
	}

	private function shouldNextTurnDouble( $currentTurn ) {
		return $this->isSpare( $currentTurn ) || $this->isStrike( $currentTurn );
	}

	private function nextThrowIsSpare( $currentThrowIndex, $turnsArray ) {
		return array_key_exists( $currentThrowIndex + 1, $turnsArray )
		&& $this->isSpare( $turnsArray[$currentThrowIndex + 1] );
	}

	private function isSpare( $turn ) {
		return $turn === '/';
	}

	private function isStrike( $turn ) {
		return $turn === 'X';
	}

	private function getScoreForSingleTurn( $turn ) {
		if ( $turn === '-' ) {
			return 0;
		}

		if ( $this->isStrike( $turn ) || $this->isSpare( $turn ) ) {
			return 10;
		}

		return (int)$turn;
	}

}

class BowlingKataTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider bowlingProvider
	 */
	public function testBowlingScoring( $expectedScore, $turns ) {
		$this->assertSame( $expectedScore, ( new Bowling() )->getScoreForTurns( $turns ) );
	}

	public function bowlingProvider() {
		return [
			'Given empty throw score is zero' => [ 0, '-' ],
			'Given one throw score is one' => [ 1, '1' ],
			'Given strike throw score is ten' => [ 10, 'X' ],
			'Given two throws of one score is two' => [ 2, '11' ],
			'Given empty throw and a spare score is ten' => [ 10, '-/' ],
			'Given one and a spare score is ten' => [ 10, '1/' ],
			'Given empty throw, a spare and a throw of one, score is 12' => [ 12, '-/1' ],
			'Given empty throw, a spare and a throw of one and a spare, score is 21' => [ 21, '-/1/' ],
			'Given strike, a throw of one and two, score is 16' => [ 16, 'X12' ],
			'Given two strikes score is 30' => [ 30, 'XX' ],
			'Given 3 strikes score is 60' => [ 60, 'XXX' ],
			'Given strike, one and spare, score is 31' => [ 31, 'X1/' ],
			'Given 9-9-9-9-9-9-9-9-9-9- score is 90' => [ 90, '9-9-9-9-9-9-9-9-9-9-' ],
			'Given 5/5/5/5/5/5/5/5/5/5/ score is 145' => [ 145, '5/5/5/5/5/5/5/5/5/5/' ],
			'Given --53X-11/1-1/5/ score is 57' => [ 57, '--53X-11/1-1/5/' ],
		];
	}

}