<?php

namespace Goldfinch\FormHandler\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use Goldfinch\FormHandler\Helpers\Rule;
use Goldfinch\FormHandler\Traits\FormHelperTrait;
use Goldfich\Service\SendGrid;

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

        $this->validator([
          'token'       => ['required', Rule::recaptcha()],
          'name'        => 'required|alpha_spaces',
          'email'       => 'required|email',
          'phone'       => 'required|regex:/^[\d\s\+\-]+$/',
          'message'     => 'required',
        ], $request);

        SendGrid::send([
            'from' => 'from@test.nz',
            'to' => 'to@test.nz',
            'subject' => 'Test subject',
            'reply_to' => 'reply@test.nz',
            'reply_to_name' => 'Reply name',
            'bcc' => 'bcc@test.nz',
            'body' => '<p><strong>Big</strong> font</p>',
        ]);

        // ..

        return json_encode(true);
    }
}
