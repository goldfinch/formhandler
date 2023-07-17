<?php

namespace Goldfinch\Formhandler\Traits;

use Goldfinch\Formhandler\Helpers\Validator;
use SilverStripe\Control\HTTPRequest;

trait FormHelperTrait
{
    protected function authorized(HTTPRequest $request)
    {
        if(!$request->isAjax() || !$request->isPOST())
        {
            return $this->httpError(403, 'This action is unauthorized');
        }
        else if($request->getHeader('X-CSRF-TOKEN') != SecurityToken::getSecurityID())
        {
            return $this->httpError(401, 'Unauthorized');
        }
    }

    protected function validator($schema, $request)
    {
        Validator::validate($schema, $request);
    }
}
