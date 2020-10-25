<?php

/*
* $Id: postfaq.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/

include '../../mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';
include 'include/functions.php';
global $xoopsUser, $xoopsUser, $xoopsConfig;

if (!is_object($xoopsUser)) {
    redirect_header('index.php', 1, _NOPERM);

    exit();
}
global $wfsConfig, $xoopsUser, $xoopsDB;
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
        global $xoopsUser, $xoopsConfig;

        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->uid();
        } else {
            $uid = 0;
        }
        if ((int)$_POST['catid']) {
        } else {
            echo (int)$_POST['catid'];
        }

        $cat = $myts->addSlashes($_POST['catid']);
        $question = $myts->addSlashes($_POST['question']);
        $answer = $myts->addSlashes($_POST['answer']);
        $summary = $myts->addSlashes($_POST['summary']);
        $uid = $xoopsUser->uid();
        $datesub = time();
        $submit = 1;

        $result = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('ecotu_faqtopics') . " (catID, question, answer, summary, uid, datesub, submit) VALUES ('$cat', '$question', '$answer', '$summary', '$uid', '$datesub', '$submit')");

        if ($result) {
            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total + 1 WHERE catID = '$cat'");

        /*
         I think that We need notify to admin about that post? Comming soon >_<
           */
        } else {
            redirect_header('javascript:history.go(-1)', 2, _MD_ERRORSAVINGDB);
        }

        redirect_header('javascript:history.go(-1)', 2, _MD_SUBMITFAQUSER);
        exit();
        break;
    case 'form':
    default:

        require XOOPS_ROOT_PATH . '/header.php';
        // Check user permission
        if (!is_object($xoopsUser) || !isset($c) || !isset($sc)) {
            redirect_header('index.php', 1, _NOPERM);

            exit();
        }
            $uid = $xoopsUser->uid();
            $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c' AND uid = '$uid' AND supID ='$sc'");
            $isUser = $xoopsDB->getRowsNum($result);
            if (0 == $isUser) {
                redirect_header('index.php', 1, _NOPERM);

                exit();
            }

        // Some seem not need
        $question = '';
        $answer = '';
        $summary = '';
        $catid = 1;
        $noname = 0;
        $nohtml = 0;
        $nosmiley = 0;
        $notifypub = 1;

        $supcat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$sc'");
        $cat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c'");
        [$supcatname] = $GLOBALS['xoopsDB']->fetchRow($supcat);
        [$catname] = $GLOBALS['xoopsDB']->fetchRow($cat);
        echo '<h3>' . _MD_CAPTION . '</h3>';
        echo "<b>+ <a href='index.php'>" . _MD_MAINSUPCATHEAD . "</a> >> <a href=\"index.php?op=viewcat&sc=$sc\">$supcatname</a> >> <a href=\"index.php?op=viewtp&sc=$sc" . "&c=$c\">$catname</a></b>";
        $noTpl = 1;
        echo editorCP($sc, $c, $noTpl);
        require __DIR__ . '/include/faqform.inc.php';
        require XOOPS_ROOT_PATH . '/footer.php';

        break;
}
