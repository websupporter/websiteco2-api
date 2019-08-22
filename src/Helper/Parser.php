<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Helper;

use Websupporter\Co2\Api\Entity\Request;
use Websupporter\Co2\Api\Entity\RequestCollectionFactory;

class Parser
{

    private $factory;
    public function __construct(RequestCollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function parseFile($file)
    {
        if (! is_readable($file)) {
            throw new \RuntimeException("Cant read $file");
        }

        $data = json_decode(file_get_contents($file));
        $requests = $data->audits->{'network-requests'}->details->items;

        $requests = array_map(
            function (\stdClass $item) : Request {
                return new Request($item);
            },
            $requests
        );

        return $this->factory->collectionForRequests($data->finalUrl, ...$requests);
    }
}
