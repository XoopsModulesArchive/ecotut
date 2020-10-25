<?php

/*
* $Id: admin.php,v 1.1 2006/03/27 08:05:59 mikhail Exp $
* Licence: GNU
*/

//Main ADmin Section

define('_AM_TopicMANINTRO', 'Chào mừng bạn đến với ecoTut Control Panel');
define('_AM_UNKNOWERR', 'Lỗi khó hiểu, bó tay >_<');

/*
* Uni Lang defines
*/
define('_AM_SUBMIT', 'Tạo');
define('_AM_CREATE', 'Tạo');
define('_AM_YES', 'Ừa');
define('_AM_NO', 'Thui');
define('_AM_DELETE', 'Xóa');
define('_AM_MODIFY', 'Sửa');
define('_AM_UPDATED', 'Dữ liệu đã được cập nhật');
define('_AM_NOTUPDATED', 'Có lỗi khi cập nhật dữ liệu!');
define('_AM_CATCREATED', 'Category mới đã được tạo và lưu!');
define('_AM_CATMODIFY', 'Category đã được sửa và lưu!');
/*
* Lang defines for functions.php
*/
define('_AM_FADMINHEAD', 'ecoTut Admin CP');
define('_AM_SUPPAGE', 'Quản lý nội dung');
define('_AM_SUBALLOW', 'Duyệt đơn xin phép');
define('_AM_FVAL', 'Hiệu lực cho các Category mới gởi');
define('_AM_FADMINCATH', 'ecoTut Category Admin');

/*
*Lang defines for supercategory.php
*/
define('_AM_FADSUPCAT', 'SuperCategory');
define('_AM_ADDSUPCAT', 'Thêm SuperCategory');
define('_AM_MODIFYSUPCAT', 'Sửa SuperCategory');
define('_AM_FADMINSUPCATH', 'ecoTut SuperCategory Admin');
define('_AM_SUPCATNAME', 'Tên SuperCategory');
define('_AM_SUPCATDESCRIPT', 'Vài dòng giới thiệu cho SuperCategory');
define('_AM_NOSUPCATTOEDIT', 'Không có Supercategory nào để sửa cả.');
define('_AM_ACTIONSUPCAT', 'Tác động vào SuperCategory');
define('_AM_SUPCATMODIFY', 'SuperCategory đã được sửa');
define('_AM_SUPCATCREATED', 'SuperCategory đã được tạo');
define('_AM_DELSUPCAT', 'Xóa SuperCategory');
define('_AM_WITHSUPCAT', 'Bạn muốn');
define('_AM_ACTIONTHISSUPCAT', 'Với SuperCategory');
define('_AM_DELETETHISSUPCAT', 'Này, bồ muốn XÓA SuperCategory này thiệt hả?');
define('_AM_SUPCATISDELETED', 'SuperCategory %s đã về phương trời xa');
define('_AM_JUMPTOCAT', 'Duyệt Categories');
define('_AM_EXPLOCAT', 'Đang duyệt các Categories');
/*
* Lang defines for Category.php
*/
define('_AM_CATACTION', 'Tác động vào Category');
define('_AM_WITHCAT', 'Với Category');
define('_AM_JUMPTOFAQ', 'Duyệt Topic');
define('_AM_CHANGEEDITOR', 'Thay đổi chủ bút');
define('_AM_EDITORNAME', 'Tên chủ bút');
define('_AM_CHANGEDITOR', 'Thay bằng người này');
define('_AM_UPDATEEDITOK', 'OK, đã thay chủ bút, tên chủ cũ chắc sẽ tức như...kiến cắn đê >_<');
define('_AM_NOEDITNAME', 'Này, Chả có ai tên như rứa!');
define('_AM_CATNAME', 'Tên Category');
define('_AM_CATDESCRIPT', 'Vài dòng chú thích cho Category này');
define('_AM_NOCATTOEDIT', 'Không có Category nào để sửa cả.');
define('_AM_MODIFYCAT', 'Sửa Category');
define('_AM_DELCAT', 'Xóa Category');
define('_AM_ADDCAT', 'Thêm Category');
define('_AM_ACTIONTHISCAT', 'Với Category');
define('_AM_DELETETHISCAT', 'Này, XÓA Category này thiệt hả?');
define('_AM_CATISDELETED', 'Category %s đã bị xóa');
define('_AM_EXPLOFAQ', 'Đang duyệt Topic');

/*
* Lang defines for topics.php
*/
define('_AM_TOPICSADMIN', 'ecoTut Topics Admin');
define('_AM_ACTIONTHISTP', 'Với Topic');
define('_AM_MODIFYFAQ', 'Tác động vào Topic');
define('_AM_ADDFAQ', 'Thêm Topic');
define('_AM_NOTCTREATEDACAT', 'Bạn không thể thêm một Topic cho đến khi bạn tạo một Topic Category!');
define('_AM_ADDFAQ', 'Tạo Topic mới');
define('_AM_CREATEIN', 'Tạo trong');
define('_AM_TOPICQ', 'Câu hỏi');
define('_AM_TOPICA', 'Trả lời');
define('_AM_SUMMARY', 'Tóm tắt');
define('_AM_MODIFYEXSITFAQ', 'Sửa Topic');
define('_AM_MODIFYTHISFAQ', 'Sửa Topic question này');
define('_AM_DELFAQ', 'Xóa Topic');
define('_AM_DELTHISFAQ', 'Xóa Topic này');
define('_AM_NOTOPICTOEDIT', 'Không có Topic nào trong database để sửa cả');
define('_AM_DELETETHISTOPIC', 'Xóa Topic này chứ?');
define('_AM_TOPICISDELETED', 'Topic %s đã bị xóa');
define('_AM_FAQCREATED', 'Topic đã được tạo và lưu trữ');
define('_AM_FAQNOTCREATED', 'Lỗi: Topic đã KHÔNG được tạo và lưu, bó tay!');
define('_AM_FAQMODIFY', 'Topic đã được sửa và lưu');
define('_AM_FAQNOTMODIFY', 'Lỗi: Topic đã KHÔNG được sửa và lưu, tui bó tay!');

/*
* define for submissions
*/
define('_AM_SUBALLOW', 'Cấp phép');
define('_AM_SUBDELETE', 'Xóa');
define('_AM_SUBRETURN', 'Trở lại Admin');
define('_AM_SUBRETURNTO', 'Trở lại New Submissions');
define('_AM_SUBCATNAME', 'Tên Cat');
define('_AM_AUTHOR', 'Tác giả');
define('_AM_SUBACTION', 'Tác động');
define('_AM_SUBGUEST', 'Khách lạ');
define('_AM_PUBLISHED', 'Đã xuất bản');
define('_AM_SUBPREVIEW', 'Xem ecoTut Tut Admin');
define('_AM_SUBADMINPREV', 'Admin, bồ đang xem qua Topic topic này.');

define('_AM_SUBNODATA', 'Không có Category nào cần hiệu lực');
define('_AM_DBUPDATED', 'Category đã được cấp phép');
define('_AM_DELETETHISCAT', 'Xóa Category này hả');
define('_AM_DBDELETED', 'Category đã bị xóa');

define('_AM_VISITSUPPORT', 'Xin lưu ý, đây chỉ là bản thử nghiệm<br> ecoTut v1.2 RC1 [phát triển từ WF-FAQ v1.1] <a href="http://wfsections.xoops2.com/" target="_blank">Catzwolf</a>:<a href = "http://ecoblue.net.ms" target ="_blank">ghostEye</a> &copy; 2004 ecoTut');
