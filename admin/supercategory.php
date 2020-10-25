<?php

/*
* $Id: supercategory.php,v 1.1 2006/03/27 08:03:52 mikhail Exp $
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

if (isset($_POST['mod'])) {
    $op = 'mod';
} elseif (isset($_POST['del'])) {
    $op = 'del';
} elseif (isset($_POST['jump'])) {
    $op = 'jump';
}

function editsupcat($supid = '')
{
    $name = '';

    $description = '';

    global $xoopsUser, $xoopsConfig, $xoopsDB, $modify;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    if ($modify) {
        $result = $xoopsDB->query('SELECT name, description FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$supid'");

        [$name, $description] = $GLOBALS['xoopsDB']->fetchRow($result);

        if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
            redirect_header('index.php', 1, _AM_NOSUPCATTOEDIT);

            exit();
        }

        $sform = new XoopsThemeForm(_AM_MODIFYSUPCAT, 'op', xoops_getenv('PHP_SELF'));
    } else {
        $sform = new XoopsThemeForm(_AM_ADDSUPCAT, 'op', xoops_getenv('PHP_SELF'));
    }

    $sform->addElement(new XoopsFormText(_AM_SUPCATNAME, 'name', 50, 80, $name), true);

    $sform->addElement(new XoopsFormDhtmlTextArea(_AM_SUPCATDESCRIPT, 'description', $description, 15, 60));

    $sform->addElement(new XoopsFormHidden('supid', $supid));

    $sform->addElement(new XoopsFormHidden('modify', $modify));

    $button_tray = new XoopsFormElementTray('', '');

    $hidden = new XoopsFormHidden('op', 'addsupcat');

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
        redirect_header('category.php?supID=' . $_POST['supid'] . '', 0, _AM_EXPLOCAT);
        break;
    case 'mod':
        xoops_cp_header();
        $modify = 1;
        faqlinks();
        editsupcat($_POST['supid']);
        break;
    case 'addsupcat':

        global $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $myts;

        if (isset($_POST['supid'])) {
            $supid = $_POST['supid'];
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
        if ('0' == $_POST['modify']) {
            if ($xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('ecotu_faqsupcat') . " (supID, name, description) VALUES ('', '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "', '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5) . "')")) {
                redirect_header('supercategory.php', 1, _AM_SUPCATCREATED);
            } else {
                redirect_header('supercategory.php', 1, _AM_NOTUPDATED);
            }
        } else {
            if ($xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqsupcat') . " SET name = '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "', description = '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5) . "' WHERE supID = '$supid'")) {
                redirect_header('supercategory.php', 1, _AM_SUPCATMODIFY);
            } else {
                redirect_header('supercategory.php', 1, _AM_NOTUPDATED);
            }
        }
        //}

        exit();
        break;
    case 'update':

        global $xoopsUser, $xoopDB, $xoopsConfig, $myts;

        $supid = $myts->addSlashes($_POST['supid']);
        $name = $myts->addSlashes($_POST['name']);
        $description = $myts->addSlashes($_POST['description']);

        $description = str_replace("\r\n", '', $description);
        $description .= ' ';
        $name = str_replace('"', '&quot;', $name);

        echo $_POST['modify'];

        if (empty($name)) {
            redirect_header('javascript:history.go(-1)', 1, 'SuperCategory name is empty!');
        } elseif (empty($description)) {
            redirect_header('javascript:history.go(-1)', 1, 'SuperCategory description is empty!');
        } else {
            if ($xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqsupcat') . " SET name = '" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "', description = '" . htmlspecialchars($description, ENT_QUOTES | ENT_HTML5) . "' WHERE supID = '$supid'")) {
                redirect_header('supercategory.php', 1, _AM_UPDATED);
            } else {
                redirect_header('supercategory.php', 1, _AM_NOTUPDATED);
            }
        }
        exit();
        break;
    case 'del':
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
        if ($confirm) {
            //Get catID

            $sql = 'SELECT catID FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE supID = '$supid'";

            $result = $xoopsDB->query($sql);

            if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
                while (false !== ($catdel = $GLOBALS['xoopsDB']->fetchRow($result))) {
                    $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE catID = '$catdel'");
                }
            }

            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE supID = '$supid'");

            $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supID = '$supid'");

            redirect_header('supercategory.php', 1, sprintf(_AM_SUPCATISDELETED, $question));

            exit();
        }
            $catid = $_POST['supid'];
            $result = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . " WHERE supid = '$supid'");
            [$name] = $GLOBALS['xoopsDB']->fetchRow($result);
            xoops_cp_header();

            echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo '<h4>';
            echo '' . _AM_DELETETHISSUPCAT . "</font></h4>$name<br><br>";
            echo '<table><tr><td>';
            echo myTextForm('supercategory.php?op=del&supid=' . $_POST['supid'] . "&confirm=1&question=$name", _AM_YES);
            echo '</td><td>';
            echo myTextForm('supercategory.php?op=default', _AM_NO);
            echo '</td></tr></table>';
            echo '</div><br><br>';
            echo '</td></tr></table>';

            xoops_cp_footer();

        exit();
        break;
    case 'default':
    default:
        xoops_cp_header();
        $modify = '0';
        $name = '';
        $description = '';
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
        //Turn on FAQAdminCP
        faqlinks();
        echo '<div><h3>' . _AM_FADMINSUPCATH . '</h3></div>';

        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqsupcat') . '');
        $check = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($check > 0) {
            $mytree = new XoopsTree($xoopsDB->prefix('ecotu_faqsupcat'), 'supid', '0');

            $sform = new XoopsThemeForm(_AM_ACTIONSUPCAT, 'storyform', xoops_getenv('PHP_SELF'));

            //Action: Modify or Del... a SuperCategory

            ob_start();

            $sform->addElement(new XoopsFormHidden('supid', ''));

            $mytree->makeMySelBox('name', 'supid');

            $sform->addElement(new XoopsFormLabel(_AM_ACTIONTHISSUPCAT, ob_get_contents()));

            ob_end_clean();

            // Add button: Modify and Delete  and Jumpto

            $button_tray = new XoopsFormElementTray(_AM_WITHSUPCAT, '');

            $button_tray->addElement(new XoopsFormButton('', 'mod', _AM_MODIFY, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'del', _AM_DELETE, 'submit'));

            $button_tray->addElement(new XoopsFormButton('', 'jump', _AM_JUMPTOCAT, 'submit'));

            $sform->addElement($button_tray);

            $sform->display();
        }
        editsupcat();
        break;
}
wffaqfooter();
xoops_cp_footer();
