<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir . '/formslib.php';

/**
 * Form for editing a mandatory enrolment.
 * 
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_mandatory_edit_form extends moodleform {
    
    /**
     * Define the form.
     * 
     * @global moodle_database $DB
     */
    public function definition() {
        global $DB;
        
        /** @var moodle_form $mform */
        $mform = $this->_form;
        list($instance, $plugin, $context) = $this->_customdata;
        
        $mform->addElement('header', 'header', 'Mandatory Enrolment');
        
        // Roles
        $roles = get_default_enrol_roles(
            $context, 
            $instance->id ? $instance->roleid : null
        );
        
        $mform->addElement('select', 'roleid', 'Role', $roles);
        $mform->setDefault('roleid', $plugin->get_config('default_roleid'));
        
        // Hidden values for convenience
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        
        // Buttons
        $this->add_action_buttons();
        
        $this->set_data($instance);
    }
}
