<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Moovrelocator - Bytearray class - holds bytes
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
 * @subpackage Moovrelocator_Lib_Bytearray
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    SVN: $Id: Bytearray.class.php 2 2010-01-25 01:01:16Z phpfluesterer $
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */

/**
 * Moovrelocator - Bytearray class - holds bytes
 *
 * MOOV Relocator is a well documented small library written in PHP to relocate (or move)
 * the MOOV-Atom of MP4-Files from the end to the beginning of a file.
 *
 * @category   Moovrelocator
 * @package    Moovrelocator_Lib
 * @subpackage Moovrelocator_Lib_Bytearray
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    Release: @package_version@
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */
class Bytearray
{
    /**
     * holding the bytes of this bytearray as array 
     *
     * empty array on initialising - used to hold the bytes of this
     * bytearray
     *
     * @var array
     * @access private
     */
	private $_byteArray    = array();

    /**
     * holding the bytes of this bytearray as string 
     *
     * empty string on initialising - used to hold the bytes of this
     * bytearray
     *
     * @var string
     * @access private
     */
	private $_bytes   = '';
	
    /**
     * holding the last position of bytes written 
     *
     * 0 integer on initialising - used to hold the last position of pointer
     * after writing bytes to byteArray
     *
     * @var integer
     * @access private
     */
	private $_lastPosition = 0;
	
    /**
     * holding the current position of bytes written 
     *
     * 0 integer on initialising - used to hold the current position of pointer
     * after writing bytes to byteArray
     *
     * @var integer
     * @access private
     */
	private $_position     = 0;
	
	
	
    /**
     * sets the current position of pointer
     *
     * sets the position of Bytearray pointer after writing bytes to Bytearray
     *
     * @param   integer $position current position of the pointer 
     * 
     * @return  void
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
	public function setPosition($position)
	{
		$this->_lastPosition = $this->_position;
        $this->_position     = $position;	
	}
	

    /**
     * returns the current position of pointer
     *
     * returns the current position of Bytearray pointer
     *
     * @param   void 
     * 
     * @return  integer current position of the pointer  
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
    public function getPosition()
    {
        return $this->_position;   
	}
	
	
    /**
     * returns the last position of pointer
     *
     * returns the last position - the position of pointer before last write
     * operation
     *
     * @param   void 
     * 
     * @return  integer last position of the pointer  
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
    public function getLastPosition()
    {
        return $this->_lastPosition;   
    }
	
	
    /**
     * writes bytes to the Bytearray
     *
     * writes bytes to the Bytearray
     *
     * @param   void 
     * 
     * @return  void 
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
    public function writeBytes($bytes, $offset = false, $inject = false)
    {
    	// if offset not given - set offset to lastpos + 1 (append)
		if (!$offset) {
			$offset = $this->getPosition();
		}
		
        if ($offset == $this->getPosition()) {
        	// offset is current pos (len) +1 so just append!
        	$this->_bytes .= $bytes;
			$this->setPosition($offset + strlen($bytes));
						
        } elseif ($offset > $this->getPosition()) {
        	// the offset is larger then current bytes string len
			// fill missing bytes with null-Bytes
			$countFillBytes = $offset - $this->getPosition();
			for ($i = 0; $i < $countFillBytes; $i++) {
				$fillBytes .= pack("H", 0x00);
			}
			$this->_bytes .= $fillBytes;
			$this->_bytes .= $bytes;
			$this->setPosition($offset + strlen($bytes));
			
        } elseif ($offset < strlen($this->_bytes)+1) {
        	// we write into existing position in byte string
			// so we check for overwrite (default) || inject into exisiting
			if (!$inject) {
				// we overwrite exisiting bytes
                // we inject bytes into existing bytes
                $bytesB = substr($this->_bytes, 0, $offset-1);
                $bytesA = substr($this->_bytes, ($offset-1)+strlen($bytes), strlen($this->_bytes)-(($offset-1)+strlen($bytes)));              
                $this->_bytes = $bytesB . $bytes . $bytesA;
                $this->setPosition(strlen($this->_bytes));				
				
			} else {
				// we inject bytes into existing bytes
				$bytesB = substr($this->_bytes, 0, $offset-1);
				$bytesA = substr($this->_bytes, $offset-1, strlen($this->_bytes)-($offset-1));				
				$this->_bytes = $bytesB . $bytes . $bytesA;
				$this->setPosition(strlen($this->_bytes) . strlen($bytes));
			}
        }
    } 

		
    /**
     * read bytes from Bytearray
     *
     * read bytes from Bytearray with given offset and length
     *
     * @param   void 
     * 
     * @return  void 
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
    public function readBytes($offset, $length)
    {
        return substr($this->_bytes, $offset, $length);   
    }
	
	
    /**
     * read all bytes from Bytearray
     *
     * read all bytes from Bytearray
     *
     * @param   void 
     * 
     * @return  void 
     * @access  public
     * @author  Benjamin Carl <benjamin.carl@qualifier.de>
     * @since   Method available since Release 1.0.0
     * @version 1.0
     */
    public function readAllBytes()
    {
        return $this->_bytes;   
    }     
	
	
	public function readUnsignedInt($offset)
	{
		$length = 4;
		$bytes  = $this->readBytes($offset, $length);
		$bytes  = unpack('N', $bytes);
		return $bytes[count($bytes)];
	}


    public function readUTFBytes($offset, $length = 4)
    {
        $bytes = $this->readBytes($offset, $length);
        return $bytes;
    }
	
	
    public function readDouble($offset, $length = 8)
    {
        $bytes  = $this->readBytes($offset, $length);
        $bytes  = unpack('d', $bytes);
        return $bytes[count($bytes)];
    }	
		
	
	public function bytesAvailable()
	{
		return strlen($this->_bytes);
	}
}

?>
