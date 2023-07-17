<?php

namespace Goldfinch\Formhandler\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\ORM\ValidationResult;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\Forms\HTMLEditor\HtmlEditorField;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'FormContactSubject' => 'Varchar',
        'FormContactRecipients' => 'Varchar',
        'FormContactBody' => 'HTMLText',
        'FormContactSuccessMessage' => 'HTMLText',
        'FormContactFailMessage' => 'HTMLText',
        'FormContactSendSenderEmail' => 'Boolean',
        'FormContactSenderSubject' => 'Varchar',
        'FormContactSenderBody' => 'HTMLText',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Forms', [

            CompositeField::create(

                CheckboxField::create('FormContact', 'Contact form'),
                Wrapper::create(

                    FieldGroup::create(

                        TextField::create('FormContactSubject', 'Subject')->setAttribute('placeholder', 'Contact enquiry')->addExtraClass('fcol-6'),
                        TextField::create('FormContactRecipients', 'Recipients')->setAttribute('placeholder', 'johndoe@johndoe.com')->addExtraClass('fcol-6'),
                        HtmlEditorField::create('FormContactBody', 'Body')->addExtraClass('fcol-12'),
                        HtmlEditorField::create('FormContactSuccessMessage', 'Form sent message')->addExtraClass('fcol-12'),
                        HtmlEditorField::create('FormContactFailMessage', 'Form failed message')->addExtraClass('fcol-12'),

                    )->setTitle('Email to admin'),

                    CheckboxField::create('FormContactSendSenderEmail','Send confirmation email to the sender'),
                    Wrapper::create(

                        FieldGroup::create(

                            TextField::create('FormContactSenderSubject', 'Subject')->setAttribute('placeholder', 'Thank you for your enquiry')->addExtraClass('fcol-6'),
                            HtmlEditorField::create('FormContactSenderBody', 'Body')->addExtraClass('fcol-12'),

                        )->setTitle('Email to sender'),

                    )->displayIf('FormContactSendSenderEmail')->isChecked()->end(),

                )->displayIf('FormContact')->isChecked()->end(),

            ),

        ]);
    }

    public function validate(ValidationResult $validationResult)
    {
        // $validationResult->addError('Error message');
    }
}
