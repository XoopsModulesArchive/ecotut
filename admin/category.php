<?php

/*
* $Id: category.php,v 1.1 2006/03/27 08:03:51 mikhail Exp $
* Licence: GNU
*/

include 'admin_header.php';
$myts = MyTextSanitizer::getInstance();
$op = '';
global $supID, $xoopsUser;
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
if (isset($_POST['mod'])) {
    $op = 'mod';
} elseif (isset($_POST['del'])) {
    $op = 'del';
} elseif (isset($_POST['jump'])) {
    $op = 'jump';
} elseif (isset($_POST['editor'])) {
    $op = 'editor';
}

function makeCatSelBox()
{
    global $xoopsDB, $supID;

    $myts = MyTextSanitizer::getInstance();

    echo "<select name='catid'>";

    $sql = 'SELECT catID, name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE supID = '$supID' ORDER BY catID";

    $result = $xoopsDB->query($sql);

    if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
        echo "<option value='0'>----</option>\n";
    }

    while (list($catid, $name) = $xoopsDB->fetchRow($result)) {
        echo "<option value='$catid'>$name</option>\n";
    }

    echo "</select>\n";
}

function editcat($supid, $catid = '')
{
    $name = '';

    $description = '';

    global $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $supID;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    if ($modify) {
        $result = $xoopsDB->query('SELECT name, description FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$catid'");

        [$name, $description] = $GLOBALS['xoopsDB']->fetchRow($result);

        if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_NOCATTOEDIT);

            exit();
        }

        $supcat = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$supid'");

        [$supcatname] = $GLOBALS['xoopsDB']->fetchRow($supcat);

        echo '<h3>' . _AM_MODIFYCAT . '</h3>';

        echo "<b>+ <a href='supercategory.php?op=default'>" . _AM_FADSUPCAT . "</a> >> <a href=\"category.php?supID=$supid\">$supcatname</a> >> $name</b>";

        $sform = new XoopsThemeForm(_AM_MODIFYCAT, 'op', xoops_getenv('PHP_SELF'));
    } else {
        $sform = new XoopsThemeForm(_AM_ADDCAT, 'op', xoops_getenv('PHP_SELF'));
    }

    $sform->addElement(new XoopsFormText(_AM_CATNAME, 'name', 50, 80, $name), true);

    $sform->addElement(new XoopsFormDhtmlTextArea(_AM_CATDESCRIPT, 'description', $description, 15, 60));

    $sform->addElement(new XoopsFormHidden('catid', $catid));

    $sform->addElement(new XoopsFormHidden('supID', $supid));

    $sform->addElement(new XoopsFormHidden('modify', $modify));

    $button_tray = new XoopsFormElementTray('', '');

    $hidden = new XoopsFormHidden('op', 'addcat');

    $button_tray->addElement($hidden);

    if ('0' == $modify) {
        $button_tray->addElement(new XoopsFormButton('', 'update', _AM_CREATE, 'submit'));
    } else {
        $button_tray->addElement(new XoopsFormButton('', 'update', _AM_MODIFY, 'submit'));
    }

    $sform->addElement($button_tray);

    $sform->display();

    unset($hidden);
}

switch ($op) {
    case 'jump':
        redirect_header('faq.php?supID=' . $_POST['supID'] . '&catID=' . $_POST['catid'] . '', 0, _AM_EXPLOFAQ);
        break;
    case 'mod':
        xoops_cp_header();
        $modify = 1;
        editcat($_POST['supID'], $_POST['catid']);
        faqlinks();
        break;
    case 'addcat':

        global $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $myts, $supID;

        if (isset($_POST['catid'])) {
            $catid = $_POST['catid'];
        }
        if (isset($_POST['supID'])) {
            $supID = $_POST['supID'];
        }

        $name = $myts->addSlashes($_POST['name']);
        $description = $myts->addSlashes($_POST['description']);
        $description = str_replace("\r\n", '', $description);
        $description .= ' ';
        $name = str_replace('"', '&quot;', $name);

        echo $_POST['modify'];

        if (empty($name)) {
            redirect_header('javascript:history.go(-1)', 1, _AM_UNKNOWERR);
        }
        if (empty($description)) {
            redirect_header('javascript:history.go(-1)', 1, _AM_UNKNOWERR);
        }
        // Run the query and update the data
        $uid = 1;
        if ('0' == $_POST['modify']) {
            if ($xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('ecotu_faqcategories') . " (catID, name, description, total, uid, mod, supID) VALUES ('', '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5)
                                . "', '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5)
                                . "', '0', '" . $uid . "', '1', '" . $supID . "')")) {
                redirect_header("category.php?supID=$supID", 1, _AM_CATCREATED);
            } else {
                redirect_header("category.php?supID=$supID", 1, _AM_NOTUPDATED);
            }
        } else {
            if ($xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET name = '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "', description = '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5) . "' WHERE catID = '$catid'")) {
                redirect_header("category.php?supID=$supID", 1, _AM_CATMODIFY);
            } else {
                redirect_header('javascript:history.go(-2)', 1, _AM_NOTUPDATED);
            }
        }
        //}

        exit();
        break;
    case 'update':

        global $xoopsUser, $xoopDB, $xoopsConfig, $myts, $supID;

        $catid = $myts->addSlashes($_POST['catid']);
        $name = $myts->addSlashes($_POST['name']);
        $description = $myts->addSlashes($_POST['description']);

        if (isset($_POST['supID'])) {
            $supID = $_POST['supID'];
        }

        $description = str_replace("\r\n", '', $description);
        $description .= ' ';
        $name = str_replace('"', '&quot;', $name);

        echo $_POST['modify'];

        if (empty($name)) {
            redirect_header('javascript:history.go(-1)', 1, 'Category name is empty!');
        } elseif (empty($description)) {
            redirect_header('javascript:history.go(-1)', 1, 'Category description is empty!');
        } else {
            if ($xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET name = '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "', description = '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5) . "' WHERE catID = '$catid'")) {
                redirect_header("category.php?supID=$supID", 1, _AM_UPDATED);
            } else {
                redirect_header("category.php?supID=$supID", 1, _AM_NOTUPDATED);
            }
        }
        exit();
        break;
    case 'del':
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $supID;
        if ($confirm) {
            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$catid'");

            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$catid'");

            redirect_header("category.php?supID=$supID", 1, sprintf(_AM_CATISDELETED, $question));

            exit();
        }
            $catid = $_POST['catid'];
            $supID = $_POST['supID'];
            $result = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catid = '$catid'");
            [$name] = $GLOBALS['xoopsDB']->fetchRow($result);
            xoops_cp_header();
            echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo '<h4>';
            echo '' . _AM_DELETETHISCAT . "</font></h4>$name<br><br>";
            echo '<table><tr><td>';
            echo myTextForm('category.php?op=del&catid=' . $_POST['catid'] . "&confirm=1&question=$name&supID=$supID", _AM_YES);
            echo '</td><td>';
            echo myTextForm("category.php?supID=$supID", _AM_NO);
            echo '</td></tr></table>';
            echo '</div><br><br>';
            echo '</td></tr></table>';
            xoops_cp_footer();

        exit();
        break;
    case 'editor':
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $supID;
        $catID = $_POST['catid'];
        $supID = $_POST['supID'];
        if ($changeEditor) {
            $editorName = $_POST['editorName'];

            $result = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " WHERE uname = '$editorName'");

            $check = $GLOBALS['xoopsDB']->getRowsNum($result);

            if ($check > 0) {
                [$uid] = $xoopsDB->fetchRow($result);

                $sql = 'UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET uid='$uid' WHERE catID='$catID'";

                $result = $xoopsDB->query($sql);

                redirect_header("category.php?supID=$supID", 1, _AM_UPDATEEDITOK);
            } else {
                redirect_header("category.php?supID=$supID", 1, _AM_NOEDITNAME);
            }
        } else {
            xoops_cp_header();

            $result = $xoopsDB->query('SELECT uid, name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " where catID='$catID'");

            [$uid, $name] = $xoopsDB->fetchRow($result);

            $check = $GLOBALS['xoopsDB']->getRowsNum($result);

            if ($check > 0) {
                if (0 == $uid) {
                    $editorName = 'Guest';
                } else {
                    $thisUser = new XoopsUser($uid);

                    $thisUser->getVar('uname');

                    $thisUser->getVar('uid');

                    $editorName = $thisUser->uname();
                }

                $sup_info = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " where supID='$supID'");

                [$supName] = $xoopsDB->fetchRow($sup_info);

                echo '<div><h3>' . _AM_FADMINCATH . '</h3></div>';

                echo "<div><b>+ <a href='supercategory.php?op=default'>" . _AM_FADSUPCAT . "</a> >><a href='category.php?supID=$supID'> $supName</b></a> >> $name</div>";

                $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " where supID='$supID'");

                $sform = new XoopsThemeForm(_AM_CHANGEEDITOR, 'storyform', xoops_getenv('PHP_SELF'));

                ob_start();

                $sform->addElement(new XoopsFormHidden('catid', $catID));

                $sform->addElement(new XoopsFormHidden('changeEditor', '1'));

                $sform->addElement(new XoopsFormHidden('uid', '$uid'));

                $sform->addElement(new XoopsFormText(_AM_EDITORNAME, 'editorName', 50, 80, $editorName), true);

                $sform->addElement(new XoopsFormHidden('supID', $supID));

                ob_end_clean();

                // Add button: Modify and Delete  and Jumpto

                $button_tray = new XoopsFormElementTray(_AM_WITHSUPCAT, '');

                $button_tray->addElement(new XoopsFormButton('', 'editor', _AM_CHANGEDITOR, 'submit'));

                $sform->addElement($button_tray);

                $sform->display();
            }
        }
        faqlinks();
        break;
    case 'default':
    default:
        $modify = '0';
        $name = '';
        $description = '';
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $supID, $catid;
        $result = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " where supID='$supID'");
        [$name] = $xoopsDB->fetchRow($result);
        $check = $GLOBALS['xoopsDB']->getRowsNum($result);
        if (0 == $check) {
            redirect_header('javascript:history.go(-1)', 1, 'Kong thay Cat nay');
        }
        xoops_cp_header();
        echo '<div><h3>' . _AM_FADMINCATH . '</h3></div>';
        echo "<div><b>+ <a href='supercategory.php?op=default'>" . _AM_FADSUPCAT . "</a> >><a href='category.php?supID=$supID'> $name</b></a></div>";
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " where supID='$supID'");
        $check = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($check > 0) {
            //$mytree = new XoopsTree($xoopsDB->prefix("ecotu_faqcategories"),"catid","0");

            $sform = new XoopsThemeForm(_AM_CATACTION, 'storyform', xoops_getenv('PHP_SELF'));

            //Category actions

            ob_start();

            $sform->addElement(new XoopsFormHidden('catid', ''));

            //$mytree->makeMySelBox("name", "catid");

            makeCatSelBox();

            $sform->addElement(new XoopsFormLabel(_AM_ACTIONTHISCAT, ob_get_contents()));

            $sform->addElement(new XoopsFormHidden('supID', $supID));

            ob_end_clean();

            // Add button: Modify and Delete  and Jumpto

            $button_tray = new XoopsFormElementTray(_AM_WITHSUPCAT, '');

            $button_tray->addElement(new XoopsFormButton('', 'mod', _AM_MODIFY, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'del', _AM_DELETE, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'jump', _AM_JUMPTOFAQ, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'editor', _AM_CHANGEEDITOR, 'submit'));

            $sform->addElement($button_tray);

            $sform->display();
        }
        editcat($supID);
        faqlinks();
        break;
}
wffaqfooter();
xoops_cp_footer();
