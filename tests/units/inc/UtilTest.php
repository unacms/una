<?php

/**
 * This test performs test install, it can do it only if script is not installed yet.
 */
class UtilTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerForReturnBytes
     */
    public function testReturnBytes($sInput, $sOutput)
    {
        $this->assertEquals(return_bytes($sInput), $sOutput);
    }

    public function providerForReturnBytes()
    {
        return array(
            array('2k', 20480),
            array('2K', 20480),
            array('2 k', 20480),
            array('2 K', 20480),
            array('1m', 10485760),
            array('1g', 10737418240),
        );
    }
}
