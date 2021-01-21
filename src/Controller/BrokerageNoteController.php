<?php

namespace App\Controller;

use App\Helper\BrokerageNoteDTOFactory;
use App\Service\BrokerageNoteService;

class BrokerageNoteController extends BaseController
{
    public function __construct(BrokerageNoteService $brokerageNoteService, BrokerageNoteDTOFactory $brokerageNoteDTOFactory)
    {
        parent::__construct($brokerageNoteService, $brokerageNoteDTOFactory);
    }
}
