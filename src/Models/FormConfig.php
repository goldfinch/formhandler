<?php

namespace Goldfinch\FormHandler\Models;

use JonoM\SomeConfig\SomeConfig;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\View\TemplateGlobalProvider;
use Goldfinch\JSONEditor\Forms\JSONEditorField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class FormConfig extends DataObject implements TemplateGlobalProvider
{
    use SomeConfig;

    private static $table_name = 'FormConfig';

    private static $db = [
        'FormContactSubject' => 'Varchar',
        'FormContactRecipients' => 'Varchar',
        'FormContactBody' => 'HTMLText',
        'FormContactSuccessMessage' => 'HTMLText',
        'FormContactFailMessage' => 'HTMLText',
        'FormContactSendSenderEmail' => 'Boolean',
        'FormContactSenderSubject' => 'Varchar',
        'FormContactSenderBody' => 'HTMLText',

        'FormsGeneralSettings' => 'Text',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
          'FormContactSubject',
          'FormContactRecipients',
          'FormContactBody',
          'FormContactSuccessMessage',
          'FormContactFailMessage',
          'FormContactSendSenderEmail',
          'FormContactSenderSubject',
          'FormContactSenderBody',
          'FormsGeneralSettings',
        ]);

        $fields->addFieldsToTab('Root.Main', [

            CompositeField::create(

                CheckboxField::create('FormContact', 'Contact form'),
                Wrapper::create(

                    FieldGroup::create(

                        TextField::create('FormContactSubject', 'Subject')->setAttribute('placeholder', 'Contact enquiry')->addExtraClass('fcol-6'),
                        TextField::create('FormContactRecipients', 'Recipients')->setAttribute('placeholder', 'johndoe@johndoe.com')->addExtraClass('fcol-6'),
                        HTMLEditorField::create('FormContactBody', 'Body')->addExtraClass('fcol-12'),
                        HTMLEditorField::create('FormContactSuccessMessage', 'Form sent message')->addExtraClass('fcol-12'),
                        HTMLEditorField::create('FormContactFailMessage', 'Form failed message')->addExtraClass('fcol-12'),

                    )->setTitle('Email to admin'),

                    CheckboxField::create('FormContactSendSenderEmail','Send confirmation email to the sender'),
                    Wrapper::create(

                        FieldGroup::create(

                            TextField::create('FormContactSenderSubject', 'Subject')->setAttribute('placeholder', 'Thank you for your enquiry')->addExtraClass('fcol-6'),
                            HTMLEditorField::create('FormContactSenderBody', 'Body')->addExtraClass('fcol-12'),

                        )->setTitle('Email to sender'),

                    )->displayIf('FormContactSendSenderEmail')->isChecked()->end(),

                )->displayIf('FormContact')->isChecked()->end(),

                JSONEditorField::create('FormsGeneralSettings', 'General Settings', '{}', null, '{}')->addExtraClass('mt-4'),

            ),

        ]);

        return $fields;
    }
}
