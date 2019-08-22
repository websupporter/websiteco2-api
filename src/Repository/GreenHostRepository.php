<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Repository;

class GreenHostRepository
{

    const EXPIRATION_TIME =  2592000; //30 days.
    private $cache;
    public function __construct(\Memcached $cache)
    {
        $this->cache = $cache;
    }

    public function forHost(string $host) : bool
    {
        $isGreen = $this->cache->get($host);
        if ($isGreen === false) {
            $isGreen = $this->request($host);
            $this->cache->set($host, $isGreen, self::EXPIRATION_TIME);
        }
        return (bool) $isGreen;
    }

    public function forUrl(string $url) : bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            return false;
        }
        return $this->forHost($host);
    }

    private function request(string $host) : int
    {
        $result = file_get_contents('http://api.thegreenwebfoundation.org/greencheck/' . $host);
        $data = json_decode($result);
        return (int) $data->green;
    }
}
