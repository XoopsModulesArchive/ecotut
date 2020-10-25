<?php

/*
* $Id: admin_header.php,v 1.1 2006/03/27 08:03:51 mikhail Exp $
* Licence: GNU
*/

include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';
require XOOPS_ROOT_PATH . '/modules/ecoTut/include/functions.php';
require XOOPS_ROOT_PATH . '/class/xoopstree.php';
require XOOPS_ROOT_PATH . '/class/xoopslists.php';
require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('ecoTut');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists('../language/' . $xoopsConfig['language'] . '/admin.php')) {
    include '../language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include '../language/english/admin.php';
}
if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
    include '../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include '../language/english/main.php';
}
