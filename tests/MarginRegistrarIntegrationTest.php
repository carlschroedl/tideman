<?php

namespace PivotLibre\Tideman;

use PivotLibre\Tideman\TestScenario1;
use PivotLibre\Tideman\MarginRegistrar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Each of these large tests were constructed as follows:
 *
 * Visit the Google Sheet:
 * https://docs.google.com/spreadsheets/d/1634wP6-N8GG2Fig-yjIOk7vPBn4AijXOrjq6Z2T1K8M/edit?usp=sharing
 *
 * (Optionally create a worksheet describing a collection of ballots that are grouped with a count)
 * Create a worksheet describing a collection of individuals' ballots.
 * Create a worksheet describing the expected pairs.
 *
 * Download the individual ballots worksheet as a TSV.
 * Generate a php class from the TSV by running:
 *     python parse_ballot.py <name_of_individual_ballot.tsv> > TestScenarioN.php
 * Update the name of the generated class to match the name of the file it is inside of.
 * Create a new method in MarginRegistrarIntegrationTest.
 * Inside of the method, manually construct a list of expected pairs by looking at the expected pairs worksheet.
 * Compare the expected pairs and the actual pairs using the `checkPairs()` utility method.
 *
 */
class MarginRegistrarIntegrationTest extends TestCase
{
    protected function checkPairs($expectedPairs, $ballots)
    {
        $agenda = new Agenda(...$ballots);
        $pairRegistry = (new MarginRegistrar())->register($agenda, ...$ballots);
        $this->assertEquals(sizeof($expectedPairs), $pairRegistry->getCount());
        foreach ($expectedPairs as $expectedPair) {
            $winner = $expectedPair->getWinner();
            $loser = $expectedPair->getLoser();
            $pairAsString = "{$winner->getId()} -> {$loser->getId()}";
            $actualPair = $pairRegistry->get($winner, $loser);
            $this->assertEquals($expectedPair, $actualPair, $pairAsString);
        }
    }

    public function testScenario1() : void
    {
            $ballots = (new TestScenario1())->getBallots();
            $expectedPairs = [
                new Pair(new Candidate('MM'), new Candidate('DD'), 14),
                new Pair(new Candidate('MM'), new Candidate('SY'), 12),
                new Pair(new Candidate('MM'), new Candidate('YW'), 14),
                new Pair(new Candidate('MM'), new Candidate('RR'), 6),

                new Pair(new Candidate('DD'), new Candidate('MM'), -14),
                new Pair(new Candidate('DD'), new Candidate('SY'), 0),
                new Pair(new Candidate('DD'), new Candidate('YW'), 2),
                new Pair(new Candidate('DD'), new Candidate('RR'), 2),

                new Pair(new Candidate('SY'), new Candidate('MM'), -12),
                new Pair(new Candidate('SY'), new Candidate('DD'), 0),
                new Pair(new Candidate('SY'), new Candidate('YW'), 6),
                new Pair(new Candidate('SY'), new Candidate('RR'), 6),

                new Pair(new Candidate('YW'), new Candidate('MM'), -14),
                new Pair(new Candidate('YW'), new Candidate('DD'), -2),
                new Pair(new Candidate('YW'), new Candidate('SY'), -6),
                new Pair(new Candidate('YW'), new Candidate('RR'), 6),

                new Pair(new Candidate('RR'), new Candidate('MM'), -6),
                new Pair(new Candidate('RR'), new Candidate('DD'), -2),
                new Pair(new Candidate('RR'), new Candidate('SY'), -6),
                new Pair(new Candidate('RR'), new Candidate('YW'), -6)
            ];
            $this->checkPairs($expectedPairs, $ballots);
    }

    public function testScenario2() : void
    {
            $ballots = (new TestScenario2())->getBallots();
            $expectedPairs = [
                new Pair(new Candidate('MM'), new Candidate('BT'), 6),
                new Pair(new Candidate('MM'), new Candidate('CS'), 8),
                new Pair(new Candidate('MM'), new Candidate('FE'), -1),
                new Pair(new Candidate('MM'), new Candidate('RR'), 6),


                new Pair(new Candidate('BT'), new Candidate('MM'), -6),
                new Pair(new Candidate('BT'), new Candidate('CS'), 4),
                new Pair(new Candidate('BT'), new Candidate('FE'), 6),
                new Pair(new Candidate('BT'), new Candidate('RR'), 6),

                new Pair(new Candidate('CS'), new Candidate('MM'), -8),
                new Pair(new Candidate('CS'), new Candidate('BT'), -4),
                new Pair(new Candidate('CS'), new Candidate('FE'), 0),
                new Pair(new Candidate('CS'), new Candidate('RR'), 3),

                new Pair(new Candidate('FE'), new Candidate('MM'), 1),
                new Pair(new Candidate('FE'), new Candidate('BT'), -6),
                new Pair(new Candidate('FE'), new Candidate('CS'), 0),
                new Pair(new Candidate('FE'), new Candidate('RR'), 4),

                new Pair(new Candidate('RR'), new Candidate('MM'), -6),
                new Pair(new Candidate('RR'), new Candidate('BT'), -6),
                new Pair(new Candidate('RR'), new Candidate('CS'), -3),
                new Pair(new Candidate('RR'), new Candidate('FE'), -4)
            ];
            $this->checkPairs($expectedPairs, $ballots);
    }

    public function testScenario3() : void
    {
            $ballots = (new TestScenario3())->getBallots();
            $expectedPairs = [
                new Pair(new Candidate('CS'), new Candidate('MC'), -8),
                new Pair(new Candidate('CS'), new Candidate('BT'), -4),
                new Pair(new Candidate('CS'), new Candidate('FE'), 0),
                new Pair(new Candidate('CS'), new Candidate('RR'), 3),
                new Pair(new Candidate('CS'), new Candidate('MN'), -8),

                new Pair(new Candidate('MC'), new Candidate('CS'), 8),
                new Pair(new Candidate('MC'), new Candidate('BT'), 6),
                new Pair(new Candidate('MC'), new Candidate('FE'), 0),
                new Pair(new Candidate('MC'), new Candidate('RR'), 5),
                new Pair(new Candidate('MC'), new Candidate('MN'), -2),

                new Pair(new Candidate('BT'), new Candidate('CS'), 4),
                new Pair(new Candidate('BT'), new Candidate('MC'), -6),
                new Pair(new Candidate('BT'), new Candidate('FE'), 6),
                new Pair(new Candidate('BT'), new Candidate('RR'), 6),
                new Pair(new Candidate('BT'), new Candidate('MN'), -6),

                new Pair(new Candidate('FE'), new Candidate('CS'), 0),
                new Pair(new Candidate('FE'), new Candidate('MC'), 0),
                new Pair(new Candidate('FE'), new Candidate('BT'), -6),
                new Pair(new Candidate('FE'), new Candidate('RR'), 4),
                new Pair(new Candidate('FE'), new Candidate('MN'), 0),

                new Pair(new Candidate('RR'), new Candidate('CS'), -3),
                new Pair(new Candidate('RR'), new Candidate('MC'), -5),
                new Pair(new Candidate('RR'), new Candidate('BT'), -6),
                new Pair(new Candidate('RR'), new Candidate('FE'), -4),
                new Pair(new Candidate('RR'), new Candidate('MN'), -6),

                new Pair(new Candidate('MN'), new Candidate('CS'), 8),
                new Pair(new Candidate('MN'), new Candidate('MC'), 2),
                new Pair(new Candidate('MN'), new Candidate('BT'), 6),
                new Pair(new Candidate('MN'), new Candidate('FE'), 0),
                new Pair(new Candidate('MN'), new Candidate('RR'), 6)
            ];
            $this->checkPairs($expectedPairs, $ballots);
    }

    public function testScenario4() : void
    {
            $ballots = (new TestScenario4())->getBallots();
            $expectedPairs = [
                new Pair(new Candidate('CW'), new Candidate('BB'), 2),
                new Pair(new Candidate('CW'), new Candidate('CS'), 2),
                new Pair(new Candidate('CW'), new Candidate('BT'), 2),
                new Pair(new Candidate('CW'), new Candidate('SY'), 2),

                new Pair(new Candidate('BB'), new Candidate('CW'), -2),
                new Pair(new Candidate('BB'), new Candidate('CS'), 20),
                new Pair(new Candidate('BB'), new Candidate('BT'), 20),
                new Pair(new Candidate('BB'), new Candidate('SY'), 20),

                new Pair(new Candidate('CS'), new Candidate('CW'), -2),
                new Pair(new Candidate('CS'), new Candidate('BB'), -20),
                new Pair(new Candidate('CS'), new Candidate('BT'), 2),
                new Pair(new Candidate('CS'), new Candidate('SY'), 2),

                new Pair(new Candidate('BT'), new Candidate('CW'), -2),
                new Pair(new Candidate('BT'), new Candidate('BB'), -20),
                new Pair(new Candidate('BT'), new Candidate('CS'), -2),
                new Pair(new Candidate('BT'), new Candidate('SY'), 0),

                new Pair(new Candidate('SY'), new Candidate('CW'), -2),
                new Pair(new Candidate('SY'), new Candidate('BB'), -20),
                new Pair(new Candidate('SY'), new Candidate('CS'), -2),
                new Pair(new Candidate('SY'), new Candidate('BT'), 0)
            ];
            $this->checkPairs($expectedPairs, $ballots);
    }

    public function testTideman1987Example2() : void
    {
        $ballots = (new TestScenarioTideman1987Example2())->getBallots();
        $expectedPairs = [
            new Pair(new Candidate('V'), new Candidate('W'), 2),
            new Pair(new Candidate('V'), new Candidate('X'), 18),
            new Pair(new Candidate('V'), new Candidate('Y'), -14),
            new Pair(new Candidate('V'), new Candidate('Z'), -14),

            new Pair(new Candidate('W'), new Candidate('V'), -2),
            new Pair(new Candidate('W'), new Candidate('X'), 18),
            new Pair(new Candidate('W'), new Candidate('Y'), -14),
            new Pair(new Candidate('W'), new Candidate('Z'), -14),

            new Pair(new Candidate('X'), new Candidate('V'), -18),
            new Pair(new Candidate('X'), new Candidate('W'), -18),
            new Pair(new Candidate('X'), new Candidate('Y'), 16),
            new Pair(new Candidate('X'), new Candidate('Z'), 16),

            new Pair(new Candidate('Y'), new Candidate('V'), 14),
            new Pair(new Candidate('Y'), new Candidate('W'), 14),
            new Pair(new Candidate('Y'), new Candidate('X'), -16),
            new Pair(new Candidate('Y'), new Candidate('Z'), 2),

            new Pair(new Candidate('Z'), new Candidate('V'), 14),
            new Pair(new Candidate('Z'), new Candidate('W'), 14),
            new Pair(new Candidate('Z'), new Candidate('X'), -16),
            new Pair(new Candidate('Z'), new Candidate('Y'), -2)
        ];
        $this->checkPairs($expectedPairs, $ballots);
    }

    public function testTideman1987Example4() : void
    {
        $ballots = (new TestScenarioTideman1987Example4())->getBallots();
        $expectedPairs = [
            new Pair(new Candidate('W'), new Candidate('X'), 9),
            new Pair(new Candidate('W'), new Candidate('Y'), -5),
            new Pair(new Candidate('W'), new Candidate('Z'), 3),

            new Pair(new Candidate('X'), new Candidate('W'), -9),
            new Pair(new Candidate('X'), new Candidate('Y'), 13),
            new Pair(new Candidate('X'), new Candidate('Z'), 3),

            new Pair(new Candidate('Y'), new Candidate('W'), 5),
            new Pair(new Candidate('Y'), new Candidate('X'), -13),
            new Pair(new Candidate('Y'), new Candidate('Z'), 3),

            new Pair(new Candidate('Z'), new Candidate('W'), -3),
            new Pair(new Candidate('Z'), new Candidate('X'), -3),
            new Pair(new Candidate('Z'), new Candidate('Y'), -3),
        ];
        $this->checkPairs($expectedPairs, $ballots);
    }

    public function testTideman1987Example5() : void
    {
        $ballots = (new TestScenarioTideman1987Example5())->getBallots();
        $expectedPairs = [
            new Pair(new Candidate('V'), new Candidate('W'), 9),
            new Pair(new Candidate('V'), new Candidate('X'), -7),
            new Pair(new Candidate('V'), new Candidate('Y'), 3),
            new Pair(new Candidate('V'), new Candidate('Z'), -1),

            new Pair(new Candidate('W'), new Candidate('V'), -9),
            new Pair(new Candidate('W'), new Candidate('X'), 11),
            new Pair(new Candidate('W'), new Candidate('Y'), 3),
            new Pair(new Candidate('W'), new Candidate('Z'), -1),

            new Pair(new Candidate('X'), new Candidate('V'), 7),
            new Pair(new Candidate('X'), new Candidate('W'), -11),
            new Pair(new Candidate('X'), new Candidate('Y'), 3),
            new Pair(new Candidate('X'), new Candidate('Z'), -1),

            new Pair(new Candidate('Y'), new Candidate('V'), -3),
            new Pair(new Candidate('Y'), new Candidate('W'), -3),
            new Pair(new Candidate('Y'), new Candidate('X'), -3),
            new Pair(new Candidate('Y'), new Candidate('Z'), 5),

            new Pair(new Candidate('Z'), new Candidate('V'), 1),
            new Pair(new Candidate('Z'), new Candidate('W'), 1),
            new Pair(new Candidate('Z'), new Candidate('X'), 1),
            new Pair(new Candidate('Z'), new Candidate('Y'), -5),
        ];
        $this->checkPairs($expectedPairs, $ballots);
    }
}
