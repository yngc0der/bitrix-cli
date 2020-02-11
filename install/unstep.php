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
    <input type="hidden" name="id" value="<?= $context->getRequest()->get('mod'); ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="submit" name="" value="<?= Loc::getMessage('MOD_UNINST_DEL'); ?>">
</form>