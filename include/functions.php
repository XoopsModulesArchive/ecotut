<?php

/*
* $Id: functions.php,v 1.1 2006/03/27 08:05:31 mikhail Exp $
* Licence: GNU
*/

function generatecjump()
{
    global $PHP_SELF, $tbprefix, $xoopsDB;

    $result = $xoopsDB->query('SELECT catID, name FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . '');

    if (1 == $xoopsDB->fetchRow($result)) {
        return '&nbsp;';
    }

    $html = '<form method="post">';

    $html .= '<select name="cjump" onchange="jumpMenu(this)">';

    $html .= '<option value="index.php">Category Jump:</option>';

    $html .= '<option value="index.php">--------</option>';

    while (false !== ($query_data = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $html .= '<option value="index.php?op=cat&c=' . $query_data['catID'] . '">' . $query_data['name'] . '</option>';
    }

    $html .= '</select>';

    $html .= '</form>';

    return $html;
}

function faqlinks()
{
    echo '<h4>' . _AM_FADMINHEAD . '</h4>';

    echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";

    echo " - <b><a href='supercategory.php'>" . _AM_SUPPAGE . '</a></b><p></p>';

    echo " - <b><a href='submissions.php'>" . _AM_SUBALLOW . '</a></b><p></p>';

    echo '</td></tr></table>';
}

function editorCP($sc, $c, $noTpl = '0')
{
    global $xoopsUser, $xoopsDB, $xoopsTpl;

    $uid = $xoopsUser->uid();

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('ecotu_faqcategories') . " WHERE catID = '$c' AND uid = '$uid'");

    $isUser = $xoopsDB->getRowsNum($result);

    if ($isUser > 0) {
        $editorcp = "<h2 align='center'>" . _MD_FUNC_EDITCP . '</h2>';

        $editorcp .= '<fieldset><div align="center">';

        $editorcp .= "<a href='index.php?op=viewtp&c=$c" . "&sc=$sc'>" . _MD_FUNC_VIEWCAT . '</a>';

        $editorcp .= " | <a href='postfaq.php?c=$c" . "&sc=$sc'>" . _MD_FUNC_POSTF . '</a>';

        $editorcp .= " | <a href='editcp.php?c=$c" . "&sc=$sc'>" . _MD_FUNC_FCONTRL . '</a>';

        $editorcp .= '</div></fieldset>';
    } else {
        $editorcp = '';
    }

    if (0 == $noTpl) {
        $xoopsTpl->assign('editerCP', $editorcp);
    }

    return $editorcp;
}

function wffaqfooter()
{
    echo "<br><div style='text-align:center'>" . _AM_VISITSUPPORT . '</div>';
}

function viettime($datetime)
{
    $viet = (string)$datetime;

    $viet = str_replace('Jan', _AM_MONTH_JAN, $viet);

    $viet = str_replace('Feb', _AM_MONTH_FEB, $viet);

    $viet = str_replace('Mar', _AM_MONTH_MAR, $viet);

    $viet = str_replace('Apr', _AM_MONTH_APR, $viet);

    $viet = str_replace('May', _AM_MONTH_MAY, $viet);

    $viet = str_replace('Jun', _AM_MONTH_JUN, $viet);

    $viet = str_replace('Jul', _AM_MONTH_JUL, $viet);

    $viet = str_replace('Aug', _AM_MONTH_AUG, $viet);

    $viet = str_replace('Sep', _AM_MONTH_SEP, $viet);

    $viet = str_replace('Oct', _AM_MONTH_OCT, $viet);

    $viet = str_replace('Nov', _AM_MONTH_NOV, $viet);

    $viet = str_replace('Dec', _AM_MONTH_DEC, $viet);

    $viet = str_replace('Mon', _AM_DAY_MON, $viet);

    $viet = str_replace('Tue', _AM_DAY_TUE, $viet);

    $viet = str_replace('Wed', _AM_DAY_WEB, $viet);

    $viet = str_replace('Thu', _AM_DAY_THU, $viet);

    $viet = str_replace('Fri', _AM_DAY_FRI, $viet);

    $viet = str_replace('Sat', _AM_DAY_SAT, $viet);

    $viet = str_replace('Sun', _AM_DAY_SUN, $viet);

    return $viet;
}
