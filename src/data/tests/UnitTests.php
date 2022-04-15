<?php

/**
 * 
 * PHPUnit is used for unit testing
 * 
 * Installation:
 * - cd to directory where you want to run your tests from (i.e. tests)
 * - run `wget -O phpunit https://phar.phpunit.de/phpunit-9.phar`
 * - run `chmod +x phpunit`
 * 
 * Writing Tests:
 * - Test functions must have names beginning with the word 'test'
 * 
 * To run tests:
 * - cd to this directory (tests)
 * - run `php ./phpunit ./UnitTests.php`
 *  - '.' means a successful test
 *  - 'F' means a failed test
 *  - 'W' means a warning happened while trying to run test file
 * 
 */

require_once('../PDO.DB.class.php');
require_once('../validations.php');

class UnitTests extends \PHPUnit\Framework\TestCase
{

    // stringText(...) should return false for valid strings
    // stringText(...) should return true for invalid strings or injections
    public function testStringText(){
        $this->assertFalse(stringText("This is a string."));
        $this->assertFalse(stringText(""));
        $this->assertTrue(stringText("<script>alert('dangerous script oooo')</script>"));
    }

    public function testNotDecimal(){
        $this->assertTrue(notDecimal("10"));
        $this->assertFalse(notDecimal("10.0"));
    }
    
    public function testIntegerNotEmpty0(){
        $this->assertFalse(integerNotEmpty0("10"));
        $this->assertTrue(integerNotEmpty0(""));
        $this->assertTrue(integerNotEmpty0("0"));
        $this->assertTrue(integerNotEmpty0("test"));
    }
    
    public function testAlphabeticID(){
        $this->assertFalse(alphabeticID("abc"));
        $this->assertFalse(alphabeticID("de"));
        $this->assertTrue(alphabeticID("abcd"));
        $this->assertTrue(alphabeticID(""));
        $this->assertTrue(alphabeticID("123"));
    }
    
    public function testStringNotEmpty255(){
        $this->assertFalse(stringNotEmpty255("This is a string."));
        $this->assertTrue(stringNotEmpty255(""));
        $this->assertTrue(stringNotEmpty255("<script>alert('dangerous script oooo')</script>"));
        $this->assertTrue(stringNotEmpty255("01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789"));
    }
}