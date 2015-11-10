<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(    
    // This task assigns users to groups
    array(
        'classname' => 'enrol_mandatory\task\sync',
        'blocking'  => 0,
        
        // Run this task eat 6:15 every morning.
        'minute'    => '15',
        'hour'      => '6',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*',
    ),
);
