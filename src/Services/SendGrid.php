<?php

namespace Goldfinch\FormHandler\Services;

use SendGrid\Mail\Mail;
use \SendGrid as SendGridCore;
use SilverStripe\Core\Environment;
use SilverStripe\Control\HTTPRequest;

class SendGrid
{
    public static function send(HTTPRequest $request, $supplies)
    {
        $data = $request->postVars();

        if(empty($supplies['recipients']) || empty($supplies['subject'])) {
            return false;
        }

        $recipients = $supplies['recipients'];
        $subject = $supplies['subject'];

        // take first recipient as the main one, even if more provided
        if(explode(',', $recipients) > 1)
        {
            $allRecipients = explode(',', $recipients);

            if(filter_var($allRecipients[0], FILTER_VALIDATE_EMAIL))
            {
                // $from = $allRecipients[0];
                $to = $allRecipients[0];
            }
        }
        else
        {
            if(filter_var($recipients, FILTER_VALIDATE_EMAIL))
            {
                // $from = $recipients;
                $to = $recipients;
            }
        }

        // replace dynamic variables in the subject
        // if(isset($data['name']) && strpos($subject, '[name]') !== false)
        // {
        //     $subject = str_replace('[name]', $data['name'], $subject);
        // }

        // dd($from, $subject, $data, $to);

        $mail = new Mail();
        $mail->setFrom($supplies['from']);
        $mail->setSubject($subject);
        $mail->setReplyTo($data['email'], $data['name']);
        $mail->addTo($to);

        $bccEmail = Environment::getEnv('SS_BCC_EMAIL');

        if ($bccEmail) {
          $mail->addBcc(Environment::getEnv('SS_BCC_EMAIL'));
        }

        // add other recipients
        if(explode(',', $recipients) > 1)
        {
            $toEmails = [];

            $allRecipients = explode(',', $recipients);
            unset($allRecipients[0]);

            $i = 1;

            foreach($allRecipients as $one)
            {
                $one = trim($one);

                if(!filter_var($one, FILTER_VALIDATE_EMAIL))
                {
                    continue;
                }

                $toEmails[] = new To($one);

                if($i == 3)
                {
                    break;
                }

                $i++;
            }

            if(count($toEmails))
            {
                $mail->addTos($toEmails);
            }
        }

        $caller = debug_backtrace()[1]['function'];
        $callerEmail = $caller . 'Email';

        $body = 'test message';
        // $body = method_exists(__CLASS__, $callerEmail) ? $this->$callerEmail($data) : '';

        $mail->addContent(
          'text/html', $body
        );

        $sg = new SendGridCore(Environment::getEnv('SEND_GRID_KEY'));

        $response = $sg->send($mail);

        return $response;
    }
}
