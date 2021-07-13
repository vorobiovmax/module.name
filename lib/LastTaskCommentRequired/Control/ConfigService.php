<?php


namespace module\name\LastTaskCommentRequired\Control;


use Bitrix\Main\Config\Option;
use module\name\LastTaskCommentRequired\Entity\Config;

class ConfigService
{
    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function getConfig(): Config {
        $isActive = Option::get('module.name', 'LABA_TASK_LAST_COMMENT_ACTIVE', 'N') === 'Y';

        return new Config($isActive);
    }
}