<?php

/**
 * Created by PhpStorm.
 * User: juanfelipe
 * Date: 10/08/17
 * Time: 3:24 PM
 */
global $CFG;
require_once($CFG->dirroot.'/plagiarism/safeassign/lib.php');
require_once($CFG->dirroot.'/config.php');

class Test extends PHPUnit_Framework_TestCase
{
    function insert_files_for_testing($cm, $userid, $reporturl, $score, $time, $subid, $fileid) {
        global $DB;
        $file = new stdClass();
        $file -> cm = $cm; // course id
        $file -> userid = $userid;
        $file -> reporturl = $reporturl;
        $file -> similarityscore = $score;
        $file -> timesubmitted = $time;
        $file -> supported = 1; //fixed on '1'
        $file -> submissionid = $subid;
        $file -> fileid = $fileid;
        $DB->insert_record('plagiarism_safeassign_files', $file, true);
    }

    function insert_submission_for_testing($submitted, $report, $subid) {
        global $DB;
        $submission = new stdClass();
        $submission -> globalcheck = '1';
        $submission -> groupsubmission = '0';
        $submission -> highscore = 1.00;
        $submission -> avgscore = 0.50;
        $submission -> submitted = $submitted;
        $submission -> reportgenerated = $report;
        $submission -> submissionid = $subid;
        $DB->insert_record('plagiarism_safeassign_subm',$submission, true);
    }

    //Case 0: ideal case, submitted and analyzed
    function test_ideal_case() {
        $this -> insert_submission_for_testing(1, 1, 1111111);
        $this -> insert_files_for_testing(000, 111, 'http://fakeurl1.com', 0.99, 1502484564, 1111111, 111);
        $object = new stdClass();
        $object -> fileid = 111;
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib->get_file_results(000, 111, $object);
        $this->assertequals(1, $results['analyzed']);
        $this->assertequals(0.99, $results['score']);
        $this->assertequals('http://fakeurl1.com', $results['reporturl']);
    }
    //Case 1: submitted but not analyzed yet
    function test_case1() {
        $this -> insert_submission_for_testing(1,0,2222222);
        $this -> insert_files_for_testing(000, 222, '', NULL, 1502484564, 2222222, 222);
        $object = new stdClass();
        $object -> fileid = 222;
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib -> get_file_results(000, 222, $object);
        $this -> assertequals(0, $results['analyzed']);
        $this -> assertequals('', $results['score']);
        $this -> assertequals('', $results['reporturl']);
    }
    //Case 2: testing an unexisting submission
    function test_case2() {
        $object = new stdClass();
        $object -> fileid = 333;
        $lib = new plagiarism_plugin_safeassign();
        $results = $lib -> get_file_results(000, 333, $object);
        $this -> assertequals(0, $results['analyzed']);
        $this -> assertequals('', $results['score']);
        $this -> assertequals('', $results['reporturl']);
    }
}


