<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require '../../config.php';
require_once 'edit_form.php';

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT);
$course     = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context    = context_course::instance($course->id);

require_login($course);
require_capability('enrol/mandatory:config', $context);

$PAGE->set_url('/enrol/mandatory/edit.php', [
    'courseid'  => $course->id,
    'id'        => $instanceid,
]);
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', ['id' => $course->id]);
if (!enrol_is_enabled('mandatory')) {
    redirect($return);
}

$plugin = enrol_get_plugin('mandatory');

// Get the enrollment instance
if ($instanceid) {
    // Existing instance
    $instance = $DB->get_record('enrol', [
        'courseid'  => $course->id, 
        'enrol'     => 'mandatory',
        'id'        => $instanceid,
    ], '*', MUST_EXIST);
} else {
    // New instance
    require_capability('moodle/course:enrolconfig', $context);
    navigation_node::override_active_url(new moodle_url(
        '/enrol/instances.php',
        ['id' => $course->id]
    ));
    
    $instance           = new stdClass();
    $instance->id       = null;
    $instance->courseid = $course->id;
}

$mform = new enrol_mandatory_edit_form(null, [$instance, $plugin, $context]);

if ($mform->is_cancelled()) {
    redirect($return);
} else if ($data = $mform->get_data()) {
    if ($instance->id) {
        // Existing instance
        $instance->roleid       = $data->roleid;
        
        $DB->update_record('enrol', $instance);
    } else {
        // New instance
        $field = ['roleid' => $data->roleid];
        
        $plugin->add_instance($course, $field);
    }
    
    redirect($return);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title('Mandatory Enrollment');

echo $OUTPUT->header();
echo $OUTPUT->heading('Mandatory Enrollment');
$mform->display();
echo $OUTPUT->footer();
