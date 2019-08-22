<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

class SuccessResponse implements Response
{

    private $data;
    public function __construct(array $data)
    {
        if (! isset($data['message'])) {
            $data['message'] = '';
        }
        $this->data = $data;
    }

    public function hasError(): bool
    {
        return false;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function statusCode() : int
    {
        return 200;
    }
}
