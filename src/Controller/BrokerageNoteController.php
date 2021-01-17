<?php

namespace App\Controller;

use App\Helper\BrokerageNoteFactory;
use App\Service\BrokerageNoteService;

class BrokerageNoteController extends BaseController
{
    public function __construct(BrokerageNoteService $brokerageNoteService, BrokerageNoteFactory $brokerageNoteEntityFactory)
    {
        parent::__construct($brokerageNoteService, $brokerageNoteEntityFactory);
    }
}
