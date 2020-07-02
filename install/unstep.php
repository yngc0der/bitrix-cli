<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

use Bitrix\Main\Localization\Loc;
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
    <input type="submit" name="" value="<?= Loc::getMessage('MOD_UNINST_DEL'); ?>">
</form>