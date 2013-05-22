<?php

/**
 * Dropbox status view.
 *
 * @category   apps
 * @package    user-dropbox
 * @subpackage views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/user_dropbox/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->load->helper('number');
$this->lang->load('base');
$this->lang->load('user_dropbox');

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo form_open('/user_dropbox');
echo form_header(lang('base_settings'));

echo field_toggle_enable_disable('enabled', $enabled, lang('base_status'));

echo field_info(
    'size',
    lang('dropbox_folder_size'),
    byte_format($size)
);
// Only show link if Dropbox folder doesn't exist (eg. size == 0)
if ($size == 0) {
    echo field_info(
        'link',
        lang('user_dropbox_url_link'),
        anchor_custom($url, lang('user_dropbox_authenticate_to_service'), 'high')
    );
}
echo field_button_set(
    array(
        form_submit_update('submit'),
        anchor_custom('user_dropbox/reset_account', lang('base_reset'))
    )
);

echo form_footer();
echo form_close();
