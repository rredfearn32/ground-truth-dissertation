<?php
require_once('../simpletest/autorun.php');
require_once('../../../php/classes/membership.php');

class TestOfLogging extends UnitTestCase {
    function testValidationOfUser() {
        $membership = new membership("rbj9","password");
        $this->assertFalse(file_exists('/temp/test.log'));
        $log->message('Should write this to a file');
        $this->assertTrue(file_exists('/temp/test.log'));
    }
}
?>