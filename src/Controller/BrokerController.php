<?php

namespace App\Controller;

use App\Helper\BrokerFactory;
use App\Service\BrokerService;

class BrokerController extends BaseController
{
    public function __construct(BrokerService $brokerService, BrokerFactory $brokerEntityFactory)
    {
        parent::__construct($brokerService, $brokerEntityFactory);
    }
}
