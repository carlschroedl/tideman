<?php
namespace PivotLibre\Tideman\TieBreaking;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use PivotLibre\Tideman\Pair;

class TieBreakingPairComparator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $tieBreaker;

    public function __construct(PairTieBreaker $tieBreaker)
    {
        $this->logger = new NullLogger();
        $this->tieBreaker = $tieBreaker;
    }

    /**
     * Compares two Pairs, returning an integer to indicate their relative ordering.
     * @param Pair $a
     * @param Pair $b
     * @return an int :
     *  A negative int if Pair $a is more preferred than Pair $b
     *  A positive int if Pair $b is more preferred than Pair $a
     *
     * This function should never return zero. In the event of a tie, it should use the TieBreaker specified in the
     * constructor to determine a nonzero integer indicating which Pair should be treated as though it were more
     * preferred than the other.
     */
    public function compare(Pair $a, Pair $b) : int
    {
        $differenceOfStrength = $b->getVotes() - $a->getVotes();
        if (0 == $differenceOfStrength) {
            $this->logger->notice("Tie between two Pairs:\n$a\n$b\n");
            $result = $this->tieBreaker->breakTie($a, $b);
            $winner = $result < 0 ? $a : $b;
            $loser = $result < 0 ? $b : $a;
            // $this->logger->info("Tie-breaking results:\nWinner:\n$winner\nLoser:\n$loser\n");
            // echo "Tie-breaking results:\nWinner:\n$winner\nLoser:\n$loser\n";
        } else {
            $result = $differenceOfStrength;
        }
        return $result;
    }

    /**
     * A simple wrapper that simplifies referencing this instance's compare() method.
     * For example, the wrapper permits us to write:
     *
     * $tieBreaker = new MyGreatTieBreaker();
     * usort($array, new TieBreakingPairComparator($tieBreaker));
     *
     * instead of:
     *
     * $tieBreaker = new MyGreatTieBreaker();
     * usort($array, array(new TieBreakingPairComparator($tieBreaker), "compare"));
     *
     * Additional details:
     * https://stackoverflow.com/a/35277180
     */
    public function __invoke(Pair $a, Pair $b) : int
    {
        return $this->compare($a, $b);
    }
}
