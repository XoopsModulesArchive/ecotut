<?php

/*
* $Id: submissions.php,v 1.1 2006/03/27 08:03:52 mikhail Exp $
* Licence: GNU
*/

require __DIR__ . '/admin_header.php';

$op = '';

global $xoopsUser, $xoopsUser, $xoopsConfig;

$myts = MyTextSanitizer::getInstance();

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

switch ($op) {
    case 'view':

        global $xoopsUser, $xoopsDB;
        //if (empty($c)) $c = 1;
        // Display the answer
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqtopics') . " WHERE topicID = $t");
        [$topicID, $catID, $question, $answer, $summary, $uid, $submit, $datesub] = $GLOBALS['xoopsDB']->fetchRow($result);

        $result2 = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c'");
        [$cat] = $xoopsDB->getRowsNum($result2);

        $answer = str_replace("\r\n", '<br>', $answer);
        $answer = str_replace("\n", '<br>', $answer);

        if ($uid) {
            $user = new xoopsUser($uid);

            $poster = $user->getVar('uname');

            $submitter = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $uid . "'>$poster</a>";
        } else {
            $submitter = 'Guest';
        }

        $datesub = formatTimestamp($datesub, 'D, d-M-Y, H:i');

        xoops_cp_header();
        echo "<table border='0' width='100%' cellspacing='1' cellpadding='2'>";
        echo "<tr valign='middle' class='b4'>";
        echo "<td align='left' colspan='3' class='bg3'><b>" . _AM_SUBPREVIEW . '</b></td></tr>';
        echo '<tr>';
        echo "<td width='100%'><br><br>" . _AM_SUBADMINPREV . '</td>';
        echo '</tr>';
        echo '</table>';
        echo "<table border='0' width='100%' cellspacing='1' cellpadding='2'>";
        echo '<tr>';
        echo "<td class='bg3' colspan='2'><b>&nbsp;" . _MD_FAQ . ": $question</td>";
        echo '</tr>';
        echo "<tr><td class='head'>" . _AM_AUTHOR . ": $submitter";
        echo '<br>' . _AM_PUBLISHED . ": $datesub</td></tr>";
        echo "<td><br>$answer<br><br></td>";
        echo '</tr>';
        echo "<tr><td class='even'  align = 'center'><b>&nbsp<a href='submissions.php?op=allow&t=$t&c=$c'>" . _AM_SUBALLOW . "</a> <a href='index.php?op=del&subm=1&t=$topicID''>" . _AM_SUBDELETE . '</a></b></td></tr>';
        echo "<tr><td class='head' colspan='2' align = 'center'><a href='submissions.php?op=cat'>" . _AM_SUBRETURNTO . '</a></td></tr>';
        echo '</table>';
        exit();
        break;
    case 'allow':

        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('ecotu_faqcategories') . " SET mod = 1 WHERE catID = $c");
        redirect_header('submissions.php?op=default', 1, _AM_DBUPDATED);
        exit();
        break;
    case 'del':
        if (1 == $q) {
            xoops_cp_header();

            $catname = $xoopsDB->query('SELECT name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c'");

            [$catname] = $GLOBALS['xoopsDB']->fetchRow($catname);

            echo "<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";

            echo "<div class='confirmMsg'>";

            echo '<h4>';

            echo '' . _AM_DELETETHISCAT . "</font></h4>$catname<br><br>";

            echo '<table><tr><td>';

            echo myTextForm("submissions.php?op=del&c=$c", _AM_YES);

            echo '</td><td>';

            echo myTextForm('submissions.php?op=default', _AM_NO);

            echo '</td></tr></table>';

            echo '</div><br><br>';

            echo '</td></tr></table>';

            xoops_cp_footer();

            exit();
        }

        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = $c");
        redirect_header('submissions.php', 1, _AM_DBDELETED);
        exit();
        break;
    case 'cat':
    default:

        global $xoopsUser, $xoopDB, $xoopsConfig;
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE mod = '0' ORDER BY catID";
        $results = $xoopsDB->query($sql);
        $totalfiles = $xoopsDB->getRowsNum($results);

        if (0 == (int)$totalfiles) {
            redirect_header('index.php?op=default', 1, _AM_SUBNODATA);

            exit();
        }
            xoops_cp_header();
            // Display the questions

            //faqlinks();
            echo '<br>';
            echo "<table border='0' width='100%'  cellspacing='1' cellpadding='4' class = 'outer'>";
            echo "<tr valign='middle' >";
            echo "<td align='left' class='bg3' colspan =5><b>" . _AM_FVAL . '</b></td>';
            echo '</tr></table>';
            echo '<br>';
            echo "<div width='100%' colspan =5 ><b>New Submissions</b></div>";
            echo '<br>';
            echo "<table border='0' width='100%' cellspacing='1' cellpadding='2' class = 'outer'>";
            echo "<td width='5%' align='center' valign='middle' class='bg3'><b>catID</b></td>";
            echo "<td width='25%' align='left' valign='middle' class='bg3'><b>" . _AM_SUBCATNAME . '</b></td>';
            echo "<td width='25%' align='center' valign='middle' class='bg3'><b>" . _AM_AUTHOR . '</b></td>';

            echo "<td width='25%' align='center' colspan='2' class='bg3'><b>" . _AM_SUBACTION . '</b></td>';
            echo '</tr>';

            while (list($catID, $name, $description, $total, $uid, $mod, $supID) = $xoopsDB->fetchRow($results)) {
                if ($uid) {
                    $user = new xoopsUser($uid);

                    $poster = $user->getVar('uname');

                    $submitter = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $uid . "'>$poster</a>"; //$thisUser->getVar("uname");
                } else {
                    $submitter = _AM_SUBGUEST;
                }

                echo '<tr>';

                echo "<td class='head' align = 'center'>$catID</td>";

                echo "<td class='even'><a href='submissions.php?op=view&t=$topicID&c=$catID'>$name</a></td>";

                echo "<td class='even'><p align='center'>$submitter</td>";

                echo "<td align='center' class='even' > <a href='submissions.php?op=allow&c=$catID'>" . _AM_SUBALLOW . '</a></td>';

                echo "<td align='center' class='even' > <a href='submissions.php?op=del&q=1&c=$catID'>" . _AM_SUBDELETE . '</a>';

                echo '</td></tr>';
            }
            echo '</table>';

        break;
}
wffaqfooter();
xoops_cp_footer();
