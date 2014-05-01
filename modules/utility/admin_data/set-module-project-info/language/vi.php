<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$u_lang = array();

$u_lang['info'] = 'Ứng dụng này giúp cập nhật file mô tả của các file php trong các module. Lưu ý: Ứng dụng được xây dựng dành cho các nhà phát triển, chỉ chạy trên môi trường safe_mode OFF, nếu hosting của bạn có chế độ phân quyền ghi file chặt chẽ, hãy chắc chắn hàm file_put_contents có khả năng ghi file trước khi sử dụng tiện ích này. Hãy chắc chắn các file php trong module của bạn hợp chuẩn với NukeViet để hạn chế thấp nhất rủi ro có thể xảy ra. Thông tin vầ chuẩn các file php có thể xem tại <a target="_blank" href="http://wiki.nukeviet.vn/programming:rule#dối_với_cac_file_php">http://wiki.nukeviet.vn/programming:rule</a>. Để an toàn, hãy đóng gói module trước khi cập nhật. Lời khuyên: Dùng tốt hơn trên localhost.';

$u_lang['step1'] = 'Đầu tiên hãy điền thông tin dự án';
$u_lang['project_name'] = 'Tên dự án';
$u_lang['project_version'] = 'Phiên bản';
$u_lang['project_author'] = 'Tác giả';
$u_lang['project_email'] = 'Email';
$u_lang['project_year'] = 'Năm';
$u_lang['project_copyright'] = 'Bản quyền';
$u_lang['project_createdate'] = 'Thời gian tạo';

$u_lang['step2'] = 'Tiếp theo hãy chọn module cần ghi nội dung vào';
$u_lang['step3'] = 'Cuối cùng hãy nhấp vào đây để hoàn tất quá trình';
$u_lang['error_choose'] = 'Hãy chọn ít nhất một module để thực hiện';
$u_lang['waiting'] = 'Hãy đợi, hệ thống đang làm việc, vui lòng đừng làm gì cho đến khi có thông báo :D Nếu không hậu quả sẽ khôn lường';
$u_lang['ok'] = 'Thực hiện thành công, dưới đây là thông tin';
$u_lang['result'] = 'Hệ thống đã sửa đổi thành công các file sau đây';
$u_lang['error'] = 'Có lỗi rồi! Hệ thống không ghi được file ';

?>