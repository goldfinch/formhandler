<?php

namespace Goldfinch\FormHandler\Controllers;

use Goldfinch\Service\SendGrid;
use Goldfinch\Illuminate\Validator;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use Goldfinch\FormHandler\Helpers\Rule;
use Goldfinch\Service\Rules\GoogleRecaptcha;
use Goldfinch\FormHandler\Traits\FormHelperTrait;

class ApiFormController extends Controller
{
    use FormHelperTrait;

    private static $url_segment = 'api/form';

    private static $url_handlers = [
        'POST contact' => 'contactForm',
    ];

    private static $allowed_actions = [
        'contactForm',
    ];

    public function init()
    {
        parent::init();
    }

    public function contactForm(HTTPRequest $request)
    {
        $this->authorized($request);

        $data = $request->postVars();

        Validator::validate($data, [
          'token'       => ['required', new GoogleRecaptcha],
          'name'        => 'required|alpha',
          'phone'       => 'required|regex:/^[\d\s\+\-]+$/',
          'email'       => 'required|email',
          'how'         => 'required',
          'message'     => 'required',
        ]);

        SendGrid::send([
          'name' => 'John Doe',
          'from' => 'contact@johndoe.com',
          'to' => 'none@persone.com',
          'subject' => 'John Doe enquiry',
          'reply_to' => 'contact@johndoe.com',
          'bcc' => 'bcc@johndoe.com',
          'body' => '<strong>John</strong>Doe',
        ]);

        return json_encode(true);
    }
}
