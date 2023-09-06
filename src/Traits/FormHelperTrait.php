<?php

namespace Goldfinch\FormHandler\Traits;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\SecurityToken;

trait FormHelperTrait
{
    protected function authorized(HTTPRequest $request)
    {
        if(!$request->isPOST())
        {
            return $this->httpError(403, 'This action is unauthorized');
        }
        else if($request->getHeader('X-CSRF-TOKEN') != SecurityToken::getSecurityID())
        {
            return $this->httpError(401, 'Unauthorized');
        }
    }
}
