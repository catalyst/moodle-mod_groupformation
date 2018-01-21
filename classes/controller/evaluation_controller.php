<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package mod_groupformation
 * @@author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/groups_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/template_builder.php');

class mod_groupformation_evaluation_controller {

    /** @var mod_groupformation_storage_manager The manager of activity data */
    private $store = null;

    /** @var mod_groupformation_user_manager The manager of user data */
    private $usermanager = null;

    /** @var int ID of module instance */
    private $groupformationid = null;

    /**
     * mod_groupformation_evaluation_controller constructor.
     * @param $groupformationid
     */
    public function __construct($groupformationid) {
        $this->groupformationid = $groupformationid;

        $this->store = new mod_groupformation_storage_manager ($groupformationid);
        $this->usermanager = new mod_groupformation_user_manager ($groupformationid);
    }

    /**
     * Renders for no evaluation
     *
     * @param string $caption
     * @return array
     */
    public function no_evaluation($caption = 'no_evaluation_text') {

        $assigns = array();

        $assigns['eval_show_text'] = true;
        $assigns['eval_text'] = get_string($caption, 'groupformation');
        $json = json_encode(null);
        $assigns['json_content'] = $json;

        return $assigns;
    }

    /**
     * Returns eval array for user.
     *
     * @param $userid
     * @return array
     */
    public function get_eval($userid) {
        $courseusers = $this->store->get_users();

        if (!count($courseusers) >= 2) {
            $courseusers = array();
        }

        $groupusers = array();

        $cc = new mod_groupformation_criterion_calculator($this->groupformationid);

        $this->usermanager->set_evaluation_values($userid);

        return $cc->get_eval($userid, $groupusers, $courseusers);
    }

    /**
     * Load info
     *
     * @return array
     */
    public function load_info() {
        global $USER;

        $assigns = array();

        $userid = $USER->id;

        if ($this->store->ask_for_topics()) {

            $assigns = array_merge($this->no_evaluation(), $assigns);

        } else if (!$this->usermanager->has_answered_everything($userid)) {

            $assigns = array_merge($this->no_evaluation('no_evaluation_ready'), $assigns);

        } else {

            $eval = $this->get_eval($userid);

            if (is_null($eval) || count($eval) == 0) {

                $assigns = array_merge($this->no_evaluation(), $assigns);

            } else {

                $assigns['eval_text'] = false;
                $assigns['eval_show_text'] = false;
                $json = json_encode($eval);
                $assigns['json_content'] = $json;

            }
        }

        return $assigns;
    }

}