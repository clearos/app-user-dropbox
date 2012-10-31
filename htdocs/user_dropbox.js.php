<?php

/**
 * Javascript helper for User Dropbox.
 *
 * @category   Apps
 * @package    User_Dropbox
 * @subpackage Javascript
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2011 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/user_dropbox/
 */

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('user_dropbox');
clearos_load_language('base');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type: application/x-javascript');

echo "

$(document).ready(function() {
    $('#sync_now').click(function(e) {
        e.preventDefault();
        $('#sync_now').hide();
        $('#sync_status').show();
        init_progress();
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
                console.log(data);
                $('#sync_status').html(data.errmsg);
                // Hack...just change the font to more 'alerty'
                $('#sync_status').addClass('theme-validation-error');
                window.setTimeout(init_progress, 5000);
            }

        },
        error: function(xhr, text, err) {
            // Don't display any errors if ajax request was aborted due to page redirect/reload
            if (xhr['abort'] == undefined)
                clearos_dialog_box('error', '" . lang('base_warning') . "', xhr.responseText.toString());
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
                $('#step_2_help').html('" . lang('user_dropbox_init_complete') . "');
                $('#sync_url').attr('href', data.url);
                $('#sync_status').hide();
                $('#sync_url_link').show();
            } else if (data.code == 1) {
                // No data yet...keep on whirlying
                window.setTimeout(log_progress, 2000);
            } else {
                console.log(data);
                $('#sync_status').html(data.errmsg);
                // Hack...just change the font to more 'alerty'
                $('#sync_status').addClass('theme-validation-error');
                window.setTimeout(log_progress, 2000);
            }

        },
        error: function(xhr, text, err) {
            // Don't display any errors if ajax request was aborted due to page redirect/reload
            if (xhr['abort'] == undefined)
                clearos_dialog_box('error', '" . lang('base_warning') . "', xhr.responseText.toString());
        }
    });
}
";
// vim: syntax=javascript ts=4
