<?php

use PHPUnit\Framework\TestCase;
use wgh000\onBoarding\OnBoarding;

/**
 * @author Simone Cabrino
 */
class OnBoardingTest extends TestCase
{
    protected $obj;

    /**
     * OnBoardingTest constructor.
     */
    protected function setUp()
    {
        $this->obj = new OnBoarding();
    }

    /**
     * Just check if the OnBoardingTest has no syntax error
     *
     * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
     * any typo before you even use this library in a real project.
     *
     */
    public function testIsThereAnySyntaxError(): void
    {
        $this->assertInternalType('object', $this->obj);
    }

    public function testException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->obj = $this->obj->setName('Giuseppe');
        $this->assertTrue($this->obj->canProceed());
    }

    public function testEuropean(): void
    {
        $this->obj = $this->obj->setName('MARIO')->setPhoneCountry('DEU')->setDocumentCountry('FRA')->setAddressCountry('ITA');
        $this->assertTrue($this->obj->canProceed());
    }

    public function testEuropeanInRussia(): void
    {
        $this->obj = $this->obj->setName('ADELISE')->setPhoneCountry('RUS')->setDocumentCountry('FIN')->setAddressCountry('RUS');
        $this->assertFalse($this->obj->canProceed());
    }

    public function testRussian(): void
    {
        $this->obj = $this->obj->setName('DMITRII')->setPhoneCountry('RUS')->setDocumentCountry('RUS')->setAddressCountry('RUS');
        $this->assertTrue($this->obj->canProceed());
    }

    public function testArabInRussian(): void
    {
        $this->obj = $this->obj->setName('ABDUL')->setPhoneCountry('RUS')->setDocumentCountry('RUS')->setAddressCountry('RUS');
        $this->assertFalse($this->obj->canProceed());

        $this->obj = $this->obj->setName('IANVARBEK')->setPhoneCountry('RUS')->setDocumentCountry('RUS')->setAddressCountry('RUS');
        $this->assertFalse($this->obj->canProceed());

        $this->obj = $this->obj->setName('KHYNTYZHV')->setPhoneCountry('RUS')->setDocumentCountry('RUS')->setAddressCountry('RUS');
        $this->assertFalse($this->obj->canProceed());
    }

    public function testArabInEurope(): void
    {
        $this->obj = $this->obj->setName('ZURZILAT')->setPhoneCountry('SPA')->setDocumentCountry('SPA')->setAddressCountry('SPA');
        $this->assertTrue($this->obj->canProceed());
    }

    public function testHighRisk(): void
    {
        $this->obj = $this->obj->setName('O\'SHEA')->setPhoneCountry('SYR')->setDocumentCountry('RUS')->setAddressCountry('RUS');
        $this->assertFalse($this->obj->canProceed());
    }
}
