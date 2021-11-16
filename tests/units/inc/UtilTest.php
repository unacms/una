<?php

/**
 * Test util functions
 */
class UtilTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @see clear_xss
     * @dataProvider providerForClearXssAdmin
     */
    public function testClearXssAdmin($sInput, $sOutput)
    {
        $this->assertEquals(clear_xss($sInput), $sOutput);

        // if user is admin then "purify" is never called
        // $this->_testClearXss(1, 'never');
    }

    /**
     * @see clear_xss
     * @dataProvider providerForClearXssNotAdmin
     */
    public function testClearXssNotAdmin($sInput, $sOutput)
    {
        $this->assertEquals(clear_xss($sInput), $sOutput);

        // if user is not admin then "purify" is called once
        // $this->_testClearXss(0, 'once');
    }

    protected function _testClearXss($isAdmin, $sCalled)
    {
        // create mock object instance of HTMLPurifier class
        $GLOBALS['oHtmlPurifier'] = $this->createMock('HTMLPurifier');

        // set admin or not admin user
        $GLOBALS['logged']['admin'] = $isAdmin;

        // check if we have instance of correct class
        $this->assertInstanceOf('HTMLPurifier', $GLOBALS['oHtmlPurifier']);

        // we expect that 'purify' method should be called once(or never) when we call clear_xss function
        $GLOBALS['oHtmlPurifier']->expects($this->$sCalled())->method('purify');

        // call tested function
        clear_xss('test');
    }

    public function providerForClearXssNotAdmin()
    {
        return array(
            array('test<script>alert(1);</script>', 'test'),
            array('test<style>div {border:3px solid red;}</style>', 'test'),
        );
    }

    public function providerForClearXssAdmin() 
    {
        // TODO: for admin all tags should be allowed including script and style
        return array(
            array('test<script>alert(1);</script>', 'test'),
            array('test<style>div {border:3px solid red;}</style>', 'test'),
        );
    }

    /**
     * @see return_bytes
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

    /**
     * @see title2uri
     * @dataProvider providerForTitleToUri
     */
    function testTitleToUri($sIn, $sOut)
    {
        $this->assertEquals(title2uri($sIn), $sOut);
    }
    public function providerForTitleToUri()
    {
        return array(
            array('test', 'test'),
            array('test & test', 'test [and] test'),
            array('test + test', 'test [plus] test'),
            array('"test"', '[quote]test[quote]'),
            array('/test/', '[slash]test[slash]'),
            array('\\test\\', '[backslash]test[backslash]'),
        );
    }

    /**
     * @see uri2title
     * @dataProvider providerForUriToTitle
     */
    function testUriToTitle($sIn, $sOut)
    {
        $this->assertEquals(uri2title($sIn), $sOut);
    }
    public function providerForUriToTitle()
    {
        return array(
            array('test', 'test'),
            array('test [and] test', 'test & test'),
            array('test [plus] test', 'test + test'),
            array('[quote]test[quote]', '"test"'),
            array('[slash]test[slash]', '/test/'),
            array('[backslash]test[backslash]', '\\test\\'),
        );
    }
}
