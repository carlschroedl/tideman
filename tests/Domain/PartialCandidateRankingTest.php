<?php

namespace PivotLibre\Tideman\Domain;
use PHPUnit\Framework\TestCase;

class PartialCandidateRankingTest extends TestCase
{

    public function test__construct()
    {

    }

    public function testFromStringIdsToIntRanks()
    {
        print_r(PartialCandidateRanking::fromStringIdsToIntRanks(['a' => 1, 'b' => 2]));
    }
}
