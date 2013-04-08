<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Moovrelocator - Atom class
 *
 * PHP versions 5
 *
 * LICENSE: Moovrelocator - Copyright (c) 2009, Benjamin Carl -
 * All rights reserved. Redistribution and use in source and binary forms, with
 * or without modification, are permitted provided that the following conditions
 * are met: Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer. * Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and the
 * following disclaimer in the documentation and/or other materials provided with
 * the distribution. * All advertising materials mentioning features or use of
 * this software must display the following acknowledgement: This product includes
 * software developed by Benjamin Carl and its contributors.
 *
 * Neither the name of Benjamin Carl nor the names of its contributors may be used
 * to endorse or promote products derived from this software without specific prior
 * written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * Please feel free to contact us via e-mail: phpfluesterer@googlemail.com
 *
 * @category   Moovrelocator
 * @package    Moovrelocator_Lib
 * @subpackage Moovrelocator_Lib_Atom
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    SVN: $Id: Atom.class.php 2 2010-01-25 01:01:16Z phpfluesterer $
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */

/**
 * Moovrelocator - Atom class
 *
 * MOOV Relocator is a well documented small library written in PHP to relocate (or move)
 * the MOOV-Atom of MP4-Files from the end to the beginning of a file.
 *
 * @category   Moovrelocator
 * @package    Moovrelocator_Lib
 * @subpackage Moovrelocator_Lib_Atom
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    Release: @package_version@
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */
class Atom
{
	/**
	 * is 64Bit?
	 * 
	 * @var boolean
	 * @access private 
	 */
    private $_is64   = false;
	
    /**
     * type of atom (ISO code)
     * 
     * @var string
     * @access private 
     */
	private $_type   = '';
	
    /**
     * size of atom in bytes
     * 
     * @var integer
     * @access private 
     */	
    private $_size   = 0;
	
    /**
     * offset of atom in the file in bytes
     * 
     * @var integer
     * @access private 
     */ 	
    private $_offset = 0;
	
    /**
     * ISO family codes / atom types
     */
	const FTYP_ATOM          = 'ftyp';
	const MOOV_ATOM          = 'moov';
    const CMOV_ATOM          = 'cmov';
	const STCO_ATOM          = 'stco';
	const CO64_ATOM          = 'co64';
	const URL_ATOM           = 'url ';
	const XML_ATOM           = 'xml ';
	
    protected static $validAtoms = array(
        self::FTYP_ATOM,
        self::MOOV_ATOM,
		self::CMOV_ATOM,
		self::STCO_ATOM,
		self::CO64_ATOM,
		self::URL_ATOM,
		self::XML_ATOM,
		'pdin',
        'mvhd',
        'trak',
        'tkhd',
        'tref',
        'edts',
        'elst',
        'mdia',
        'mdhd',
        'hdlr',
        'minf',
        'vmhd',
        'smhd',
        'hmhd',
        'nmhd',
        'dinf',
        'dref',
        'stbl',
        'stsd',
        'stts',
        'ctts',
        'stsc',
        'stsz',
        'stz2',
        'stss',
        'stsh',
        'padb',
        'stdp',
        'sdtp',
        'sbgp',
        'sgpd',
        'subs',
        'mvex',
        'mehd',
        'trex',
        'ipmc',
        'moof',
        'mfhd',
        'traf',
        'tfhd',
        'trun',
        'mfra',
        'tfra',
        'mfro',
        'mdat',
        'free',
        'skip',
        'udta',
		'cprt',
		'meta',
		'dinf',
		'ipmc',
		'iloc',
		'ipro',
		'sinf',
		'frma',
		'imif',
		'schm',
		'schi',
		'iinf',
		'bxml',
		'pitm'
    );


    /**
     * returns true if $fourByteString is a character string 
     * and  atom type (ISO code of the atom) is valid ($validAtoms)
     */	
    public static function isValidAtom($fourByteString)
	{
        if (preg_match('/[@a-zA-Z][a-zA-Z0-9][a-zA-Z0-9 ][a-zA-Z0-9 ]/', $fourByteString) && 
		    in_array($fourByteString, self::$validAtoms)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * returns the atom type (ISO code of the atom)
     * i.e.: moov, ftyp, mdat etc...
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * sets the atom type (ISO code of the atom)
     * i.e.: moov, ftyp, mdat etc...
     */
    public function setType($type)
    {
        $this->_type = $type;
	}

    
    /**
     * returns the size of atom in bytes
     */
    public function getSize()
    {
        return $this->_size;
    }
    
    /**
     * sets the size of atom in bytes
     */
    public function setSize($value)
    {
        $this->_size = $value;
        if ($value == 1) {
            $this->_is64 = true; 
        }
    }


    /**
     * returns the offset of where the atom/box resides in 
     * the file in bytes    
     */
    public function getOffset()
    {
        return $this->_offset;
    }
	
    /**
     * sets the offset of where the atom/box resides in 
     * the file in bytes    
     */
    public function setOffset($value)
    {
        $this->_offset = $value;
    }


    /**
     * returns formatted informations about the instance   
     */
    public function toString()
    {
        return '[type: ' . $this->_type . ',size: ' . $this->_size . ',offset: ' . $this->_offset . ']';
    }	
}

?>
