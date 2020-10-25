<?php

/*
* $Id: editcp.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/

require __DIR__ . '/header.php';
$myts = MyTextSanitizer::getInstance();

//Cau hinh Smarty Tempalte, tam thoi xai vay di >_<
$html = 0;
$smiley = 1;
$xcode = 1;
require XOOPS_ROOT_PATH . '/header.php';

//require XOOPS_ROOT_PATH."/modules/wffaq/include/functions.php";
include 'include/functions.php';
global $xoopsUser, $xoopsDB, $xoopsConfig, $wfsConfig;

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}
foreach ($_GET as $k => $v) {
    ${$k} = $v;
}
if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}
if (isset($_POST['sc'])) {
    $sc = $_POST['sc'];
}
if (isset($_POST['c'])) {
    $c = $_POST['c'];
}
if (isset($_POST['tp'])) {
    $tp = $_POST['tp'];
}

$PHP_SELF = $_SERVER['PHP_SELF'];
$uid = $xoopsUser->uid();
$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c' AND uid = '$uid' AND supID ='$sc'");
$isUser = $xoopsDB->getRowsNum($result);
if (0 == $isUser) {
    redirect_header('index.php', 1, _NOPERM);

    exit();
}

switch ($op) {
    case 'conf':
        echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'><tr><td></td></tr></table>";
        echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
        echo "<div class='confirmMsg'>";
        echo '<h4>';
        echo _MD_EDITCP_CONFIRM . '</font></h4>' . $name . '<br><br>';
        echo '<table><tr><td>';
        echo '</td><td>';
        echo "<a href='editcp.php?op=del&sc=" . $sc . '&c=' . $c . '&tp=' . $tp . "'>" . _MD_EDITCP_YES . '</a> ';
        echo " | <a href='editcp.php?sc=" . $sc . '&c=' . $c . '&tp=' . $tp . "'>" . _MD_EDITCP_NO . '</a> ';
        echo '</td></tr></table>';
        echo '</div><br><br>';
        echo '</td></tr></table>';

        break;
    case 'del':
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID='$tp'";
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        if (!$result) {
            redirect_header('editcp.php?sc=' . $sc . '&c=' . $c . "&tp=$tp", 2, _MD_EDITCP_DELFAIL);
        }
        $sql = 'UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total - 1 WHERE catID = '$c'";
        $GLOBALS['xoopsDB']->queryF($sql);
        redirect_header('editcp.php?sc=' . $sc . '&c=' . $c . "&tp=$tp", 2, _MD_EDITCP_DELOK);
        break;
    case 'order':
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        $TpOr = $_POST['TopicOrder'];
        $result = $xoopsDB->query('SELECT topicID FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$c' AND submit ='1' ORDER BY TopicOrder");
        $i = 0;
        while (false !== ($topic_ID = $xoopsDB->fetchArray($result))) {
            $order = (int)$TpOr[$i];

            if ($order < 0) {
                $order = 0;
            }

            $tpID = $topic_ID['topicID'];

            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqtopics') . " SET TopicOrder = '$order' WHERE topicID ='$tpID'");

            $i++;
        }
        redirect_header('javascript:history.go(-1)', 2, _MD_EDITCP_REOROK);

        break;
    case 'default':
    default:
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        //Smarty template
        $GLOBALS['xoopsOption']['template_main'] = 'ecotut_editcp.html';
        $html = 0;
        $smiley = 1;
        $xcode = 1;
        //Lay table of contents cua cat nay Get infor for table
        $havecat = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c' AND mod>0");
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$c' AND submit ='1' ORDER BY TopicOrder");
        //So cats va tps How many topics are there?
        $totalcat = $xoopsDB->getRowsNum($havecat);
        $totaltopics = $xoopsDB->getRowsNum($result);
        //Neu ko co cat nao No cat and topic
        if (0 == $totalcat) {
            redirect_header('javascript:history.go(-1)', 1, _MD_MAINNOSELECTCAT);

            exit();
        }
        //Neu ko co tp nao
        if (0 == $totaltopics) {
            redirect_header("index.php?op=viewtp&sc=$sc" . "&c=$c", 1, _MD_MAINNOTOPICS);

            exit();
        }
        // Neu Good thi -> OK, lay du lieu
        $xoopsTpl->assign(['catID' => $c, 'supID' => $sc, 'ReOrder' => _MD_EDITCP_REORDERBUT]);
        $count = 0;
        while (false !== ($topic_data = $xoopsDB->fetchArray($result))) {
            if (0 == $count % 2) {
                $class = 'odd';
            } else {
                $class = 'even';
            }

            $topicID = $topic_data['topicID'];

            $topicOrder = $topic_data['TopicOrder'];

            $topicQuestion = "<a href='index.php?op=view&c=$c" . "&sc=$sc" . "&t=$topicID' target='_blank' title='" . _MD_EDITCP_VIEWTOPIC . "'> " . $topic_data['question'] . ' </a>';

            $topicAction = "<a href='editfaq.php?c=$c" . "&sc=$sc" . "&tp=$topicID'>" . _MD_EDITCP_EDIT . "</a> | <a href='editcp.php?op=conf&c=$c" . "&sc=$sc" . "&tp=$topicID" . '&name=' . $topic_data['question'] . "'>" . MD_EDITCP_DEL . '</a>';

            $xoopsTpl->append('faq', ['class' => $class, 'name' => $topicQuestion, 'action' => $topicAction, 'order' => $topicOrder]);

            $xoopsTpl->assign(['name' => _MD_FAQQUEST, 'order' => _MD_EDITCP_ORDER, 'action' => _MD_EDITCP_ACTION]);

            $count++;
        }

        $xoopsTpl->assign('editcp', editorCP($sc, $c));
}

require XOOPS_ROOT_PATH . '/footer.php';
