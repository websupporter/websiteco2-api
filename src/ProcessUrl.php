<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api;

use Websupporter\Co2\Api\Entity\RequestCollection;
use Websupporter\Co2\Api\Helper\Lighthouse;
use Websupporter\Co2\Api\Helper\Parser;

class ProcessUrl
{

    private $lighthouse;
    private $parser;
    public function __construct(
        Lighthouse $lighthouse,
        Parser $parser
    ) {
        $this->lighthouse = $lighthouse;
        $this->parser = $parser;
    }

    public function process(string $url) : RequestCollection
    {
        $jsonFile = $this->lighthouse->generateDataForUrl($url);
        return $this->parser->parseFile($jsonFile);
    }
}
