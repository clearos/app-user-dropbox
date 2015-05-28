<?php

/**
 * Javascript helper for User Dropbox.
 * @category   apps
 * @package    user-dropbox
 * @subpackage javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2015 ClearFoundation
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

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('base');
clearos_load_language('user_dropbox');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type: application/x-javascript');

?>

var lang_warning = '<?php echo lang('base_warning'); ?>';
var lang_init_complete = '<?php echo lang('user_dropbox_init_complete'); ?>';

$(document).ready(function() {
    $('#sync_now').click(function(e) {
        e.preventDefault();
        $('#sync_now').hide();
        $('#sync_status').show();
        init_progress();
    });

    $('#sync_url').click(function(e) {
        window.location = '/app/user_dropbox';
    });
});

function init_progress()
{
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/user_dropbox/init_account',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token'),
        success: function(data) {
            if (data.code == 0) {
                window.setTimeout(log_progress, 3000);
            } else {
                $('#sync_status').html(data.errmsg);
                // Hack...just change the font to more 'alerty'
                $('#sync_status').addClass('theme-validation-error');
                window.setTimeout(init_progress, 5000);
            }

        },
        error: function(xhr, text, err) {
            // Don't display any errors if ajax request was aborted due to page redirect/reload
            if (xhr['abort'] == undefined)
                clearos_dialog_box('error', lang_warning, xhr.responseText.toString());
        }
    });
}

function log_progress()
{
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/user_dropbox/get_log',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token'),
        success: function(data) {
            if (data.code == 0) {
                $('#step_2_help').html(lang_init_complete);
                $('#sync_url').attr('href', data.url);
                $('#sync_status').hide();
                $('#sync_url_link').show();
            } else if (data.code == 1) {
                // No data yet...keep on whirlying
                window.setTimeout(log_progress, 2000);
            } else {
                $('#sync_status').html(data.errmsg);
                // Hack...just change the font to more 'alerty'
                $('#sync_status').addClass('theme-validation-error');
                window.setTimeout(log_progress, 2000);
            }

        },
        error: function(xhr, text, err) {
            // Don't display any errors if ajax request was aborted due to page redirect/reload
            if (xhr['abort'] == undefined)
                clearos_dialog_box('error', lang_warning, xhr.responseText.toString());
        }
    });
}
// vim: syntax=javascript ts=4
