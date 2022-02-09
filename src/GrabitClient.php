<?php

namespace Omniship\Grabitmk;

use Infifni\Grabitmk\Client as Base;
use Infifni\Grabitmk\Request\EndpointInterface;
use Omniship\Grabitmk\BaseExtends\GetPdfAwb;

/**
 * @method getPdfAwb(array $params)
 */
class GrabitmkClient extends Base
{
    public function instantiate(string $class): EndpointInterface
    {
        if($class == 'GetPdfAwb') {
            return new GetPdfAwb();
        }

        return parent::instantiate($class); // TODO: Change the autogenerated stub
    }
}