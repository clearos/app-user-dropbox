<?php

/**
 * Initialize dropbox view.
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

$this->lang->load('base');
$this->lang->load('user_dropbox');

///////////////////////////////////////////////////////////////////////////////
// Infobox
///////////////////////////////////////////////////////////////////////////////

echo infobox_highlight(
    lang('user_dropbox_step_2'),
    "<div id='step_2_help'>" .
    lang('user_dropbox_initialize_dropbox_sync_help') .
    "</div>" .
    "<div style='text-align: center; margin-top: 10px;'>" .
    anchor_custom(
        '/app/user_dropbox/initialize/sync',
        lang('user_dropbox_sync_now'),
        'high',
        array('id' => 'sync_now')
    ) .
    "</div>" .
    "<div id='sync_status' style='text-align: center; margin-top: 10px; display: none;'>" .
    loading('normal', lang('user_dropbox_starting_account_init')) .
    "</div>" .
    "<div id='sync_url_link' style='text-align: center; margin-top: 10px; display: none;'>" .
    anchor_custom('#', lang('user_dropbox_authentication'), 'high', array('id' => 'sync_url', 'target' => '_blank')) .
    "</div>"
);
