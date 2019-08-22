<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

use Websupporter\Co2\Api\Helper\Calculator;

class RequestCollection
{

    private $calculator;
    private $parentRequestUrl;
    private $requests;
    public function __construct(Calculator $calculator, string $parentRequestUrl, Request ...$requests)
    {
        $this->calculator = $calculator;
        $this->parentRequestUrl = $parentRequestUrl;
        $this->requests = $requests;
    }

    public function totalGramCo2() : float
    {
        return $this->calculator->calculateGramCo2ForRequests(... $this->requests);
    }

    public function forUrl() : string
    {
        return $this->parentRequestUrl;
    }

    public function transferBytes() : float
    {
        $total = 0;
        foreach ($this->requests as $request) {
            $total += $request->transferSizeInBytes();
        }
        return $total;
    }

    public function resourceBytes() : float
    {
        $total = 0;
        foreach ($this->requests as $request) {
            $total += $request->resourceSizeInBytes();
        }
        return $total;
    }
}
