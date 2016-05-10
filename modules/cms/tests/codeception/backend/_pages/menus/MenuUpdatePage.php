<?php

namespace im\cms\tests\codeception\backend\_pages\menus;

/**
 * Represents menu update page
 */
class MenuUpdatePage extends MenuCreatePage
{
    /**
     * @var string
     */
    public $route = 'cms/menu/update';

    public function clickCreateItem()
    {
        $this->actor->click('button[data-toolbar-action="create"]');
    }

    public function clickEditItem()
    {
        $this->actor->click('button[data-toolbar-action="edit"]');
    }

    public function clickDeleteItem()
    {
        $this->actor->click('button[data-toolbar-action="delete"]');
    }

    public function clickSubmitItem()
    {
        $this->actor->click('#menu-item-form button[type="submit"]');
    }

    /**
     * @param array $data
     */
    public function submitMenuItem(array $data)
    {
        $this->fillFields($data, $this->itemFields);
        $this->actor->click('#menu-item-form button[type="submit"]');
    }

    /**
     * @param array $data
     */
    public function fillMainFields(array $data)
    {
        $this->actor->click('Main');
        $this->fillFields($data, $this->itemFields);
    }

    /**
     * @param array $data
     */
    public function fillDisplayFields(array $data)
    {
        $this->actor->click('Display');
        $this->fillFields($data, $this->itemFields);
    }
}
