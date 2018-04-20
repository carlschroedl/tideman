<?php

namespace PivotLibre\Tideman\Domain;

use Ds\Map;
use PivotLibre\Tideman\Candidate;

class PartialCandidateRanking extends PartialRanking
{
    public function __construct(iterable $entityToRank)
    {
        $this->entityClass = Candidate::class;
        parent::__construct($entityToRank);
    }

    public static function fromStringIdsToIntRanks(iterable $candidateIdToInt): PartialCandidateRanking
    {
        $candidateToRank = new Map();

        foreach ($candidateIdToInt as $candidateId => $rank) {
            if (!is_string($candidateId)) {
                throw new \InvalidArgumentException("candidate IDs must be strings. Got '$candidateId' instead.");
            } else if (!is_int($rank)) {
                throw new \InvalidArgumentException("candidate IDs must be ints. Got '$rank' instead.");
            } else {
                $candidate = new Candidate($candidateId);
                $candidateToRank->put($candidate, $rank);
            }
        }
        $ranking = new PartialCandidateRanking($candidateToRank);
        return $ranking;
    }
}
