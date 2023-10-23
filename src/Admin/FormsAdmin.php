<?php

namespace Goldfinch\FormHandler\Admin;

use SilverStripe\Admin\ModelAdmin;
use JonoM\SomeConfig\SomeConfigAdmin;
use Goldfinch\FormHandler\Models\FormConfig;
use Goldfinch\FormHandler\Models\FormEnquiry;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldImportButton;

class FormsAdmin extends ModelAdmin
{
    use SomeConfigAdmin;

    private static $url_segment = 'form-enquiries';

    private static $menu_title = 'Form enquiries';

    private static $managed_models = [
        FormEnquiry::class => [
            'title' => 'Enquiries',
        ],
        FormConfig::class => [
            'title'=> 'Settings',
        ],
    ];

    private static $menu_priority = 0;

    private static $menu_icon_class = 'bi-envelope-fill';

    public $showImportForm = true;

    public $showSearchForm = true;

    private static $page_length = 30;

    public function getList()
    {
        $list =  parent::getList();

        // ..

        return $list;
    }

    public function getSearchContext()
    {
        $context = parent::getSearchContext();

        // ..

        return $context;
    }

    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = parent::getGridFieldConfig();

        $config->removeComponentsByType(GridFieldImportButton::class);

        return $config;
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        // ..

        return $form;
    }

    public function getExportFields()
    {
        return [
            // 'Name' => 'Name',
            // 'Category.Title' => 'Category'
        ];
    }
}
