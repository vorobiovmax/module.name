<?php


namespace module\name\LastTaskCommentRequired\Control;


class NotificationService
{
    public function showError(string $msg) {
        global $APPLICATION;

        $APPLICATION->ThrowException($msg);
    }
}