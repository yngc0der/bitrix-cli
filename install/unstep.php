<?php

use Bitrix\Main\Context;

if (!check_bitrix_sessid()) {
    return false;
}

$context = Context::getCurrent();
?>
<form action="<?= $context->getRequest()->getRequestedPage(); ?>">
    <?= bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= $context->getLanguage(); ?>">
    <input type="hidden" name="id" value="<?= $context->getRequest()->get('id'); ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?php
    CAdminMessage::ShowMessage('Warning!<br>The module will be removed from the system');
    ?>
    <p>You can save the module data in database:</p>
    <p>
        <input type="checkbox" name="savedata" value="Y" id="savedata" checked>
        <label for="savedata">Save tables</label>
    </p>
    <input type="submit" name="" value="Delete module">
</form>
