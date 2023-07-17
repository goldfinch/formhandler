<?php

namespace Goldfinch\Formhandler\Helpers;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\RequestHandler;
use Rakit\Validation\Validator as IlluminateValidator;

class Validator
{
    public static function make($name, $rule, $options)
    {
        return $name->$rule($options);
    }

    public static function validate($fields, HTTPRequest $request)
    {
        $validator = new IlluminateValidator;

        $validation = $validator->make($request->postVars(), $fields);

        $validation->validate();

        if($validation->fails())
        {
            $errors = $validation->errors();

            $e = new RequestHandler;

            return $e->httpError(403, (string) json_encode(['errors' => $errors->firstOfAll()]));
        }
    }
}
