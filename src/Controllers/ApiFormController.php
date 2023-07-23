<?php

namespace Goldfinch\FormHandler\Controllers;

use Goldfinch\FormHandler\Helpers\Rule;
use Goldfinch\FormHandler\Services\SendGrid;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;

class ApiFormController extends Controller
{
    private static $url_segment = 'api/form';

    private static $allowed_actions = [
        'contactForm',
    ];

    private static $url_handlers = [
        'POST contact' => 'contactForm',
    ];

    public function init()
    {
        parent::init();
    }

    public function contactForm(HTTPRequest $request)
    {
        $this->authorized($request);

        $this->validator([
          'token'       => ['required', Rule::recaptcha()],
          'name'        => 'required|alpha_spaces',
          'email'       => 'required|email',
          'phone'       => 'required|regex:/^[\d\s\+\-]+$/',
          'message'     => 'required',
        ], $request);

        SendGrid::send($request, [
            'recipients' => 'art@swordfox.nz',
            'subject' => 'Test API form',
        ]);

        // ..

        return json_encode($data);
    }
}
