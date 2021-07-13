<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;

global $USER;

$tabs = array(
    array(
        'DIV' => 'task_last_comment',
        'TAB' => Loc::getMessage('LABA_TASK_LAST_COMMENT_TAB_TITLE'),
        'TITLE' => Loc::getMessage('LABA_TASK_LAST_COMMENT_TAB_TITLE'),
    )
);

$options = array();

$options['task_last_comment'] = array(
    Loc::getMessage('LABA_TASK_LAST_COMMENT_ACTIVE_TITLE'),
    array(
        'LABA_TASK_LAST_COMMENT_ACTIVE',
        Loc::getMessage('LABA_TASK_LAST_COMMENT_ACTIVE_DESCRIPTION'),
        false,
        array('checkbox'),
    ),
);

if ($USER->IsAdmin() && check_bitrix_sessid()) {
    foreach ($options as $optionBlock) {
        __AdmSettingsSaveOptions($mid, $optionBlock);
    }

    $request = Context::getCurrent()->getRequest();
    LocalRedirect($request->getRequestUri());
}
?>
<form method="POST"
      action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?= htmlspecialcharsbx($mid); ?>&lang=<?= LANGUAGE_ID; ?>">
    <?php
    $tabControl = new CAdminTabControl('tabControl', $tabs);
    $tabControl->Begin();
    foreach ($tabs as $tab) {
        $tabControl->BeginNextTab();
        __AdmSettingsDrawList($mid, $options[$tab['DIV']]);
    }
    $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false));
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>
