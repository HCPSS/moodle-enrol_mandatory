<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Make sure everyone is enrolled in this course.
 * 
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_mandatory_plugin extends enrol_plugin {
    /**
     * Can an enrolment instance be deleted through the UI?
     * 
     * @param stdClass $instance
     * @return boolean
     */
    public function can_delete_instance($instance) {
        $context = context_course::instance($instance->courseid);
        
        return has_capability('enrol/mandatory:config', $context);
    }
    
    /**
     * Generate a link to the form for a new instance.
     * 
     * @param type $courseid
     * @return \moodle_url
     */
    public function get_newinstance_link($courseid) {
        $context = context_course::instance($courseid);
        
        $canenrol  = has_capability('moodle/course:enrolconfig', $context);
        $canconfig = has_capability('enrol/ldapgroup:config', $context);
        if (!$canenrol || !$canconfig) {
            // No access
            return null;
        }
        
        return new moodle_url('/enrol/mandatory/edit.php', array(
            'courseid' => $courseid
        ));
    }
    
    /**
     * Returns edit icons for the page with list of instances
     * 
     * @param \stdClass $instance
     */
    public function get_action_icons(\stdClass $instance) {
        global $OUTPUT;
        
        if ($instance->enrol !== 'mandatory') {
            throw new coding_exception('invalid enrol instance');
        }
        
        $context = context_course::instance($instance->courseid);
        
        $icons = array();
        
        if (has_capability('enrol/mandatory:config', $context)) {            
            $editlink = new moodle_url('/enrol/mandatory/edit.php', array(
                'courseid'  => $instance->courseid,
                'id'        => $instance->id,
            ));
            
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon(
                't/edit', 
                get_string('edit'), 
                'core', 
                array('class' => 'iconsmall')
            ));
        }
        
        return $icons;
    }

    /**
     * Is is possible to hide/show enrol instance through the UI?
     * 
     * @param stdClass $instance
     * @return boolean
     */
    public function can_hide_show_instance($instance) {
        $context = context_course::instance($instance->courseid);
        
        return has_capability('enrol/mandatory:config', $context);
    }
    
    /**
     * Returns enrolment instance manage link,
     * 
     * @param navigation_node $instancesnode
     * @param \stdClass $instance
     * @throws coding_exception
     */
    public function add_course_navigation($instancesnode, \stdClass $instance) {
        if ($instance->enrol !== 'mandatory') {
            throw new coding_exception('Invalid enrol instance type.');
        }
        
        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/mandatory:config', $context)) {
            $managelink = new moodle_url('/enrol/mandatory/edit.php', array(
                'courseid' => $instance->courseid,
                'id' => $instance->id
            ));
            
            $instancesnode->add(
                $this->get_instance_name($instance), 
                $managelink, 
                navigation_node::TYPE_SETTING
            );
        }
    }
}
