<?php

use Bitrix\Main\Context;

if (!check_bitrix_sessid()) {
    return false;
}

/** @global CMain $APPLICATION */
global $APPLICATION;

if ($ex = $APPLICATION->GetException()) {
    CAdminMessage::ShowMessage('Installation errors:');
} else {
    CAdminMessage::ShowNote('The module has been installed successfully');
}

$context = Context::getCurrent();
?>
<form action="<?= $context->getRequest()->getRequestedPage(); ?>">
    <input type="hidden" name="lang" value="<?= $context->getLanguage(); ?>">
    <input type="submit" name="" value="Back to List">
</form>
