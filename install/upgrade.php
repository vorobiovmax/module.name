<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

class _ModuleNameUpgradeManager
{
    public static function getCheckPoint()
    {
        return intval(Option::get('module.name', 'VERSION_DB', null));
    }

    public static function setCheckPoint($version)
    {
        Option::set('module.name', 'VERSION_DB', $version);
    }

    public static function upgrade()
    {
        $dbVersion = self::getCheckPoint();
        $db = Application::getConnection();

        try {

            /*if ($dbVersion < 20171029000000) {
                $db->startTransaction();

                // deployment migration script here ...

                $db->commitTransaction();
                self::setCheckPoint(20171029000000);
            }*/

        } catch (Exception $e) {
            $db->rollbackTransaction();
            throw $e;
        }
    }
}