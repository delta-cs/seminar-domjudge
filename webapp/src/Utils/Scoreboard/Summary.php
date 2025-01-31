<?php declare(strict_types=1);

namespace App\Utils\Scoreboard;

use App\Entity\ContestProblem;

class Summary
{
    /** @var float[] */
    protected array $numberOfPoints = [];

    /** @var int[] */
    protected array $affiliations = [];

    /** @var int[] */
    protected array $countries = [];

    /** @var ProblemSummary[] */
    protected array $problems = [];

    /**
     * @param ContestProblem[] $problems
     */
    public function __construct(array $problems)
    {
        foreach (array_keys($problems) as $problemId) {
            $this->problems[$problemId] = new ProblemSummary();
        }
    }

    public function getNumberOfPoints(int $sortorder): float
    {
        return $this->numberOfPoints[$sortorder] ?? 0;
    }

    public function addNumberOfPoints(int $sortorder, float $numberOfPoints): void
    {
        if (!isset($this->numberOfPoints[$sortorder])) {
            $this->numberOfPoints[$sortorder] = 0;
        }
        $this->numberOfPoints[$sortorder] += $numberOfPoints;
    }

    /**
     * @return int[]
     */
    public function getAffiliations(): array
    {
        return $this->affiliations;
    }

    public function incrementAffiliationValue(int $affiliationId): void
    {
        if (!isset($this->affiliations[$affiliationId])) {
            $this->affiliations[$affiliationId] = 0;
        }
        $this->affiliations[$affiliationId]++;
    }

    /**
     * @return int[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    public function incrementCountryValue(string $country): void
    {
        if (!isset($this->countries[$country])) {
            $this->countries[$country] = 0;
        }
        $this->countries[$country]++;
    }

    /**
     * @return ProblemSummary[]
     */
    public function getProblems(): array
    {
        return $this->problems;
    }

    public function getProblem(int $problemId): ?ProblemSummary
    {
        return $this->problems[$problemId] ?? null;
    }
}
