<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

class ErrorResponse implements Response
{

    private $data;
    private $statusCode;
    public function __construct(array $data, int $statusCode)
    {
        if (! isset($data['co2'])) {
            $data['co2'] = 0;
        }
        if (! isset($data['transferKB'])) {
            $data['transferKB'] = 0;
        }
        if (! isset($data['resourceKB'])) {
            $data['resourceKB'] = 0;
        }
        if (! isset($data['url'])) {
            $data['url'] = '';
        }
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function hasError(): bool
    {
        return true;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function statusCode() : int
    {
        return $this->statusCode;
    }
}
