<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Helper;

class Lighthouse
{
    private $fileDir;
    private $lighthousePath;
    private $configFile;
    public function __construct(string $lighthousePath, string $fileDir, string $configFile)
    {
        if (! is_writeable($fileDir)) {
            throw new \RuntimeException("Cant write into $fileDir");
        }

        $this->lighthousePath = $lighthousePath;
        $this->fileDir = $fileDir;
        $this->configFile = $configFile;
    }

    public function generateDataForUrl(string $url) : string
    {
        $file = $this->fileDir . md5($url) . '.json';
        $config = ($this->configFile) ? ' --config-path=' . $this->configFile : '';
        $command = $this->lighthousePath
            . $config
            . ' --chrome-flags="--headless --window-size=2000,2000" '
            . $url
            . ' --output=json --output-path='
            . $file;
        exec($command, $result, $returnInt);

        if (!file_exists($file)) {
            throw new \RuntimeException("Could not write data for url $url.");
        }
        return $file;
    }
}
