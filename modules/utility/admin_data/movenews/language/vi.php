<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Language Tiếng Việt
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$u_lang = array();

$u_lang['info'] = 'Tiện ích này giúp di chuyển các bài viết trong một chuyên mục nào đó của module này vào chuyên mục nào đó của module khác sau đó xóa chuyên mục cũ (tùy chọn). Để bắt đầu hãy chọn module đầu, chuyên mục đầu và module đích, chuyên mục đích sau đó nhấp Thực hiện';
$u_lang['s_mod'] = 'Vui lòng chọn';
$u_lang['update'] = 'Cập nhật';
$u_lang['f_mod'] = 'Từ module';
$u_lang['f_cat'] = 'Từ chủ đề';
$u_lang['t_mod'] = 'Đến module';
$u_lang['t_cat'] = 'Đến chủ đề';
$u_lang['del_cat'] = 'Xóa chủ đề gốc sau khi copy';
$u_lang['action'] = 'Thực hiện';
$u_lang['error_fcat'] = 'Vui lòng chọn chủ đề gốc';
$u_lang['error_tcat'] = 'Vui lòng chọn chủ đề đến';
$u_lang['error_tmod'] = 'Vui lòng chọn module di chuyển đến';
$u_lang['error_smodc'] = 'Vui lòng chọn hai khu vực khác nhau';
$u_lang['error_setup'] = 'Thông tin chưa đủ';
$u_lang['error_issetmod'] = 'Module không hợp lệ';
$u_lang['error_issetcat'] = 'Chủ đề không tồn tại';
$u_lang['error_0news'] = 'Hiện không có bài viết nào ở chủ đề gốc';
$u_lang['info_num'] = 'Hệ thống phát hiện thấy có <strong>%d</strong> bài viết (chưa kể bài viết trùng) ở chủ đề gốc của bạn, để bắt đầu hãy nhấp vào nút bến dưới. Lưu ý, nếu chủ đề của bạn chọn là chủ đề cha thì hệ thống sẽ di chuyển toàn bộ vài viết của các chủ đề con trong nó, một chủ đề cha bị xóa đồng nghĩa với chủ đề con cũng bị xóa';
$u_lang['info_end'] = 'Vui lòng tắt trình duyệt và thực hiện lại';
$u_lang['error_t1'] = 'Lưu ý: Bạn muốn di chuyển bài viết từ chủ đề cha vào chủ đề con và muốn xóa chủ đề cha sau khi di chuyển. Điều này là không thể chấp nhận được.';
$u_lang['error_so'] = 'Lỗi: Nguồn tin không tồn tại';
$u_lang['error_tp'] = 'Lỗi: Dòng sự kiện không tồn tại';
$u_lang['error_bl'] = 'Lỗi: Block tin không tồn tại';
$u_lang['move_sub'] = 'Chuyển cả chủ đề con';
$u_lang['move_sub_error'] = 'Bạn muốn xóa chủ đề sau khi di chuyển bài viết, chủ đề này có chủ đề con nhưng lại không di chuyển các bài viết trong đó, điều này là không thể chấp nhận';
$u_lang['move_to_block'] = 'Thêm vào block tin';
$u_lang['move_to_sources'] = 'Thêm vào nguồn tin';
$u_lang['move_to_topics'] = 'Thêm vào dòng sự kiện';
$u_lang['move_to_default'] = 'Để trống';

$u_lang['pross_s1'] = 'Đang thực hiện di chuyển bài viết từ chuyên mục <em>%s</em>';
$u_lang['pross_s2'] = 'Vui lòng không tắt trình duyệt và đợi, hệ thống sẽ tiếp tục công việc trong giây lát';
$u_lang['pross_s3'] = 'Hệ thống đã thực hiện xong việc di chuyển %s bài viết sang chuyên mục mới';
$u_lang['pross_s4'] = 'và xuất hiện lỗi nào đó ở %s bài viết';

?>