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
 * Controller for analysis view
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/storage_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/user_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/template_builder.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/util.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/advanced_job_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/state_machine.php');

/**
 * Class mod_groupformation_analysis_controller
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupformation_analysis_controller {

    /** @var int ID of module instance */
    private $groupformationid = null;

    /** @var mod_groupformation_storage_manager The manager of activity data */
    private $store = null;

    /** @var mod_groupformation_user_manager The manager of user data */
    private $usermanager = null;

    /** @var int ID of course module*/
    public $cmid = null;

    /** @var mod_groupformation_state_machine Activity state machine */
    private $statemachine;

    /**
     * Creates instance of analysis controller
     *
     * @param int $groupformationid
     * @param stdClass $cm
     * @throws dml_exception
     */
    public function __construct($groupformationid, $cm) {
        $this->cmid = $cm->id;
        $this->groupformationid = $groupformationid;
        $this->store = new mod_groupformation_storage_manager($groupformationid);
        $this->usermanager = new mod_groupformation_user_manager($groupformationid);
        $this->statemachine = new mod_groupformation_state_machine($groupformationid);
        $this->view = new mod_groupformation_template_builder();
        $this->determine_status($cm);
    }

    /**
     * Triggers questionnaire
     *
     * @param bool $switcher
     * @throws dml_exception
     */
    public function trigger_questionnaire($switcher) {
        switch ($switcher) {
            // Sets start time of questionnaire to now.
            case 1:
                $this->store->open_questionnaire();
                $this->statemachine->prev();
                break;

            // Sets end time of questionnaire to now.
            case -1:
                $this->store->close_questionnaire();
                $this->statemachine->next();
                break;
        }
    }

    /**
     * Determine status variables
     *
     * @param stdClass $cm
     * @throws dml_exception
     */
    public function determine_status($cm) {
        $ajm = new mod_groupformation_advanced_job_manager();
        $job = $ajm::get_job($this->groupformationid);
        if (is_null($job)) {
            $groupingid = ($cm->groupmode != 0) ? $cm->groupingid : 0;
            $ajm::create_job($this->groupformationid, $groupingid);
        }
    }

    /**
     * Returns activity statistics
     *
     * @return array
     * @throws dml_exception
     */
    public function load_statistics() {
        $assigns = array();

        $usermanager = $this->usermanager;

        $stats = array();

        $studentcount = count(mod_groupformation_util::get_users($this->groupformationid));

        $stats [] = $studentcount;

        $started = $usermanager->get_started();
        $startedcount = count($started);

        $stats [] = $startedcount;

        $completed = $usermanager->get_completed();
        $completedcount = count($completed);

        $stats [] = $completedcount;

        $nomissinganswers = $usermanager->get_completed_by_answer_count();
        $nomissingcount = count($nomissinganswers);

        $stats [] = $nomissingcount;

        $questionnairestats = $stats;

        $assigns['statistics_enrolled'] = $questionnairestats [0];
        $assigns['statistics_processed'] = $questionnairestats [1];
        $assigns['statistics_submitted'] = $questionnairestats [2];
        $assigns['statistics_submitted_complete'] = $questionnairestats [3];

        return $assigns;
    }

    /**
     * Returns activity infos
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function load_info() {
        $activitytime = $this->store->get_time();

        $starttime = $activitytime ['start'];
        if (intval($activitytime ['start_raw']) == 0) {
            $starttime = get_string('no_time', 'groupformation');
        }

        $endtime = $activitytime ['end'];
        if (intval($activitytime ['end_raw']) == 0) {
            $endtime = get_string('no_time', 'groupformation');
        }

        $buttoncaption = get_string('activity_start', 'groupformation');
        if ($this->statemachine->get_state(true) == 0) {
            $buttoncaption = get_string('activity_end', 'groupformation');
        }

        $buttondisabled = "";
        if ($this->statemachine->get_state(true) >= 2) {
            $buttondisabled = "disabled";
        }

        $buttonvalue = 1;
        if ($this->statemachine->get_state(true) == 0) {
            $buttonvalue = -1;
        }

        $assigns = array();

        $assigns['button'] = array(
                'type' => 'submit',
                'name' => 'questionnaire_switcher',
                'value' => $buttonvalue,
                'state' => $buttondisabled,
                'text' => $buttoncaption
        );

        $assigns['info_teacher'] = mod_groupformation_util::get_info_text_for_teacher("analysis");
        $assigns['analysis_time_start'] = $starttime;
        $assigns['analysis_time_end'] = $endtime;
        $assigns['analysis_status'] = get_string('analysis_status_' . ($this->statemachine->get_state()), 'groupformation');

        if ($this->statemachine->get_state(true) >= 6) {

            $buttoncaption = get_string('close_questionnaire', 'groupformation');
            if ($this->statemachine->get_state(true) == 6) {
                $buttoncaption = get_string('re-open_questionnaire', 'groupformation');
            }

            $buttonvalue = 1;
            if ($this->statemachine->get_state(true) == 7) {
                $buttonvalue = -1;
            }

            $assigns['reopen_button'] = array(
                    'type' => 'submit',
                    'name' => 'questionnaire_switcher',
                    'value' => $buttonvalue,
                    'state' => "",
                    'text' => $buttoncaption
            );

        }

        return $assigns;
    }

    /**
     * Returns topic statistics
     *
     * @return array
     * @throws dml_exception
     */
    public function load_topic_statistics() {
        $assigns = array();

        $topics = $this->store->ask_for_topics();
        $options = null;
        if ($topics) {
            $xmlcontent = $this->store->get_knowledge_or_topic_values('topic');
            $xmlcontent = '<?xml version="1.0" encoding="UTF-8" ?> <OPTIONS> ' . $xmlcontent . ' </OPTIONS>';
            $options = mod_groupformation_util::xml_to_array($xmlcontent);
        }

        $topics = array();

        foreach ($options as $key => $option) {
            $topic = new stdClass();
            $topic->name = $option;
            $topic->score = $this->usermanager->get_topic_score($key + 1);

            $topics[] = $topic;
        }

        $assigns['topics'] = $topics;

        return $assigns;
    }
}