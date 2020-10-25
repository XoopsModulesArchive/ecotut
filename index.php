<?php

/*
* $Id: index.php,v 1.1 2006/03/27 08:05:25 mikhail Exp $
* Licence: GNU
*/
require __DIR__ . '/header.php';
$myts = MyTextSanitizer::getInstance();
//Cau hinh Smarty Tempalte, tam thoi xai vay di >_< ST config
$html = 0;
$smiley = 1;
$xcode = 1;
require XOOPS_ROOT_PATH . '/header.php';

//require XOOPS_ROOT_PATH."/modules/wffaq/include/functions.php";
include 'include/functions.php';
global $xoopsUser, $xoopsDB, $xoopsConfig, $wfsConfig;
$op = '';
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
$PHP_SELF = $_SERVER['PHP_SELF'];

switch ($op) {
    //Hien thi cac mot FAQ View one FAQ
    case 'view':
        //Xai smarty template
        $GLOBALS['xoopsOption']['template_main'] = 'ecotut_answer.html';
        global $xoopsUser, $xoopsDB;
        //Cap nhat counter Update counter
        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqtopics') . " SET counter=counter+1 WHERE topicID = '$t' ");
        $result = $xoopsDB->queryF('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = '$t' AND submit = '1' AND catID = '$c' ORDER BY TopicOrder");
        if (0 == $xoopsDB->getRowsNum($result)) {
            redirect_header('javascript:history.go(-1)', 1, _MD_NOTOPICLIKETHIS);
        }
        [$topicID, $catID, $question, $answer, $summary, $uid, $submit, $datesub, $counter] = $xoopsDB->fetchRow($result);

        $catname = $xoopsDB->query('SELECT name, uid FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$catID' AND mod>0");
        [$cat, $catUserID] = $xoopsDB->fetchRow($catname);
        $faqsa = [];

        if (0 == $catUserID) {
            $faqsa['editor'] = _MD_GUEST;
        } else {
            $thisUser = new XoopsUser($catUserID);

            $thisUser->getVar('uname');

            $thisUser->getVar('uid');

            $faqsa['editor'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $thisUser->uid() . "'>" . $thisUser->uname() . '</a>'; //$thisUser->getVar("uname");
        }

        $answer = $myts->displayTarea($answer, $html, $smiley, $xcode);
        $faqsa['answer'] = $answer;
        $faqsa['question'] = $question;
        $faqsa['datesub'] = viettime(formatTimestamp($datesub, 'D, d-M-Y, H:i'));
        $faqsa['counter'] = $counter;
        $faqsa['printdesc'] = _MD_PRINTTOPIC;
        $faqsa['printlink'] = "print.php?tp=$t" . "&sc=$sc" . "&c=$c";

        $c = $catID;
        //Lay table of contents cua cate nay
        $sql = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = $c AND mod>0");
        $cat_info = $xoopsDB->fetchArray($sql);
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$c' AND submit ='1' ORDER BY TopicOrder");
        $supname = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID='$sc'");
        [$supname] = $GLOBALS['xoopsDB']->fetchRow($supname);
        //OK, lay du lieu
        $category['name'] = $cat_info['name'];
        $category['description'] = $myts->displayTarea($cat_info['description'], $html, $smiley, $xcode);

        while (false !== ($cat_data = $xoopsDB->fetchArray($result))) {
            $topics['summary'] = $myts->displayTarea($cat_data['summary'], $html, $smiley, $xcode);

            $topics['question'] = $cat_data['question'];

            $topics['topicID'] = $cat_data['topicID'];

            $xoopsTpl->append('topics', ['id' => $topics['topicID'], 'question' => $topics['question'], 'summary' => $topics['summary']]);
        }
        $faqsa['link'] = "<a href='./index.php'>" . _MD_MAINSUPCATHEAD . "</a> >> <a href='index.php?op=viewcat&sc=$sc'>$supname</a> >> <a href='index.php?op=viewtp&c=$catID" . "&sc=$sc'>" . $category['name'] . '</a>';
        //He he, nap vao template
        $xoopsTpl->assign('category', $category);
        $xoopsTpl->assign('faqpage', $faqsa);
        $xoopsTpl->assign(['lang_faq' => _MD_FAQ, 'lang_publish' => _MD_PUBLISH, 'lang_posted' => _MD_POSTED, 'lang_read' => _MD_READ, 'lang_times' => _MD_TIMES, 'lang_articleheading' => $question, 'lang_tbofcontents' => _MD_TBOFCONTENTS, 'catID' => $c, 'supID' => $sc]);
        // And, if you are category editer? ->View editer CP
        //$noTpl = 0;
        editorCP($sc, $c);
        break;
    case 'viewtp':
        //Hien thi cac questions cua category Now you are in one category -> View table of contents
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        $GLOBALS['xoopsOption']['template_main'] = 'ecotut_topic.html';
        // And, if you are category editer? ->View editer CP
        $access = editorCP($sc, $c);
        //Lay table of contents cua cat nay Get infor for table
        $sql = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c' AND mod>0");
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$c' AND submit ='1' ORDER BY TopicOrder");
        //So cats va tps How many topics are there?
        $totalcat = $xoopsDB->getRowsNum($sql);
        $totaltopics = $xoopsDB->getRowsNum($result);
        //Neu ko co cat nao No cat and topic
        if (0 == $totalcat) {
            redirect_header('javascript:history.go(-1)', 1, _MD_MAINNOSELECTCAT);

            exit();
        }
        //Neu ko co tp nao
        if (0 == $totaltopics && !$access) {
            redirect_header('javascript:history.go(-1)', 1, _MD_MAINNOTOPICS);

            exit();
        }
        // Neu Good thi -> OK, lay du lieu
        $cat_info = $xoopsDB->fetchArray($sql);
        $category['name'] = $cat_info['name'];
        $category['description'] = $myts->displayTarea($cat_info['description'], $html, $smiley, $xcode);
        $uid = $cat_info['uid'];
        if (0 == $uid) {
            $category['editor'] = _MD_GUEST;
        } else {
            $thisUser = new XoopsUser($uid);

            $thisUser->getVar('uname');

            $thisUser->getVar('uid');

            $category['editor'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $thisUser->uid() . "'>" . $thisUser->uname() . '</a>';
        }
        while (false !== ($cat_data = $xoopsDB->fetchArray($result))) {
            $topics['summary'] = $myts->displayTarea($cat_data['summary'], $html, $smiley, $xcode);

            $topics['question'] = $cat_data['question'];

            $topics['topicID'] = $cat_data['topicID'];

            $xoopsTpl->append('topics', ['id' => $topics['topicID'], 'question' => $topics['question'], 'summary' => $topics['summary']]);
        }
        //He he, nap vao template, Ahah, view!
        $supname = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID='$sc'");
        [$supname] = $GLOBALS['xoopsDB']->fetchRow($supname);
        $xoopsTpl->assign('category', $category);
        $xoopsTpl->assign(['lang_tbofcontents' => _MD_TBOFCONTENTS, 'supName' => $supname, 'lang_supcattext' => _MD_MAINSUPCATHEAD, 'supID' => $sc, 'catID' => $c]);
        break;
    case 'viewcat':
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        $index = [];
        $GLOBALS['xoopsOption']['template_main'] = 'ecotut_category.html';
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE supID='$sc' AND mod>0 ORDER BY name");
        $total = $xoopsDB->getRowsNum($result);
        if (0 == $total) {
            redirect_header('javascript:history.go(-1)', 1, _MD_MAINNOCATADDED);

            exit();
        }

        $supname = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID='$sc'");
        [$supname] = $GLOBALS['xoopsDB']->fetchRow($supname);
        $xoopsTpl->assign(['supName' => $supname, 'lang_supcattext' => _MD_MAINSUPCATHEAD, 'supID' => $sc]);

        while (false !== ($query_data = $xoopsDB->fetchArray($result))) {
            $query_data['name'] = $query_data['name'];

            $query_data['description'] = $myts->displayTarea($query_data['description'], $html, $smiley, $xcode);

            //$page = array("ID" => $query_data['catID'], "DESCRIPTION" => $query_data['description'], "CATEGORY" => $query_data['name'], "TOTAL" => $query_data['total']);

            $xoopsTpl->append('catpage', ['id' => $query_data['catID'], 'description' => $query_data['description'], 'name' => $query_data['name'], 'total' => $query_data['total']]);
        }
        $xoopsTpl->assign(['lang_category' => _MD_MAININDEXCAT, 'lang_description' => _MD_MAININDEXDESC, 'lang_total' => _MD_MAININDEXTOTAL, 'lang_indextext' => _MD_MAININDEX, 'lang_articleheading' => '<h4>' . sprintf(_MD_WELCOMETOSEC, $xoopsConfig['sitename'], _MD_CAPTION) . '</h4>']);
        break;
    case 'viewsup':
    default:
        global $xoopsUser, $xoopsConfig, $xoopsDB;
        $supcat = [];
        $GLOBALS['xoopsOption']['template_main'] = 'ecotut_supcat.html';
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . ' ORDER BY name');
        $total = $xoopsDB->getRowsNum($result);
        if (0 == $total) {
            redirect_header('javascript:history.go(-1)', 1, _MD_MAINNOSUPCATADDED);

            exit();
        }
        while (false !== ($query_data = $xoopsDB->fetchArray($result))) {
            $totalcatofsup = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE supID='" . $query_data['supID'] . "' AND mod>0");

            $totalcatofsup = $xoopsDB->getRowsNum($totalcatofsup);

            $query_data['description'] = $myts->displayTarea($query_data['description'], $html, $smiley, $xcode);

            $xoopsTpl->append('supcatpage', ['id' => $query_data['supID'], 'description' => $query_data['description'], 'name' => $query_data['name'], 'total' => $totalcatofsup]);
        }
        $xoopsTpl->assign(['lang_total' => _MD_MAINSUPCATTOTAL, 'lang_supcattext' => _MD_MAINSUPCATHEAD, 'lang_articleheading' => sprintf(_MD_WELCOMETOSEC, _MD_CAPTION)]);
}
require XOOPS_ROOT_PATH . '/footer.php';
