<?php

/**
 * User dropbox controller.
 *
 * @category   apps
 * @package    user-dropbox
 * @subpackage controllers
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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\base\Folder_Not_Found_Exception as Folder_Not_Found_Exception;
use \Exception as Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * User dropbox controller.
 *
 * @category   apps
 * @package    user-dropbox
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/user_dropbox/
 */

class User_Dropbox extends ClearOS_Controller
{
    /**
     * Userdropbox summary view.
     *
     * @return view
     */

    function index()
    {

        $username = $this->session->userdata('username');

        // Load libraries
        //---------------

        $this->lang->load('user_dropbox');
        $this->load->library('dropbox/Dropbox');
        $this->load->library('base/Folder', "/home/$username");
        $this->load->factory('mode/Mode_Factory');
        $this->load->factory('users/User_Factory', $username);

        $data = array();

        // Bail if root
        //-------------

        if ($username === 'root') {
            $this->page->view_form('root_warning', $data, lang('user_dropbox_app_name'));
            return;
        }

        $user_info = $this->user->get_info();
        $is_dropbox_user = ($user_info['plugins']['dropbox']) ? TRUE : FALSE;

        $url = $this->dropbox->get_user_url_link($username);

        if (!$is_dropbox_user) {
            $data['err'] = lang('dropbox_user_no_access');
            $this->page->view_form('no_access', $data, lang('user_dropbox_app_name'));
            return;
        }
        if (!$this->folder->exists()) {
            $data['err'] = lang('dropbox_missing_home_folder');
            $this->page->view_form('no_access', $data, lang('user_dropbox_app_name'));
            return;
        }
        if ($url != NULL && !$this->dropbox->get_running_state()) {
            $this->page->view_form('unavailable', $data, lang('user_dropbox_app_name'));
            return;
        }

        if ($url == NULL && !$this->dropbox->get_boot_state()) {
            $this->page->view_form('unavailable', $data, lang('user_dropbox_app_name'));
            return;
        }

        if ($url == NULL) {
            $this->page->view_form('setup_account', $data, lang('user_dropbox_app_name'));
            return;
        }

        // Handle form submit
        //---------------------
         
        $this->form_validation->set_policy('enabled', 'dropbox/Dropbox', 'validate_enabled', FALSE);
        $form_ok = $this->form_validation->run();

        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $disabled_users = $this->dropbox->get_disabled_users();
                $configured_users = $this->dropbox->get_configured_users();
                if (!$this->input->post('enabled') && !in_array($username, $disabled_users)) {
                    $disabled_users[] = $username;
                    $this->dropbox->set_disabled_users($disabled_users);
                    $pos = array_search($username, $configured_users);
                    unset($configured_users[$pos]);
                    $this->dropbox->set_configured_users($configured_users);
                    //$this->dropbox->restart();
                } else if ($this->input->post('enabled') && !in_array($username, $configured_users)) {
                    $configured_users[] = $username;
                    $this->dropbox->set_configured_users($configured_users);
                    $pos = array_search($username, $disabled_users);
                    unset($disabled_users[$pos]);
                    $this->dropbox->set_disabled_users($disabled_users);
                    //$this->dropbox->restart();
                }
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view
        //----------

        $configured = $this->dropbox->get_configured_users();
        if (in_array($username, $configured))
            $data['enabled'] = TRUE;
        else
            $data['enabled'] = FALSE;

        try {
            $data['size'] = $this->dropbox->get_folder_size($username);
        } catch (Folder_Not_Found_Exception $e) {
            $data['wait'] = lang('user_dropbox_waiting_confirmation');
            $data['size'] = 0;
        }
        $data['url'] = $url;
        $this->page->view_form('status', $data, lang('user_dropbox_app_name'));
    }

    /**
     * Userdropbox initialize view.
     *
     * @return view
     */

    function initialize()
    {
        // Load libraries
        //---------------

        $username = $this->session->userdata('username');

        $this->lang->load('user_dropbox');
        $this->load->library('dropbox/Dropbox');
        $this->load->factory('mode/Mode_Factory');
        $this->load->factory('users/User_Factory', $username);

        $data = array();

        $user_info = $this->user->get_info();
        $is_dropbox_user = ($user_info['plugins']['dropbox']) ? TRUE : FALSE;

        // Bail if root
        //-------------

        if ($username === 'root') {
            $this->page->view_form('root_warning', $data, lang('user_dropbox_app_name'));
            return;
        }

        if (!$is_dropbox_user) {
            $data['err'] = lang('dropbox_user_no_access');
            $this->page->view_form('no_access', $data, lang('user_dropbox_app_name'));
            return;
        }
        if ($this->dropbox->get_user_url_link($username) != NULL) {
            $this->page->set_message(lang('user_dropbox_waiting_confirmation'), 'info');
            redirect('/user_dropbox');
            return;
        }

        // Load view
        //----------

        $this->page->view_form('initialize', $data, lang('user_dropbox_app_name'));
    }

    /**
     * Ajax get init log controller
     *
     * @return JSON
     */

    function get_log()
    {
        clearos_profile(__METHOD__, __LINE__);

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');

        // Load libraries
        //---------------

        $this->lang->load('dropbox');
        $this->lang->load('user_dropbox');
        $this->load->library('dropbox/Dropbox');

        $username = $this->session->userdata('username');

        if ($username === 'root') {
            $this->page->view_form('root_warning', $data, lang('user_dropbox_app_name'));
            echo json_encode(array('code' => 500, 'errmsg' => lang('user_dropbox_invalid_account')));
            return;
        }
        try {

            $configured_users = $this->dropbox->get_configured_users();
            $disabled_users = $this->dropbox->get_disabled_users();

            $contents = $this->dropbox->get_user_log($username);
            $first = NULL;
            foreach ($contents as $line) {
                if (preg_match("/Another instance of Dropbox.*/", $line, $match))
                    continue;
                $first = $line;
                if (preg_match("/.*(https\S+)\s+.*/", $line, $match)) {
                    if (!in_array($username, $configured_users) && !in_array($username, $disabled_users)) {
                        $configured_users[] = $username;
                        $this->dropbox->set_configured_users($configured_users);
                    }
                    echo json_encode(array('code' => 0, 'url' => $match[1]));
                    return;
                }
            }
            if ($first == NULL)
                echo json_encode(array('code' => 1, 'errmsg' => lang('dropbox_no_data')));
            else
                echo json_encode(array('code' => 2, 'errmsg' => $line));
            
        } catch (Exception $e) {
            echo json_encode(array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }
    }

    /**
     * Ajax init account controller
     *
     * @return JSON
     */

    function init_account()
    {
        clearos_profile(__METHOD__, __LINE__);

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');

        $username = $this->session->userdata('username');

        // Load libraries
        //---------------

        $this->lang->load('dropbox');
        $this->lang->load('user_dropbox');
        $this->load->library('dropbox/Dropbox');
        $this->load->factory('mode/Mode_Factory');
        $this->load->factory('users/User_Factory', $username);

        if ($username === 'root') {
            echo json_encode(array('code' => 500, 'errmsg' => lang('user_dropbox_invalid_account')));
            return;
        }

        $user_info = $this->user->get_info();
        $is_dropbox_user = ($user_info['plugins']['dropbox']) ? TRUE : FALSE;

        if (!$is_dropbox_user) {
            echo json_encode(array('code' => 500, 'errmsg' => lang('dropbox_user_no_access')));
            return;
        }

        try {
            $this->dropbox->init_account($username);

            echo json_encode(array('code' => 0));
            
        } catch (Exception $e) {
            echo json_encode(array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }
    }

    /**
     * Reset account.
     *
     * @param String $confirm confirm reset
     *
     * @return view
     */

    function reset_account($confirm = NULL)
    {
        clearos_profile(__METHOD__, __LINE__);

        // Load dependencies
        //------------------

        $this->load->library('dropbox/Dropbox');
        $this->lang->load('dropbox');
        $this->lang->load('user_dropbox');

        $username = $this->session->userdata('username');

        if ($confirm != NULL) {
            try {
                $this->dropbox->reset_account($username);
                $this->page->set_message(lang('dropbox_reset_complete'), 'info');
                redirect('/user_dropbox');
                return;
            } catch (Engine_Exception $e) {
                $this->page->set_message(clearos_exception_message($e), 'warning');
            }
        }

        $this->page->view_form('confirm_reset', $data, lang('dropbox_reset_delete'));
    }
}
