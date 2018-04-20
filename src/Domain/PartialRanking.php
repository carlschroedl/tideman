<?php
namespace PivotLibre\Tideman\Domain;

use Ds\Map;

abstract class PartialRanking
{
    protected $entityClass;
    protected $entityToRank;
    public function __construct(iterable $entityToRank)
    {
        $this->entityToRank = new Map();
        foreach ($entityToRank as $entity => $rank) {
            if (! is_int($rank)){
                throw new \InvalidArgumentException("'$rank' is not an int. Expected int.");
            } else if ( ! $entity instanceof $this->entityClass) {
                $className = get_class($entity);
                throw new \InvaliidArgumentException("'$className' is not of the expected type '$this->entityClass'");
            //check for duplicates
            } else if ($this->entityToRank->hasKey($entity)){
                $existingRank = $this->entityToRank->get($entity);
                throw new \InvaliidArgumentException(
                    "Failed to add ( $entity => $rank ) to the ordering. The ranking already contains an "
                    . "entry with the same key: ( $entity => $existingRank )."
                );
            } else {
                $this->entityToRank->put($entity, $rank);
            }
        }
    }

    /**
     * Ensures that the ranks are all within 1 of the next-highest or next-lowest rank
     * This is a consequence of using a map entry's value to indicate rank instead of an array entry's index.
     * @param Map $entityToRank
     */
    protected function assertContiguousRanks(Map $entityToRank)
    {
        $ranks = $entityToRank->values()->sorted();
        $ranks->reduce(function($carry, $current){
            $diff = $current - $carry;
            if ($diff > 1) {
                throw new \InvalidArgumentException(
                    "Found non-contiguous ranks: $carry and $current. (Difference of $diff)"
                );
            }
        }, $ranks->get(0));
    }
}
