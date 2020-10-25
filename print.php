<?php

/*
* $Id: print.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/

require dirname(__DIR__, 2) . '/mainfile.php';
require __DIR__ . '/header.php';
$myts = MyTextSanitizer::getInstance();
$tp = isset($_GET['tp']) ? (int)$_GET['tp'] : 0;
if (empty($tp) || empty($sc) || empty($c)) {
    redirect_header('index.php');
}
include 'include/functions.php';

function PrintPage($sc, $c, $tp)
{
    global $xoopsConfig, $xoopsModule, $xoopsDB, $myts, $tp;

    $result = $xoopsDB->queryF('SELECT question, answer, summary,  uid, datesub FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = '$tp'");

    [$question, $answer, $summary, $uid, $datesub] = $xoopsDB->fetchRow($result);

    if (0 == $uid) {
        $username = _MD_GUEST;
    } else {
        $thisUser = new XoopsUser($uid);

        $thisUser->getVar('uname');

        $thisUser->getVar('uid');

        $username = $thisUser->uname();
    }

    $datetime = viettime(formatTimestamp($datesub));

    $answer = $myts->displayTarea($answer, 0, 1, 1);

    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

    echo '<html><head>';

    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '">';

    echo '<title>' . $xoopsConfig['sitename'] . '</title>';

    echo '<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '">';

    echo '<meta name="COPYRIGHT" content="Copyright (c) 2001 by ' . $xoopsConfig['sitename'] . '">';

    echo '<meta name="DESCRIPTION" content="' . $xoopsConfig['slogan'] . '">';

    echo '<meta name="GENERATOR" content="' . XOOPS_VERSION . '">';

    echo '<body bgcolor="#ffffff" text="#000000" onload="window.print()">';

    echo '<table border="0"><tr><td align="center">';

    echo '<table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>';

    echo '<table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td align="center">';

    echo '<img src="' . XOOPS_URL . '/images/logo.gif" border="0"><br><br>';

    echo "<h3>$question</h3>";

    echo '<small>' . _MD_MAINPSUBMITTED . '&nbsp;' . $datetime . ' | ' . _MD_MAINPAUTHOR . '&nbsp;<b>' . $username . '</b><br><br></td></tr>';

    echo '<tr valign="top" style="font:12px;"><td><br>';

    echo $answer;

    echo '</td></tr></table></td></tr></table><br><br><small>';

    echo printf(_NW_THISCOMESFROM, $xoopsConfig['sitename']);

    echo "<br><a href='" . XOOPS_URL . "'>" . $xoopsConfig['sitename'] . '</a><br><br>';

    echo _NW_URLFORSTORY . '<br>';

    $url = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/index.php?op=view&sc=$sc" . "&c=$c" . "&t=$tp";

    echo "<a href='$url'>$url</a>";

    echo '</small></td></tr></table></body></html>';
}

PrintPage($sc, $c, $tp);
