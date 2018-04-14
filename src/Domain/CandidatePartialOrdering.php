<?php
namespace PivotLibre\Tideman\Domain;

use PivotLibre\Tideman\Candidate;

class CandidatePartialOrdering
{
    protected $values;
    public function __construct(Candidate ...$candidates)
    {
        $this->values = $candidates;
    }
}
