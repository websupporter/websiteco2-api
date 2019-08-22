<?php
declare(strict_types=1);

namespace Websupporter\Co2\Api\Helper;

use Websupporter\Co2\Api\Entity\Request;
use Websupporter\Co2\Api\Repository\GreenHostRepository;

class Calculator
{
    //Source: https://api.carbonintensity.org.uk/intensity/stats/2019-06-18T12:00Z/2019-07-01T12:00Z
    const CO2_KWH = 225; // in gram

    //Source: https://aceee.org/files/proceedings/2012/data/papers/0193-000409.pdf
    const KWH_KB = 0.000005;

    const SERVER = .48;
    const TRANSPORT = .14;
    const ENDUSER = .38;

    private $greenHostRepository;
    public function __construct(GreenHostRepository $greenHostRepository)
    {
        $this->greenHostRepository = $greenHostRepository;
    }

    public function calculateGramCo2ForRequests(Request ...$requests)
    {

        $total = 0;
        foreach ($requests as $request) {
            $total += $this->calculateForRequest($request);
        }
        return $total;
    }

    private function calculateForRequest(Request $request) : float
    {

        $resourceCo2 = ($request->resourceSizeInBytes() / 1000) * self::KWH_KB * self::CO2_KWH;
        $transferCo2 = ($request->transferSizeInBytes() / 1000) * self::KWH_KB * self::CO2_KWH;

        $resourceShare = ($this->requestHostIsGreen($request)) ? self::ENDUSER : self::ENDUSER + self::SERVER;
        $total = $resourceCo2 * $resourceShare + $transferCo2 * self::TRANSPORT;
        return $total;
    }

    private function requestHostIsGreen(Request $request) : bool
    {
        return $this->greenHostRepository->forUrl($request->url());
    }
}
