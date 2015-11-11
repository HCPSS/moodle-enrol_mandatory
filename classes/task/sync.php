<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_mandatory\task;

/**
 * This Moodle task creates and updates users in Moodle who have been recently
 * updated in LDAP.
 * 
 * @author Brendan Anderson
 */
class sync extends \core\task\scheduled_task {
    
    /**
     * 
     * @global \moodle_database $DB
     * @param type $enrolment
     */
    private function process_enrolment($enrolment) {
        global $DB;
        
        $userids = $DB->get_records_sql('
            SELECT id FROM {user}
            WHERE deleted = 0 AND suspended = 0 AND id NOT IN (
                SELECT userid FROM {user_enrolments}
                WHERE enrolid = :enrolid
            )
        ', ['enrolid' => $enrolment->id]);
        
        $plugin = new \enrol_mandatory_plugin();
        foreach (array_keys($userids) as $userid) {
            $plugin->enrol_user($enrolment, $userid, $enrolment->roleid, time(), 0);
        }
        
        return array_keys($userids);
    }
    
    /**
     * Task execution which syncs users with LDAP
     * 
     * @see \core\task\task_base::execute()
     * @global \moodle_database $DB
     */
    public function execute() {
        global $DB;
        
        $enrolments = $DB->get_records('enrol', [
            'enrol'     => 'mandatory',
            'status'    => 0,
        ]);
        
        foreach ($enrolments as $enrolment) {
            $userids = $this->process_enrolment($enrolment);
            
            mtrace(vsprintf('Enroled %d users in course %s', [
                count($userids),
                $enrolment->courseid,
            ]));
        }
    }
        
    /**
     * (non-PHPdoc)
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return 'Mandatory Enrollment Processor';
    }
}
