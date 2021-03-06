<?php

/*
* $Id: faqform.inc.php,v 1.1 2006/03/27 08:05:31 mikhail Exp $
* Licence: GNU
*/

require XOOPS_ROOT_PATH . '/class/xoopstree.php';
require XOOPS_ROOT_PATH . '/class/xoopslists.php';
require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

//$mytree = new XoopsTree($xoopsDB->prefix("faqcategories"),"catid","0");

$sform = new XoopsThemeForm(_MD_FAQSUB_SMNAME, 'storyform', xoops_getenv('PHP_SELF'));

//ob_start();
$sform->addElement(new XoopsFormHidden('catid', $c));
$sform->addElement(new XoopsFormHidden('sc', $sc));
if (isset($tp)) {
    $sform->addElement(new XoopsFormHidden('tp', $tp));
}
//$mytree->makeMySelBox("name", "catid");
//$sform->addElement(new XoopsFormLabel(_MD_CREATIN, $c));
//ob_end_clean();

$sform->addElement(new XoopsFormText(_MD_FAQQUEST, 'question', 50, 80, $question), true);
$sform->addElement(new XoopsFormDhtmlTextArea(_MD_FAQANSW, 'answer', $answer, 15, 60), true);
$sform->addElement(new XoopsFormDhtmlTextArea(_MD_FAQSUM, 'summary', $summary, 7, 60));
$option_tray = new XoopsFormElementTray(_OPTIONS, '<br>');
/*
if ($xoopsUser) {
    if ($wfsConfig['anonpost'] == 1) {
        $noname_checkbox = new XoopsFormCheckBox('', 'noname', $noname);
        $noname_checkbox->addOption(1, _POSTANON);
        $option_tray->addElement($noname_checkbox);
    }
    $notify_checkbox = new XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, 'Notify on Publish');
    $option_tray->addElement($notify_checkbox);

    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);
        $nohtml_checkbox->addOption(1, _DISABLEHTML);
        $option_tray->addElement($nohtml_checkbox);
    }
}

$smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);
$sform->addElement($option_tray);*/
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'post', _MD_SUBMIT, 'submit'));
$sform->addElement($button_tray);
$sform->display();
