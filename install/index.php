<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use module\name\LastTaskCommentRequired\Boundary\TaskCloseFacade;

class MODULE_NAME extends CModule
{
    const MODULE_ID = 'module.name';
    var $MODULE_ID = self::MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME.MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_NAME.MODULE_DESC');

        $this->PARTNER_NAME = Loc::getMessage('MODULE_NAME.PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('MODULE_NAME.PARTNER_URI');
    }

    function DoInstall()
    {
        ModuleManager::registerModule(self::MODULE_ID);
        Option::set(self::MODULE_ID, 'VERSION_DB', $this->versionToInt());

        $this->InstallDB();
    }

    function DoUninstall()
    {
        $this->UnInstallDB();

        Option::delete(self::MODULE_ID, array('name' => 'VERSION_DB'));
        ModuleManager::unRegisterModule(self::MODULE_ID);
    }

    function InstallDB() {
        $this->installEventHandlers();
    }

    function UnInstallDB() {
        $this->unInstallEventHandlers();
    }

    function installEventHandlers() {
        $em = EventManager::getInstance();

        $em->registerEventHandlerCompatible(
            'tasks',
            'OnBeforeTaskUpdate',
            self::MODULE_ID,
            TaskCloseFacade::class,
            'onBeforeTaskUpdateHandler'
        );
    }

    function unInstallEventHandlers() {
        $em = EventManager::getInstance();

        $em->unRegisterEventHandler(
            'tasks',
            'OnBeforeTaskUpdate',
            self::MODULE_ID,
            TaskCloseFacade::class,
            'onBeforeTaskUpdateHandler'
        );
    }

    private function versionToInt()
    {
        return intval(preg_replace('/[^0-9]+/i', '', $this->MODULE_VERSION_DATE));
    }
}