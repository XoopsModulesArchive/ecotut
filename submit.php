<?php

/*
* $Id: submit.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/

require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';
global $xoopsUser, $xoopsDB, $xoopsConfig;

//Kiem tra tu cach user
if (!is_object($xoopsUser)) {
    redirect_header('index.php', 1, _NOPERM);

    exit();
}

global $wfsConfig;
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

foreach ($_GET as $k => $v) {
    ${$k} = $v;
}

$op = 'form';

if (isset($_POST['post'])) {
    $op = 'post';
} elseif (isset($_POST['edit'])) {
    $op = 'edit';
}

switch ($op) {
    case 'post':

        $myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
        //Global $xoopsUser, $xoopsConfig;

        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->uid();
        } else {
            $uid = 0;
        }
        if ((int)$_POST['supID']) {
        } else {
            echo (int)$_POST['supID'];
        }

        $supid = $myts->addSlashes($_POST['supID']);
        $name = $myts->addSlashes($_POST['name']);
        $description = $myts->addSlashes($_POST['desc']);
        $uid = $xoopsUser->uid();
        $mod = 1;

        $result = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('ecotu_faqcategories') . " (catID, name, description, uid, mod, supID) VALUES ('', '$name', '$description', '$uid', '0', '$supID')");

        if ($result) {
            // Some code here?
        } else {
            redirect_header('submit.php', 2, _MD_ERRORSAVINGDB);
        }

        redirect_header('index.php', 2, _MD_SUBMITUSER);
        exit();
        break;
    case 'form':
    default:
        require XOOPS_ROOT_PATH . '/header.php';
        $result = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat'));
        if (0 == $xoopsDB->getRowsNum($result)) {
            redirect_header('index.php', 2, _MD_SUBMINOSUP);
        }
        $name = '';
        $desc = '';
        $noname = 0;
        $nohtml = 1;
        $nosmiley = 0;
        $notifypub = 1;
        require __DIR__ . '/include/storyform.inc.php';
        require XOOPS_ROOT_PATH . '/footer.php';
        break;
}
