<?php

namespace App\Controller;

use App\Helper\BrokerDTOFactory;
use App\Service\BrokerService;

class BrokerController extends BaseController
{
    public function __construct(BrokerService $brokerService, BrokerDTOFactory $brokerDTOFactory)
    {
        parent::__construct($brokerService, $brokerDTOFactory);
    }
}
