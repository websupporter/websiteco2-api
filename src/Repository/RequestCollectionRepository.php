<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Repository;

use Websupporter\Co2\Api\Entity\RequestCollection;
use Websupporter\Co2\Api\ProcessUrl;

class RequestCollectionRepository
{

    const EXPIRATION_TIME = 60*5; // 5 minutes

    private $cache;
    private $processUrl;

    public function __construct(\Memcached $cache, ProcessUrl $processUrl)
    {
        $this->cache = $cache;
        $this->processUrl = $processUrl;
    }

    public function forUrl(string $url) : RequestCollection
    {
        $collection = $this->cache->get($url);
        if ($collection === false) {
            $collection = $this->processUrl->process($url);
            $this->cache->set($url, $collection, self::EXPIRATION_TIME);
        }
        return $collection;
    }
}
