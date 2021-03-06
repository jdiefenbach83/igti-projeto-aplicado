<?php

namespace App\Controller;

use App\Helper\CompanyDTOFactory;
use App\Service\CompanyService;

class CompanyController extends BaseController
{
    public function __construct(CompanyService $companyService, CompanyDTOFactory $companyDTOFactory)
    {
        parent::__construct($companyService, $companyDTOFactory);
    }
}
