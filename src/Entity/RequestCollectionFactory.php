<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

use Websupporter\Co2\Api\Helper\Calculator;

class RequestCollectionFactory
{

    private $calculator;
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function collectionForRequests(string $parentRequestUrl, Request ...$requests) : RequestCollection
    {
        return new RequestCollection($this->calculator, $parentRequestUrl, ...$requests);
    }
}
