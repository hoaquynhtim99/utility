/* *
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

function nv_rating_send(id)
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendgift&id=' + id + '&who_send=' + who_send.value + '&who_receive=' + who_receive.value + '&email_receive=' + email_receive.value + '&body=' + encodeURIComponent(body), '', 'resultgift');
}