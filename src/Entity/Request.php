<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

class Request
{

    private $data;
    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }

    public function transferSizeInBytes() : float
    {
        return $this->data->transferSize;
    }

    public function resourceSizeInBytes() : float
    {
        return $this->data->resourceSize;
    }

    public function url() : string
    {
        return $this->data->url;
    }
}
