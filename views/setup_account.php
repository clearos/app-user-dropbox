<?php

/**
 * Setup Dropbox account view.
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
    lang('user_dropbox_step_1'),
    "<div id='step_1_help'>" .
    lang('user_dropbox_initialize_dropbox_setup_help') .
    "</div>" .
    "<div style='text-align: center; margin-top: 10px;'>" .
    "<span style='margin-right: 5px'>" .
    anchor_custom('https://db.tt/hydbtZaf', lang('user_dropbox_create_account'), 'high', array('target' => '_blank')) .
    "</spam>" .
    "<span style='margin-left: 5px;'>" .
    anchor_custom('user_dropbox/initialize', lang('user_dropbox_have_account')) .
    "</span>" .
    "</div>"
);
