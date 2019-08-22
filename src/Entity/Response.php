<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Entity;

interface Response
{

    public function hasError() : bool;

    public function data() : array;

    public function statusCode() : int;
}
