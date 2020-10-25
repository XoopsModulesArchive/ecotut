<?php

/*
* $Id: faq.php,v 1.1 2006/03/27 08:03:51 mikhail Exp $
* Licence: GNU
*/

include 'admin_header.php';

$myts = MyTextSanitizer::getInstance();

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
if (!isset($supID) || ($supID <= 0)) {
    redirect_header('javascript:history.go(-1)', 1, 'Error: You must selected a supercategory!');
}

if (!isset($catID) || ($catID <= 0)) {
    redirect_header('javascript:history.go(-1)', 1, 'Error: You must selected a category!');
}

if (isset($_POST['mod'])) {
    $op = 'mod';
} elseif (isset($_POST['del'])) {
    $op = 'del';
} elseif (isset($_POST['jump'])) {
    $op = 'jump';
}

/**
 * Check to see if any categories have been created
 * if true continue script
 * if false warns user that no categories have been created.
 */
$result = $xoopsDB->query('SELECT catID, name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . ' ORDER BY name');
if ('0' == $GLOBALS['xoopsDB']->getRowsNum($result)) {
    redirect_header('supercategory.php', '1', _AM_NOTCTREATEDACAT);

    exit();
}

/*
* Function to view Topics in selectbox
*/
function makeTpSelBox()
{
    global $xoopsDB, $supID, $catID;

    $myts = MyTextSanitizer::getInstance();

    echo "<select name='topicID'>";

    $sql = 'SELECT topicID, question FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$catID' ORDER BY topicID";

    $result = $xoopsDB->query($sql);

    if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
        echo "<option value='0'>----</option>\n";
    }

    while (list($topicID, $name) = $xoopsDB->fetchRow($result)) {
        echo "<option value='$topicID'>$name</option>\n";
    }

    echo "</select>\n";
}

/*
* Function to edit and modify Topics
*/
function edittopic($supid, $catid, $topicid = '')
{
    /*
    * Clear all variable before we start
    */

    $question = '';

    $answer = '';

    $summary = '';

    //$catid = 0;

    $oldid = 0;

    global $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $supID, $catID, $topicID;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    /*
* checks to see if we are modifying a FAQ
*/

    if ($modify) {
        /*
        * Get FAQ info from database
        */

        $result = $xoopsDB->query('SELECT topicID, question, answer, summary FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = '$topicID'");

        [$topicID, $question, $answer, $summary] = $xoopsDB->fetchRow($result);

        $oldid = $catID;

        /*
        * If no FAQ are found, tell user and exit
        */

        if (0 == $xoopsDB->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_NOTOPICTOEDIT);

            exit();
        }

        $supcat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$supid'");

        $cat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$catid'");

        [$supcatname] = $GLOBALS['xoopsDB']->fetchRow($supcat);

        [$catname] = $GLOBALS['xoopsDB']->fetchRow($cat);

        echo '<h3>' . _AM_MODIFYFAQ . '</h3>';

        echo "<b>+ <a href='supercategory.php?op=default'>" . _AM_FADSUPCAT . "</a> >> <a href=\"category.php?supID=$supid\">$supcatname</a> >> <a href=\"faq.php?supID=" . $supid . "&catID=$catid\">$catname</a> >> $question</b>";

        $sform = new XoopsThemeForm(_AM_MODIFYEXSITFAQ, 'op', xoops_getenv('PHP_SELF'));
    } else {
        $sform = new XoopsThemeForm(_AM_ADDFAQ, 'op', xoops_getenv('PHP_SELF'));
    }

    /*
    * Get information for pulldown menu using XoopsTree.
    * First var is the database table
    * Second var is the unique field ID for the categories
    * Last one is not set as we do not have sub menus in WF-FAQ
    */ //$mytree = new XoopsTree($xoopsDB->prefix("ecotu_faqcategories"),"catid","0");

    /*
    * Get the mytree pulldown object for use with XoopsForm class
    */

    ob_start();

    $sform->addElement(new XoopsFormHidden('catID', $catID));

    $sform->addElement(new XoopsFormHidden('supID', $supID));

    //$mytree->makeMySelBox("name", $catid);

    //$sform->addElement(new XoopsFormLabel(_AM_CREATEIN, ob_get_contents()));

    ob_end_clean();

    /*
    * Set the user textboxs using XoopsForm Class for user input
    */

    $sform->addElement(new XoopsFormText(_AM_TOPICQ, 'question', 50, 80, $question), true);

    $sform->addElement(new XoopsFormDhtmlTextArea(_AM_TOPICA, 'answer', $answer, 15, 60), true);

    $sform->addElement(new XoopsFormDhtmlTextArea(_AM_SUMMARY, 'summary', $summary, 7, 60), true);

    /*
    * XoopsFormHidden, pass on 'unseen' var's'
    */

    $sform->addElement(new XoopsFormHidden('topicID', $topicID));

    $sform->addElement(new XoopsFormHidden('modify', $modify));

    $sform->addElement(new XoopsFormHidden('oldid', $oldid));

    /*
    * XoopsForm Class Buttons
    */

    $button_tray = new XoopsFormElementTray('', '');

    $hidden = new XoopsFormHidden('op', 'save');

    $button_tray->addElement($hidden);

    /*
    * Switch to show different buttons for either when creating or modifying a FAQ.
    */

    if (!$modify) {
        $button_tray->addElement(new XoopsFormButton('', 'create', _AM_CREATE, 'submit'));
    } else {
        $button_tray->addElement(new XoopsFormButton('', 'update', _AM_MODIFY, 'submit'));
    }

    $sform->addElement($button_tray);

    $sform->display();

    unset($hidden);

    /*
   *End of XoopsForm
   */
}

/*
* end function
*/

switch ($op) {
    case 'mod':
        xoops_cp_header();
        $modify = 1;
        edittopic($_POST['supID'], $_POST['catID'], $_POST['topicID']);
        faqlinks();
        break;
    case 'del':
        global $xoopsUser, $xoopsConfig, $xoopsDB, $supID, $catID;

        if ($confirm) {
            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = $topicID");

            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total - 1 WHERE catID = '$catID'");

            redirect_header("faq.php?supID=$supID&catID=$catID", 1, sprintf(_AM_TOPICISDELETED, $question));

            exit();
        }
            if (!$subm) {
                $topicID = $_POST['topicID'];
            } else {
                $topicID = $t;
            }
            $catid = $_POST['catid'];
            $supID = $_POST['supID'];

            $result = $xoopsDB->query('SELECT question FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = $topicID");
            [$question] = $xoopsDB->fetchRow($result);

            xoops_cp_header();
            echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo '<h4>';
            echo '' . _AM_DELETETHISTOPIC . "</font></h4>$question<br><br>";
            echo '<table><tr><td>';
            echo myTextForm('faq.php?supID=' . $supID . '&catID=' . $catID . '&op=del&topicID=' . $topicID . "&confirm=1&question=$question", _AM_YES);
            echo '</td><td>';
            echo myTextForm('faq.php?supID=' . $supID . '&catID=' . $catID . '&op=default', _AM_NO);
            echo '</td></tr></table>';
            echo '</div><br><br>';
            echo '</td></tr></table>';
            xoops_cp_footer();

        exit();
        break;
    case 'save':
        global $xoopsUser, $xoopsDB, $supID, $catID, $topicID;

        $cat = $catID; //= $myts->addSlashes($_POST['catid']);
        $question = $myts->addSlashes($_POST['question']);
        $answer = $myts->addSlashes($_POST['answer']);
        $summary = $myts->addSlashes($_POST['summary']);
        $topicID = $myts->addSlashes($_POST['topicID']);
        $oldid = $myts->addSlashes($_POST['oldid']);
        $question = str_replace('"', '&quot;', $question);

        // Define variables
        $error = 0;
        $word = null;
        $uid = $xoopsUser->uid();
        $submit = 1;
        $date = time();
        if (!$_POST['modify']) {
            if ($xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('ecotu_faqtopics') . " (catID, question, answer, summary, uid, datesub, submit) VALUES ('$cat', '$question', '$answer', '$summary', '$uid', '$date', '$submit')")) {
                $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total + 1 WHERE catID = '$cat'");

                redirect_header('faq.php?supID=' . $supID . "&catID=$catID", '1', _AM_FAQCREATED);
            } else {
                redirect_header('faq.php?supID=' . $supID . "&catID=$catID", '1', _AM_FAQNOTCREATED);
            }
        } else {
            if ($xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqtopics') . " SET question = '$question', answer = '$answer', summary = '$summary', catID = '$cat' WHERE topicID = $topicID")) {
                if ($cat != $oldid) {
                    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total - 1 WHERE catID = '$oldid'");

                    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET total = total + 1 WHERE catID = '$cat'");
                }

                redirect_header('faq.php?supID=' . $supID . "&catID=$catID", '1', _AM_FAQMODIFY);
            } else {
                redirect_header('faq.php?supID=' . $supID . "&catID=$catID", '1', _AM_FAQNOTMODIFY);
            }
        }
        exit();
        break;
    case 'default':
    default:
        xoops_cp_header();
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $supID, $catID;
        //View path
        echo '<div><h3>' . _AM_TOPICSADMIN . '</h3></div>';
        $supcat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$supID'");
        $cat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$catID'");
        [$supcatname] = $GLOBALS['xoopsDB']->fetchRow($supcat);
        [$catname] = $GLOBALS['xoopsDB']->fetchRow($cat);
        echo "<b>+ <a href='supercategory.php?op=default'>" . _AM_FADSUPCAT . "</a> >> <a href=\"category.php?supID=$supID\">$supcatname</a> >> $catname</b>";

        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID='$catID'");
        $check = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($check >= 1) {
            $mytree = new XoopsTree($xoopsDB->prefix('ecotu_faqtopics'), 'topicID', '0');

            $sform = new XoopsThemeForm(_AM_MODIFYFAQ, 'storyform', xoops_getenv('PHP_SELF'));

            //Menu actions for FQA

            ob_start();

            $sform->addElement(new XoopsFormHidden('catID', $catID));

            $sform->addElement(new XoopsFormHidden('supID', $supID));

            $sform->addElement(new XoopsFormHidden('topicID', ''));

            makeTpSelBox();

            $sform->addElement(new XoopsFormLabel(_AM_ACTIONTHISTP, ob_get_contents()));

            ob_end_clean();

            $button_tray = new XoopsFormElementTray(_AM_WITHSUPCAT, '');

            $button_tray->addElement(new XoopsFormButton('', 'mod', _AM_MODIFY, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'del', _AM_DELETE, 'submit'));

            $sform->addElement($button_tray);

            $sform->display();
        }
        edittopic($supID, $catID);
        faqlinks();
        break;
}
wffaqfooter();
xoops_cp_footer();
