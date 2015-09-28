﻿<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * German strings for newmodule
 *
 * @package mod_groupformation
 * @author Nora Wester
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *         
 */
defined ( 'MOODLE_INTERNAL' ) || die ();

$string ['language'] = 'en';
$string ['modulename'] = 'Group formation';
$string ['modulenameplural'] = 'Group formations';
$string ['modulename_help'] = 'ACHTUNG: BETA-Version. Nur für Dozenten in Zusammenarbeit mit Projekt MoodlePeers  gedacht. The groupformation plugin generates groups of participants based on a questionaire answers.';
$string ['password_wrong'] = 'wrong password';
$string ['groupformationfieldset'] = 'Custom example fieldset';
$string ['groupformationname'] = 'Group formation';
$string ['groupformationname_help'] = 'ToolTip Group formation';
$string ['groupformation'] = 'Group formation';
$string ['pluginadministration'] = 'Group formation administration';
$string ['pluginname'] = 'groupformation';
$string ['nogroupformation'] = 'No group formation';
$string ['groupnameexists'] = 'This group already exists.';
$string ['generategroups'] = 'Generation of groups';
$string ['namingschema'] = 'Naming scheme';
$string ['userpergroup'] = 'How many users in a group?';
$string ['notOpen'] = 'Submission is closed.';
$string ['continueTheForm'] = 'Continue';
$string ['completeTheForm'] = 'Complete questionaire';
$string ['alreadySubmitted'] = 'Already submitted';
$string ['overview'] = 'Overview';
$string ['generategroups'] = "Generate groups";
$string ['edit_param'] = 'Edit';
$string ['editparam'] = 'Edit parameters';
$string ['nochangespossible'] = 'The questionaire has been answered already. 
You can only change the maximum number of members or maximum number of groups. Further changes will not be saved.';
$string ['availability_nochangespossible'] = 'The questionaire has been answered already. You cannot change the availability anymore.';
$string ['scenario'] = 'Scenario';
$string ['scenarioLabel'] = '';
$string ['scenario_description'] = 'Please choose the most suitable scenario for the group formation.';
$string ['scenarioInfo'] = 'The three scenarios differ in the way how questionaaire items influence the group formation.  
			For project teams it considers prior knowledge and traits of the group members to amend each other while motivation (level) and personal targets should be as similar as possible.    
			For homework groups it optimizes each group to have the best possible prerequisites for collaborative learning.
		    For presentation groups the main aspect is a mutual interest in the same selected (and assigend) topic to work on.';
$string ['groupformationsettings'] = 'Group formation settings';
$string ['scenario_projectteams'] = 'Project teams';
$string ['scenario_projectteams_description'] = 'Project teams work intensively together to finish a project (e.g. conduct a study, delivery of a report, etc.). Often, duties and tasks can be split among the team members. Consequently it is beneficial to have a mixture of amending comptencies in the team. Usually, such a collective work result is graded with a equal group mark for all members. Thus, groupformation aims for similar motivation and similar objectives (beside the prerequisites).';
$string ['scenario_homeworkgroups'] = 'Homework groups';
$string ['scenario_homeworkgroups_description'] = 'Homework groups complete (smaller) assignments in regular intervals (often weekly) as a preperation for examination. Even though the assigment tasks (often called exercises, practice, control questions or homework) are principally subdividable among group members, this is not intended as with the final examination each member will be graded individually and needs to be able to solve all tasks alone. Consequently, groupformation aims for diverse prior knowledge and diverse learning styles that benefit from each other.';
$string ['scenario_presentationgroups'] = 'Presentation groups';
$string ['scenario_presentationgroups_description'] = 'Presentation groups work together for a relatively short time period to finish a presentation (usually to be held in class). Often in the beginning a specific sub-task is assigned to (or selected by) each student, individually worked on, and in the end re-assembled to a complete presentation. Grading is usually done for the perforance of the whole group together. Therefore, groupformation aims primarily for mutual interest in the same topic.';
$string ['time'] = 'Time';
$string ['topics'] = 'Topics';
$string ['topics_dummy'] = 'Topic ';
$string ['knowledge_dummy'] = 'Example ';
$string ['topics_description'] = 'I want to define topics';
$string ['topics_description_extended'] = 'Please list topics for the students to choose from. Students are supposed to sort the topics regarding their interests. Take a look at the preview on the right hand side.';
$string ['topics_question'] = 'Please sort the following topics regarding your personal interests. Start with your favorite topic.';
$string ['topicchoice'] = 'Topic selection';
$string ['useOneLineForEachTopic'] = 'Use one line for each topic';
$string ['knowledge'] = 'Knowledge';
$string ['knowledge_description'] = 'I want to include knowledge questions';
$string ['knowledge_description_extended'] = 'Please list knowledge areas in which students should assess themselves. 
	Take a look at the preview on the right hand side.';
$string ['knowledge_info_presentation'] = 'Please list knowledge areas in which students should assess themselves. 
	Take a look at the preview on the right hand side. 
	According to your selection "presentation groups" prior knowledge will be varied within each group (low priority behind topics)';
$string ['knowledge_info_homework'] = 'Please list knowledge areas in which students should assess themselves. 
	Take a look at the preview on the right hand side. 
	According to your selection "homework groups" prior knowledge will be varied within each group.';
$string ['knowledge_info_project'] = 'Please list knowledge areas in which students should assess themselves. 
	Take a look at the preview on the right hand side. 
	According to your selection "project teams" prior knowledge (areas) will be varied within each group, but the level of knowledge is desired to be similar.';
$string ['knowledgeChoice'] = 'Knowledge';
$string ['add_line'] = 'Add line';
$string ['remove_line'] = 'Remove line';
$string ['preview'] = 'Preview:';
$string ['input'] = 'Input:';
$string ['knowledge_question'] = 'How much do you know about the following topics?';
$string ['knowledge_scale'] = '0 = no knowledge, 100 = big knowledge';
$string ['groupoptions'] = 'Group settings';
$string ['groupoption_description'] = 'Group settings description';
$string ['groupoption_help'] = 'This parameter can be optimized after the submission of the questionaires.';
$string ['maxmembers'] = 'Max. number of group members';
$string ['maxgroups'] = 'Max. number of groups';
$string ['maxpoints'] = 'Max. points';
$string ['evaluationmethod_description'] = 'How do you evaluate the work?';
$string ['groupname'] = 'Grouping name';
$string ['groupname_help'] = 'The chosen grouping name will be a prefix for the generated groups. The scheme is <grouping name>_XXX, where XXX is the number of the group. ';
$string ['grades'] = 'Grades';
$string ['points'] = 'Points';
$string ['justpass'] = 'Just pass';
$string ['noevaluation'] = 'No evaluation';
$string ['useOneLineForEachKnowledge'] = 'Use one line for each topic';
$string ['cannotloadxml'] = 'Cannot load XML file';
$string ['scenario_error'] = 'Please choose a scenario';
$string ['maxmembers_error'] = 'Please choose a number greater than 0';
$string ['maxgroups_error'] = 'Please choose a number greater than 0';
$string ['maxpoints_error'] = 'Please choose a number between 1 and 100';
$string ['groupname_error'] = 'Please choose a name with less than 100 characters.';
$string ['evaluationmethod_error'] = 'Please choose an evaluation method.';
$string ['choose_scenario'] = 'Choose scenario';
$string ['choose_number'] = 'Choose number';
$string ['choose_evaluationmethod'] = 'Choose method';
$string ['evaluation_point_info'] = 'indicate the maximum available points';
$string ['students_enrolled_info'] = 'Students are enrolled in this course';
$string ['groupSettingsInfo'] = 'You have choosen to define topics. Thereof the number of groups and their size will be calculated, as you can see below.';
$string ['analyse'] = 'Analysis';
$string ['questionaire_not_started'] = 'The questionaire is ready.';
$string ['questionaire_press_to_begin'] = 'Press "Next" to begin.';
$string ['questionaire_not_submitted'] = 'Your answers are not submitted yet.';
$string ['questionaire_press_continue_submit'] = 'Press "Edit" to continue the questionaire or "Submit" to submit your current answers.';
$string ['questionaire_answer_stats'] = 'Current state of the questionaire: ';
$string ['questionaire_submitted'] = 'You have submitted your answers. You cannot change them anymore.';
$string ['questionaire_press_preview'] = 'Press "Preview" to take a look at the questionaire.';
$string ['questionaire_no_more_questions'] = 'There are no more questions to answer.';
$string ['questionaire_press_beginning_submit'] = 'Press "Go to Start" to go back to the start page or "Submit" to submit your current answers.';
$string ['questionaire_go_to_start'] = 'Go to Start';
$string ['questionaire_submit'] = 'Submit';
$string ['questionaire_submit_disabled_teacher'] = 'Submit is disabled because this is just a preview.';
$string ['questionaire_preview'] = 'This is a preview of the questionaire.';
$string ['category_general'] = 'General';
$string ['category_grade'] = 'Grades';
$string ['category_team'] = 'Team';
$string ['category_character'] = 'Character';
$string ['category_motivation'] = 'Motivation';
$string ['category_learning'] = 'Learning';
$string ['category_knowledge'] = 'Knowledge';
$string ['category_topic'] = 'Topics';
$string ['category_sellmo'] = 'Learning and Achievement Motivation';
$string ['category_self'] = 'Self-assessment';
$string ['category_srl'] = 'Self-controlled Learning';
$string ['stats_partly'] = 'You answered {$a->answered} out of {$a->questions} questions in the category "{$a->category}".';
$string ['stats_all'] = 'You answered all questions in the category "{$a->category}".';
$string ['stats_none'] = 'You did not answer any question in the category "{$a->category}".';
$string ['tab_overview'] = 'Overview';
$string ['tab_questionaire'] = 'Questionaire';
$string ['tab_analysis'] = 'Analysis';
$string ['tab_grouping'] = 'Groupformation';
$string ['tab_preview'] = 'Questionaire (Preview)';
$string ['tab_evaluation'] = 'Evaluation';
$string ['tab_group'] = 'Group assignment';
$string ['questionaire_availability_info_future'] = 'The questionnaire will be available in the perion from {$a->start} to {$a->end}.';
$string ['questionaire_availability_info_now'] = 'The questionnaire is available till {$a->end}.';
$string ['questionaire_availability_info_until'] = 'Der Fragebogen ist noch bis {$a->end} Uhr freigeschaltet.';
$string ['questionaire_availability_info_from'] = 'Der Fragebogen ist ab {$a->start} Uhr freigeschaltet.';
$string ['questionaire_available'] = 'The questionnaire is ready for processing.';
$string ['questionaire_not_available_begin'] = 'The questionnaire is available from {$a->start}.';
$string ['questionaire_available_end'] = 'The questionnaire is available till {$a->end}.';
$string ['questionaire_not_available'] = 'The questionnaire is currently unavailable.';
$string ['questionaire_not_available_begin_end'] = 'The questionnaire is available from {$a->start} to {$a->end}.';
$string ['questionaire_not_available_end'] = 'The questionnaire is not available anymore.';
$string ['info_header_student'] = 'What is groupformation for?';
$string ['info_text_student'] = 'In diesem Moodle-Kurs wird die Gruppenformation dazu genutzt {$a->scenario_name} für eine erfolgreiche Zusammenarbeit zu bilden.
		Wenn Sie den Fragebogen ausgefüllt und abgeschickt haben, werden für Sie geeignete Lernpartner/innen ermittelt.
		Alle Angaben werden vertraulich behandelt.
		<br>Ist die Befragungszeit um, werden Gruppen unter Berücksichtigung Ihrer Angaben und Präferenzen gebildet. Sie können anschließend hier Ihre Gruppenmitglieder einsehen (und auch über Moodle kontaktieren).
		<br><br>
		Fragen? Probleme? Lob? Anregungen?<br>
		Die Plugin-Entwickler/innen und Wissenschaftler/innen dazu, finden Sie unter<br>
		http://sourceforge.net/projects/moodlepeers/ <br>
		(erstellt und weiterentwickelt mit Mitteln zur Qualitätsverbesserung der Lehre\' der TU Darmstadt)
';
$string ['info_header_teacher_analysis'] = 'Wie funktioniert die Gruppenformation?';
$string ['info_header_teacher_settings'] = 'Wie funktioniert die Gruppenformation?';
$string ['info_text_teacher_settings'] = 'Mit diesem Plugin haben Sie die Möglichkeit die Bildung von Gruppen Ihrer Studierenden zu optimieren. Die drei erforderlichen Schritte sind:<br>
		1.)	Sie fügen die Aktivität „Gruppenformation“ Ihrem Kurs hinzu.
		Auf dieser Seite können Sie auswählen, welches Szenario am besten zu Ihren Vorstellungen der späteren Gruppenarbeit passt
		(bei Unsicherheit wählen Sie Projektteams). Die weiteren Einstellungen erlauben Ihnen die gewünschte Gruppengröße und evtl. eine Themenauswahl für
		Studierende anzugeben. Wenn Sie möchten, dass die Studierenden sich im Vorwissen gut ergänzen in den später gebildeten Gruppen, können Sie die Vorwissensthemen
		zur Abfrage angeben.<br>
		2.)	Studierende sehen den Fragebogen, welcher basierend auf Ihren Einstellungen erstellt wurde. Die Dauer der Verfügbarkeit der Fragen können Sie einstellen (siehe 1.).
		Eine Vorschau des Studierenden-Fragebogens erhalten Sie beim späteren Öffnen der Aktivität als Dozent/in.<br>
		3.)	Sie können unter dem Menüpunkt ‚Gruppenformation‘, wenn Sie die Aktivität später selbst aufrufen, sehen, wie viele Antworten bereits
		vorliegen. Sind Sie mit dem Rücklauf zufrieden, starten Sie manuell die Bildung der Gruppen (dies geschieht niemals automatisch).<br>
		Es dauert eine Weile, bis alle Gruppen fertig erstellt sind. Das Ergebnis können Sie sich noch einmal ansehen, bevor Sie die Gruppen so in Moodle übernehmen.<br>
		<br>
		Fertig. Jetzt können auch die Studierenden Ihre Gruppenmitglieder sehen. Es ist Ihnen als Dozent/in jederzeit möglich, die Gruppen manuell in Moodle nachzubearbeiten (bspw. bei Nachzüglern).<br>
';
$string ['info_text_teacher_analysis'] = 'Mit diesem Plugin haben Sie die Möglichkeit die Bildung von Gruppen Ihrer Studierenden zu optimieren. Die drei erforderlichen Schritte sind:<br>
		<br>
		1.)	Sie fügen die Aktivität „Gruppenformation“ Ihrem Kurs hinzu.
		(Das ist bereits geschehen, wenn Sie diese Seite hier sehen).
		In den Einstellungen können Sie auswählen, welches Szenario am besten zu Ihren Vorstellungen der späteren Gruppenarbeit passt
		(bei Unsicherheit wählen Sie Projektteams). Die weiteren Einstellungen erlauben Ihnen die gewünschte Gruppengröße und evtl. eine Themenauswahl für
		Studierende anzugeben. Wenn Sie möchten, dass die Studierenden sich im Vorwissen gut ergänzen in den später gebildeten Gruppen, können Sie die Vorwissensthemen
		zur Abfrage angeben.<br>
		<br>
		2.)	Studierende sehen den Fragebogen, welcher basierend auf Ihren Einstellungen erstellt wurde. Die Dauer der Verfügbarkeit der Fragen können Sie einstellen (siehe 1.).
		Eine Vorschau des Studierenden-Fragebogens erhalten Sie im Menü oben.<br>
		<br>
		3.)	Sie können unter dem Menüpunkt ‚Gruppenformation‘ hier auf dieser Seite sehen, wie viele Antworten bereits
		vorliegen. Sind Sie mit dem Rücklauf zufrieden, starten Sie manuell die Bildung der Gruppen (dies geschieht niemals automatisch).<br>
		Es dauert eine Weile, bis alle Gruppen fertig erstellt sind. Das Ergebnis können Sie sich noch einmal ansehen, bevor Sie die Gruppen so in Moodle übernehmen.<br>
		<br>
		Fertig. Jetzt können auch die Studierenden Ihre Gruppenmitglieder sehen. Es ist Ihnen als Dozent/in jederzeit möglich, die Gruppen manuell in Moodle nachzubearbeiten (bspw. bei Nachzüglern).<br>
';
$string['statusGrupping0'] = 'Um die Gruppenbildung starten zu können, müssen Sie die Aktivität in Overview beenden';
$string['statusGrupping1'] = 'Sie können die Gruppenbildung jetzt starten';
$string['statusGrupping2'] = 'Die Gruppenbildung läuft';
$string['statusGrupping3'] = 'Die Gruppenbildung wird abgebrochen';
$string['statusGrupping4'] = 'Gruppenbildung ist abgeschlossen. Wollen Sie die Gruppenvorschläge übernehme, oder soll ein neuer Gruppenvorschlag generiert werden';
$string['statusGrupping5'] = 'Die realen Gruppen sind gebildet. Sie können jetzt manuelle Veränderung an den Gruppen vornehmen ';
$string['grouping_start'] = 'Gruppenbildung starten';
$string['grouping_delete'] = 'Gruppen l&ouml;schen';
$string['grouping_adopt'] = 'Gruppe übernehmen';
$string['grouping_abort'] = 'Gruppenbildung abbrechen';
$string['moodlegrouping_delete'] = 'Moodle-Gruppen l&ouml;schen';
$string['questionaire_commited'] = 'Ihre Antworten sind abgegeben. Somit können Sie sie nicht mehr verändern.';
$string['no_data_to_display'] = 'Keine Daten vorhanden.';

$string['onlyactivestudents'] = 'Only students with at least one submitted answer should be considered for building groups';
$string['emailnotifications'] = 'I want to be notified when the groupformation job has terminated.';
$string['onlyactivestudents_description'] = 'Only students with at least one submitted answer should be considered for building groups';
$string['emailnotifications_description'] = 'I want to be notified when the groupformation job finished.';

$string['sampleGroupName'] = 'The name of your group is ';
$string['oneManGroup'] = 'You are a one man group.';
$string['noUser'] = 'User does not exist';
$string['membersAre'] = 'Your team members are: ';
$string['groupingNotReady'] = 'Group formation is not finished yet.';
$string['jobGetName'] = 'Group formation and clean up';
$string['no_time'] = 'No time fixed';
$string['activity_end'] = 'End activity';
$string['activity_start'] = 'Start activity';
$string['analysis_status_info0'] = 'You have to stop activity before group formation.';
$string['analysis_status_info1'] = 'You have to start activity so that students are able to answer the questionnaire.';
$string['analysis_status_info2'] = 'Group formation has already startet or is done respectively. Activity cannot be stopped anymore.';
$string['analysis_status_info3'] = 'You are able to start or end activity.';
$string['contact_members'] = 'To contact your team members you have to click on their user names.';
$string['invalid'] = 'invalid status';
$string['groups_build'] = 'Groups are built.';
$string['activity_visible'] = 'Activity is not visible for you.';

$string['are'] = 'You have ';
$string['are_now'] = 'At the moment you have ';
$string['students_available_single'] = ' enrolled student who can fill in the questionnaire.';
$string['students_available_multiple'] = ' enrolled students who can fill in the questionnaire.';
$string['students_answered_single'] = 'student has treated the questionnaire.';
$string['students_answered_multiple'] = 'students have treated the questionnaire.';
$string['name_by_group'] = 'Name: ';
$string['quality'] = 'Group quality ';
$string['quality_info'] = 'Group-Performance-Index (GPI) represents the quality of the built group and has a range between 0 and 1. The bigger the value, so much better the group. Is no value mentioned, the group is not built algorithmically, but by random.';
$string['to_groupview'] = 'to Moodle group view';
$string['number_member'] = 'Number of memebers: ';
$string['kohort_index'] = 'Cohorts-Performance-Index: ';
$string['kohort_index_info'] = 'The Cohorts-Performance-Index (KPI) represents the quality of the built groups and has a range between 0 and 1. The bigger the value, so much better the built groups.';
$string['max_group_size'] = 'max. group size: ';
$string['number_of_groups'] = 'Number of built groups: ';
$string['options'] = 'Options ';
$string['activity'] = 'Activity ';
$string['statistic'] = 'Statistics of questionnaire';
$string['group_building'] = 'Group building ';
$string['evaluation'] = 'Evaluation';
$string['group_overview'] = 'Overview of built groups';
$string['max_group_size_not_reached'] = 'maximum group size has not been reached by the following groups:';
$string['your_group'] = 'Your group ';
$string['students_grouping_single'] = ' student for group building.';
$string['students_grouping_multiple'] = ' students for group building.';
$string['students_commited_single'] = ' student therefrom has already commited his/her answers.';
$string['students_commited_multiple'] = ' students therefrom have already commited their answers.';
$string['commited_not_completed'] = ' from the commited questionnaires are not complete.';
$string['completed_questionaire'] = ' completely answered questionnaires.';
// $string [''] = '';