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
            array('2k', 2048),
            array('2K', 2048),
            array('2 k', 2048),
            array('2 K', 2048),
            array('1m', 1048576),
            array('1g', 1073741824),
        );
    }
}
