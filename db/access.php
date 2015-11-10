<?php

/**
 * @package enrol_mandatory
 * @author Brendan Anderson <brendan_anderson@hcpss.org>
 * @copyright (c) 2015, Howard County Public Schools
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'enrol/mandatory:config' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        )
    ),
); 
