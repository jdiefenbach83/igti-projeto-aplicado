<?php

namespace App\Controller;

use App\Helper\AssetDTOFactory;
use App\Service\AssetService;

class AssetController extends BaseController
{
    public function __construct(AssetService $assetService, AssetDTOFactory $assetDTOFactory)
    {
        parent::__construct($assetService, $assetDTOFactory);
    }
}
