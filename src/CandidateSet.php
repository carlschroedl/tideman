<?php
namespace PivotLibre\Tideman;

use \Countable;
use PivotLibre\Tideman\Candidate;

class CandidateSet extends GenericCollection implements Countable
{
    /**
     * CandidateSet constructor.
     * @param \PivotLibre\Tideman\Candidate ...$candidates
     */
    public function __construct(Candidate ...$candidates)
    {
            $this->values = [];
            $this->add(...$candidates);
    }

    /**
     * @param \PivotLibre\Tideman\Candidate ...$candidates
     */
    public function remove(Candidate ...$candidates)
    {
        foreach ($candidates as $candidate) {
            $key = $this->makeKey($candidate);
            unset($this->values[$key]);
        }
    }

    /**
     * If Candidate is already present,then it is overwritten with the newer one
     * @param Candidate[] $candidates
     */
    public function add(Candidate ...$candidates)
    {
        foreach ($candidates as $candidate) {
            $key = $this->makeKey($candidate);
            $this->values[$key] = $candidate;
        }
    }

    /**
     * @param \PivotLibre\Tideman\Candidate $candidate
     * @return string key
     */
    protected function makeKey(Candidate $candidate) : string
    {
        $key = $candidate->getId();
        return $key;
    }

    /**
     * @param \PivotLibre\Tideman\Candidate $candidate
     * @return \PivotLibre\Tideman\Candidate Candidate if present, or null if no such Candidate is in this Set.
     */
    public function get(Candidate $candidate) : Candidate
    {
        return $this->values[$this->makeKey($candidate)] ?? null;
    }

    /**
     * @param \PivotLibre\Tideman\Candidate $candidate
     * @return bool true if Candidate is in set, false otherwise
     */
    public function contains(Candidate $candidate) : bool
    {
        return null == $this->get($candidate);
    }

    public function count() : int
    {
        return sizeof($this->values);
    }
}
