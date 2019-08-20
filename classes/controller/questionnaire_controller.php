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
 * Controller for questionnaire view
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/likert_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/topic_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/basic_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/range_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/knowledge_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/binquestion_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/dropdown_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/freetext_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/multiselect_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/number_question.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/question_table.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/user_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/storage_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/util.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/define_file.php');
require_once($CFG->dirroot . '/mod/groupformation/locallib.php');

/**
 * Class mod_groupformation_questionnaire_controller
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupformation_questionnaire_controller {

    /** @var int The id of the groupformation activity */
    private $groupformationid = null;

    /** @var mod_groupformation_storage_manager instance of storage manager */
    private $store = null;

    /** @var mod_groupformation_user_manager The manager of user data */
    private $usermanager = null;

    /** @var mod_groupformation_groups_manager The manager of groups data */
    private $groupsmanager = null;

    /** @var int ID of user */
    public $userid;

    /** @var int The id of the course module */
    public $cmid;

    /** @var bool Flag for highlighting missing answers */
    private $highlightmissinganswers = false;

    /** @var int Position of category */
    public $categoryposition = 0;

    /** @var string previous category */
    public $previouscategory;

    /** @var string current category */
    public $currentcategory;

    /** @var int current direction */
    public $direction;

    /** @var array Categories of questionnaire */
    public $categories = array();

    /**
     * mod_groupformation_questionnaire_controller constructor.
     *
     * @param int $groupformationid
     * @param int $userid
     * @param string $previouscategory
     * @param $currentcategory
     * @param $direction
     * @param int $cmid
     * @throws dml_exception
     */
    public function __construct($groupformationid, $userid, $cmid, $currentcategory, $direction) {
        $this->groupformationid = $groupformationid;
        $this->userid = $userid;
        $this->cmid = $cmid;

        $this->store = new mod_groupformation_storage_manager ($groupformationid);
        $this->usermanager = new mod_groupformation_user_manager ($groupformationid);
        $this->groupsmanager = new mod_groupformation_groups_manager ($groupformationid);

        $previouscategory = "";
        if ($direction == 0) {
            $previouscategory = "";
        } else if ($direction == 1) {
            $previouscategory = $currentcategory;
            $currentcategory = $this->store->get_next_category($currentcategory);
        } else if ($direction == -1) {
            $previouscategory = $currentcategory;
            $currentcategory = $this->store->get_previous_category($previouscategory);
        }

        $this->direction = $direction;
        $this->previouscategory = $previouscategory;
        $this->currentcategory = $currentcategory;

        $this->categories = $this->store->get_categories();
        $this->categoryposition = $this->store->get_position($currentcategory);
    }

    /**
     * Triggers going a category page back
     */
    public function go_back() {
        $this->categoryposition = max($this->categoryposition - 1, 0);
    }

    /**
     * Regulates not going on and highlighting missing answers
     */
    public function not_go_on() {
        $this->categoryposition = max($this->categoryposition - 1, 0);
        $this->highlightmissinganswers = true;
    }

    /**
     * Returns percent of progress in questionnaire
     *
     * @param string $category
     * @return number
     * @throws dml_exception
     */
    public function get_percent($category = null) {
        if (!is_null($category)) {
            $categories = $this->store->get_categories();
            $pos = array_search($category, $categories);
            return 100.0 * ((1.0 * $pos) / count($categories));
        }

        $total = 0;
        $sub = 0;
        $temp = 0;

        $numbers = $this->store->get_numbers($this->categories);

        foreach ($numbers as $num) {
            if ($num != 0) {
                $total++;
                if ($temp < $this->categoryposition) {
                    $sub++;
                }
            }
            $temp++;
        }

        return ($sub / $total) * 100;
    }

    /**
     * Returns whether there is a next category or not
     *
     * @return boolean
     */
    public function has_next() {
        return ($this->categoryposition != -1 && $this->categoryposition < count($this->categories));
    }

    /**
     * Returns question in current language or possible default language
     *
     * @param int $i
     * @param int $version
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_question($i, $version) {
        $category = $this->category;
        $lang = get_string('language', 'groupformation');

        $record = $this->store->get_catalog_question($i, $category, $lang, $version);

        if (empty ($record)) {
            if ($lang != 'en') {
                $record = $this->store->get_catalog_question($i, $category, $lang, $version);
            } else {
                $lang = $this->store->get_possible_language($category);
                $record = $this->store->get_catalog_question($i, $category, $lang, $version);
            }
        }

        return $record;
    }

    /**
     * Returns whether current category is 'topic' or not
     *
     * @return boolean
     * @throws dml_exception
     */
    public function is_topics() {
        return $this->categoryposition == $this->store->get_position('topic');
    }

    /**
     * Returns whether current category is 'knowledge' or not
     *
     * @return boolean
     * @throws dml_exception
     */
    public function is_knowledge() {
        return $this->categoryposition == $this->store->get_position('knowledge');
    }

    /**
     * Returns whether current category is 'binquestion' or not
     *
     * @return boolean
     * @throws dml_exception
     */
    public function is_binquestion() {
        return $this->categoryposition == $this->store->get_position('binquestion');
    }

    /**
     * Returns whether current category is 'points' or not
     *
     * @return boolean
     * @throws dml_exception
     */
    public function is_points() {
        return $this->categoryposition == $this->store->get_position('points');
    }

    /**
     * Returns questions
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_next_questions() {
        $category = $this->category;
        if ($this->categoryposition != -1) {

            $questions = array();
            //$multiselect = $this->store->get_binquestion_multiselect();
            $multiselect = false;

            $hasanswer = $this->usermanager->has_answers($this->userid, $category);


            if ($this->is_binquestion()){
                $temp = $this->store->get_knowledge_or_topic_values($category);
                $xmlcontent = '<?xml version="1.0" encoding="UTF-8" ?> <OPTIONS> ' . $temp . ' </OPTIONS>';
                $options = mod_groupformation_util::xml_to_array($xmlcontent);
                $questionid = 1;

                if ($hasanswer){
                    $answer = $this->usermanager->get_single_answer(
                        $this->userid, $category, $questionid);
                    if ($answer == false){
                        $answer = -1;
                    }
                } else {
                    $answer = -1;
                }
                $question = $this->store->get_binquestion_text();

                if ($multiselect){
                    $name = 'mod_groupformation_multiselect_question';
                } else {
                    $name = 'mod_groupformation_' . $category . '_question';
                }
                $questionobj = new $name($category, $questionid, $question, $options, $answer);
                $questions[] = $questionobj;
            } else if ($this->is_knowledge() || $this->is_topics()) {
                $temp = $this->store->get_knowledge_or_topic_values($category);
                $xmlcontent = '<?xml version="1.0" encoding="UTF-8" ?> <OPTIONS> ' . $temp . ' </OPTIONS>';
                $values = mod_groupformation_util::xml_to_array($xmlcontent);

                $options = array(
                        100 => get_string('excellent', 'groupformation'), 0 => get_string('none', 'groupformation'));

                $position = 1;
                $questionsfirst = array();
                $answerposition = array();

                $i = 1;
                foreach ($values as $value) {

                    if ($hasanswer) {
                        $answer = $this->usermanager->get_single_answer(
                                $this->userid, $category, $position);
                        if ($answer == false) {
                            $answer = -1;
                        }
                        $answerposition[$answer] = $position - 1;
                        $position++;
                    } else {
                        $answer = -1;
                    }

                    $questionid = $i;
                    $question = $value;

                    $name = 'mod_groupformation_' . $category . '_question';
                    $questionobj = new $name($category, $questionid, $question, $options, $answer);

                    $questionsfirst[] = $questionobj;
                    $i++;
                }

                $l = count($answerposition);

                if ($l > 0 && $this->categoryposition == $this->store->get_position('topic')) {
                    // Topics are rated by users as: the topmost = most wanted=rating value highest number.
                    // Therefore here we sort them accordingly top downwards by rating.
                    for ($k = $l; $k >= 1; $k--) {
                        $h = $questionsfirst[$answerposition[$k]];
                        $h->set_answer($answerposition[$k]);
                        $questions[] = $h;
                    }
                } else {
                    $questions = $questionsfirst;
                }
            } else {
                if ($this->is_points()) {

                    $records = $this->store->get_questions_randomized_for_user($category, $this->userid);

                    foreach ($records as $record) {

                        $type = $record->type;

                        $options = array(
                                $this->store->get_max_points() => get_string('excellent', 'groupformation'),
                                0 => get_string('bad', 'groupformation'));

                        $answer = $this->usermanager->get_single_answer($this->userid, $category, $record->questionid);
                        if ($answer == false) {
                            $answer = -1;
                        }

                        $questionid = $record->questionid;
                        $question = $record->question;

                        $name = 'mod_groupformation_' . $type . '_question';
                        $questionobj = new $name($category, $questionid, $question, $options, $answer);

                        $questions [] = $questionobj;
                    }

                } else {

                    $records = $this->store->get_questions_randomized_for_user($category, $this->userid);

                    foreach ($records as $record) {

                        $type = $record->type;
                        $questionid = $record->questionid;
                        $question = $record->question;

                        $temp = '<?xml version="1.0" encoding="UTF-8" ?> <OPTIONS> ' . $record->options . ' </OPTIONS>';
                        $options = mod_groupformation_util::xml_to_array($temp);

                        $answer = $this->usermanager->get_single_answer($this->userid, $category, $record->questionid);

                        $name = 'mod_groupformation_' . $type . '_question';
                        $questionobj = new $name($category, $questionid, $question, $options, $answer);

                        $questions [] = $questionobj;
                    }

                }
            }

            return $questions;
        }
    }

    /**
     * Returns action buttons for questionnaire page
     *
     * @return string
     * @throws coding_exception
     */
    public function get_action_buttons() {
        $s = '<div class="grid">';
        $s .= '    <div class="col_m_100 questionaire_button_row">';
        $s .= '        <button type="submit" name="direction" value="-1" class="gf_button gf_button_pill gf_button_small">';
        $s .= get_string('previous');
        $s .= '        </button>';
        $s .= '        <button type="submit" name="direction" value="1" class="gf_button gf_button_pill gf_button_small">';
        $s .= get_string('next');
        $s .= '        </button>';
        $s .= '    </div>';
        $s .= '</div>';
        return $s;
    }

    /**
     * Returns navigation bar
     *
     * @param string $activecategory
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function get_navbar($activecategory = null) {
        $context = context_module::instance($this->cmid);

        $tempcategories = $this->store->get_categories();

        $categories = array();

        foreach ($tempcategories as $category) {

            if ($this->store->get_number($category) > 0) {
                $categories [] = $category;
            }

        }

        $s = '<div id="questionaire_navbar">';
        $s .= '<ul id="accordion">';
        $prevcomplete = !$this->store->all_answers_required();

        foreach ($categories as $category) {

            $url = new moodle_url ('questionnaire_view.php', array(
                    'id' => $this->cmid, 'category' => $category));
            $positionactivecategory = $this->store->get_position($activecategory);
            $positioncategory = $this->store->get_position($category);

            $beforeactive = ($positioncategory <= $positionactivecategory);
            $class = 'no-active';
            if (has_capability('mod/groupformation:editsettings', $context) || $beforeactive || $prevcomplete) {
                $class = '';
            }

            $current = ($activecategory == $category) ? 'current' : 'accord_li';

            $s .= '<li class="' . $current . '">';
            $s .= '<a class="' . $class . '"  href="' . $url . '">';
            $s .= '<span>';
            $s .= $positioncategory + 1;
            $s .= '</span>';
            $s .= get_string('category_' . $category, 'groupformation');
            $s .= '</a>';
            $s .= '</li>';

        }

        $s .= '</ul>';
        $s .= '</div>';
        return $s;
    }

    /**
     * Returns table with questions
     *
     * @param array $questions
     * @param unknown $percent
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_questions($questions, $percent) {

        $s = '<form style="width:100%; float:left;" action="';
        $s .= htmlspecialchars($_SERVER ["PHP_SELF"]);
        $s .= '" method="post" autocomplete="off" class="groupformation_questionnaire">';

        if (!is_null($questions) && count($questions) != 0) {

            $category = $this->category;

            $table = new mod_groupformation_question_table ($category);

            // Here is the actual category and groupformationid is sent hidden.
            $s .= '<input type="hidden" name="category" value="' . $category . '"/>';

            $s .= '<input type="hidden" name="percent" value="' . $percent . '"/>';

            $s .= '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';

            $activityid = optional_param('id', $this->groupformationid, PARAM_INT);

            $s .= '<input type="hidden" name="id" value="' . $activityid . '"/>';

            $s .= '<h4 class="view_on_mobile">';
            $s .= get_string('category_' . $category, 'groupformation');
            $s .= '</h4>';

            // Print the header of a table or unordered list.
            $addon = '';
            if ($category == 'binquestion' && false && $this->store->get_binquestion_multiselect()){
                $addon = '_multi';
            }
            $s .= $table->get_header($addon); // TODO ändere title für multiselect

            foreach ($questions as $q) {
                $s .= $q->get_html($this->highlightmissinganswers, $this->store->all_answers_required());

            }

            // Print the footer of a table or unordered list.
            $s .= $table->get_footer();
        }

        $s .= $this->get_action_buttons();

        $s .= '</form>';
        return $s;
    }

    /**
     * Returns progress bar
     *
     * @param float $percent
     * @return string
     */
    public function get_progressbar($percent) {
        $s = '<div class="progress">';

        $s .= '    <div class="questionaire_progress-bar" role="progressbar" aria-valuenow="' . $percent .
                '" aria-valuemin="0" aria-valuemax="100" style="width:' . $percent . '%">';
        $s .= '    </div>';

        $s .= '</div>';

        return $s;
    }

    /**
     * Prints participant code for user
     */
    public function get_participant_code() {
        $s = '<div class="participantcode">';

        $participantcode = $this->usermanager->get_participant_code($this->userid);

        if (!is_null($participantcode)) {
            $s .= get_string('participant_code_footer', 'groupformation');
            $s .= ': ';
            $s .= $participantcode;
        }

        $s .= '</div>';
        return $s;
    }

    /**
     * Saves answers for user
     *
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public function save_answers() {
        $category = $this->previouscategory;

        if (!in_array($category, $this->categories)) {
            return false;
        }

        $go = true;
        $number = $this->store->get_number($category);

        if ($category == 'knowledge' || $category == 'topic') {
            for ($i = 1; $i <= $number; $i++) {
                $question = new stdClass();
                $question->type = $category;
                $question->questionid = $i;
                $this->handle_answer($this->userid, $category, $question);
            }
        } else if ($category == 'binquestion'){
            $question = new stdClass();
            $question->type = $category;
            $question->questionid = 1;
            $this->handle_answer($this->userid, $category, $question);
        } else {
            $questions = $this->store->get_questions_randomized_for_user($category, $this->userid);

            foreach ($questions as $question) {
                $this->handle_answer($this->userid, $category, $question);
            }
        }

        // There is only one question, if it's a binquestion.
        if ($category == 'binquestion' && $number > 0){
            $number = 1;
        }
        if ($this->store->all_answers_required() && $this->usermanager->get_number_of_answers(
                $this->userid, $category) != $number) {
            $go = false;
        }

        $this->store->userstatemachine->change_state($this->userid, "answer");

        return $go;
    }

    /**
     * Handles answers
     *
     * @param int $userid
     * @param string $category
     * @param stdClass $question
     * @throws dml_exception
     */
    public function handle_answer($userid, $category, $question) {

        $type = $question->type;
        $questionid = $question->questionid;
        $name = 'mod_groupformation_' . $type . '_question';
        if ($type == 'binquestion'){
            $multiselect = false && $this->store->get_binquestion_multiselect();
            if ($multiselect){
                $name = 'mod_groupformation_multiselect_question';
            }
        }
        $questionobj = new $name($category, $questionid);
        $answer = $questionobj->read_answer();
        if (is_null($answer)) {
            return;
        }

        if ($answer[0] == "save") {
            $this->usermanager->save_answer($userid, $category, $answer[1], $questionid);
        } else if ($answer[0] == "delete") {
            $this->usermanager->delete_answer($userid, $category, $questionid);
        }
    }

    /**
     * Loads questionnaire page content
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function load_questionnaire_page() {
        $assigns = array();

        $context = context_module::instance($this->cmid);

        if (groupformation_get_current_questionnaire_version() > $this->store->get_version()) {
            $assigns['archived_alert'] = '<div class="alert">' . get_string('questionnaire_outdated', 'groupformation') . '</div>';
        }

        if ($this->has_next()) {
            $category = $this->categories[$this->categoryposition];
            $this->category = $category;

            $isteacher = has_capability('mod/groupformation:editsettings', $context);
            $hasgroup = $this->groupsmanager->has_group($this->userid, true);

            $state = $this->store->statemachine->get_state();
            $userstate = $this->store->userstatemachine->get_state($this->userid);
            if ($isteacher) {
                $s = '<div class="alert">';
                $s .= get_string('questionnaire_preview', 'groupformation');
                $s .= '</div>';
                $assigns['preview_alert'] = $s;
            } else if (in_array($state, array('q_open')) &&
                    !in_array($userstate, array("started", "consent_given", "p_code_given", "answering"))) {
                $s = '<div class="alert" id="commited_view">';
                $s .= get_string('questionnaire_committed', 'groupformation');
                $s .= '</div>';
                $assigns['committed_alert'] = $s;
            } else if (in_array($state, array('q_reopened')) && $hasgroup) {
                $s = '<div class="alert" id="commited_view">';
                $s .= get_string('questionnaire_committed', 'groupformation');
                $s .= '</div>';
                $assigns['committed_alert'] = $s;
            } else if (!in_array($state, array('q_open', 'q_reopened'))) {
                $s = '<div class="alert" id="commited_view">';
                $s .= get_string('questionnaire_closed', 'groupformation');
                $s .= '</div>';
                $assigns['committed_alert'] = $s;
            }

            $percent = $this->get_percent($category);

            $hasparticipantcode = $this->usermanager->has_participant_code($this->userid);

            if (mod_groupformation_data::ask_for_participant_code() && $hasparticipantcode && !$isteacher) {
                $assigns['participant_code'] = $this->get_participant_code();
            }

            $assigns['navbar'] = $this->get_navbar($category);
            $assigns['progressbar'] = $this->get_progressbar($percent);

            $questions = $this->get_next_questions();

            $assigns['questions'] = $this->get_questions($questions, $percent);

        } else {

            if ($this->usermanager->has_answered_everything($this->userid)) {
                $this->usermanager->set_evaluation_values($this->userid);
            }

            $returnurl = new moodle_url ('/mod/groupformation/analysis_view.php', array(
                    'id' => $this->cmid, 'do_show' => 'analysis'));
            redirect($returnurl);
        }

        return $assigns;
    }
}