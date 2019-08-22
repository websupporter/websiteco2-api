<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Api;

use Websupporter\Co2\Api\Entity\ErrorResponse;
use Websupporter\Co2\Api\Entity\Response;
use Websupporter\Co2\Api\Entity\SuccessResponse;
use Websupporter\Co2\Api\Repository\RequestCollectionRepository;

class Controller
{

    private $repository;
    public function __construct(RequestCollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listen()
    {

        $requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
        if (! $this->isValidEndpoint($requestUri)) {
            $errorResponse = new ErrorResponse(['message' => 'Not a valid endpoint.'], 400);
            $this->respond($errorResponse);
            return;
        }
        $url = $this->getUrl($requestUri);
        if (! $url) {
            $errorResponse = new ErrorResponse(['message' => 'No valid url found.'], 422);
            $this->respond($errorResponse);
            return;
        }

        try {
            $collection = $this->repository->forUrl($url);
        } catch (\RuntimeException $error) {
            echo $error->getMessage();
            die();
            $errorResponse = new ErrorResponse(['message' => 'Internal error.'], 500);
            $this->respond($errorResponse);
            return;
        }

        $response = new SuccessResponse(
            [
            'url' => $collection->forUrl(),
            'co2' => $collection->totalGramCo2(),
            'transferKB' => $collection->transferBytes() / 1000,
            'resourceKB' => $collection->resourceBytes() / 1000,
            ]
        );
        $this->respond($response);
    }

    private function isValidEndpoint($requestUri)
    {
        $request = explode('v1/website/', $requestUri);
        return count($request) === 2;
    }

    private function getUrl($requestUri)
    {
        $request = explode('v1/website/', $requestUri);
        if (! isset($request[1])) {
            return '';
        }

        $isUrl = filter_var($request[1], FILTER_VALIDATE_URL);
        if (! $isUrl) {
            return '';
        }
        return $request[1];
    }

    private function respond(Response $response)
    {
        http_response_code($response->statusCode());
        header('Content-Type: application/json');
        $data = $response->data();
        $data['error'] = 0;
        if ($response->hasError()) {
            $data['error'] = 1;
        }

        echo json_encode($data);
    }
}
