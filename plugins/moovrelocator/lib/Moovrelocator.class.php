<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Moovrelocator
 *
 * In H.264-based video formats (mp4, m4v) the metadata is called a "moov atom".
 * The moov atom is a part of the file that holds the index information for the
 * whole file. Many encoding software programs such as FFMPEG will insert this
 * moov atom information at the end of the video file. This is bad. The moov atom
 * needs to be located at the beginning of the file, or else the entire file will
 * have to be downloaded before it begins playing
 * (http://flowplayer.org/plugins/streaming/pseudostreaming.html).
 *
 * this source is based on sources and/or informations from the following sources:
 *
 * php-reader - http://code.google.com/p/php-reader/
 * by Sven Vollbehr <sven@vollbehr.eu>
 * [http://code.google.com/p/php-reader/people/detail?u=svollbehr]
 *
 * php-mp4info - http://code.google.com/p/php-mp4info/)
 * by Tommy Lacroix <lacroix.tommy@gmail.com>
 * [http://www.tommylacroix.com]
 *
 * QTIndexSwapper (Original Application) - http://renaun.com/air/QTIndexSwapper.air
 * by Renaun Erickson (Adobe)
 * [http://renaun.com]
 *
 * qt-faststart.c - http://cekirdek.pardus.org.tr/~ismail/ffmpeg-docs/qt-faststart_8c-source.html
 * by Mike Melanson (Adobe) <melanson@pcisys.net>
 * [http://blogs.adobe.com/penguin.swf/]
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
 * @subpackage Moovrelocator_Lib_Base
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    SVN: $Id: Moovrelocator.class.php 2 2010-01-25 01:01:16Z phpfluesterer $
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */

// get absolute path to this lib
define('PATH_MOOVRELOCATOR', BX_DIRECTORY_PATH_PLUGINS . 'moovrelocator/lib/');

// atom poc class
require_once PATH_MOOVRELOCATOR.'atom'.DIRECTORY_SEPARATOR.'Atom.class.php';

// bytearray to operate direct on bytes in memory just to simulate Adobe's AS3 Bytearray
require_once PATH_MOOVRELOCATOR.'bytes'.DIRECTORY_SEPARATOR.'Bytearray.class.php';

// transform (taken from php-reader)
require_once PATH_MOOVRELOCATOR.'bytes'.DIRECTORY_SEPARATOR.'Transform.class.php';

/**
 * Moovrelocator
 *
 * MOOV Relocator is a well documented small library written in PHP to relocate (or move)
 * the MOOV-Atom of MP4-Files from the end to the beginning of a file.
 *
 * @category   Moovrelocator
 * @package    Moovrelocator_Lib
 * @subpackage Moovrelocator_Lib_Base
 * @author     Benjamin Carl <phpfluesterer@googlemail.com>
 * @copyright  2009 Benjamin Carl
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version    Release: @package_version@
 * @link       http://www.benjamin-carl.de
 * @see        -
 * @since      File available since Release 1.0.0
 */
class Moovrelocator
{
    /**
     * holds the singleton instance of this class
     *
     * @var object
     * @access private
     */
    private static $_instance = null;

    /**
     * the file for output the fixed data
     *
     * @var string
     * @access private
     */
    private $_outputFile = false;

    /**
     * holds the size of inputfile in bytes
     *
     * @var integer
     * @access private
     */
    private $_filesize = 0;

    /**
     * holds the reference (file-handle) to file (input)
     *
     * @var integer
     * @access private
     */
    private $_fp = false;

    /**
     * holds the bytes of ftyp
     * (instance of Bytearray)
     *
     * @var object
     * @access private
     */
    private $_ftypBytes;

    /**
     * holds the bytes of moov
     * (instance of Bytearray)
     *
     * @var object
     * @access private
     */
    private $_moovBytes;

    /**
     * holds the bytes between fytp and moov
     * (instance of Bytearray)
     *
     * @var object
     * @access private
     */
    private $_middleBytes;

    /**
     * holds the files atoms/boxes
     *
     * @var array
     * @access private
     */
    private $_fileAtoms = array();

    /**
     * holds the last processed atom/box
     *
     * @var object
     * @access private
     */
    private $_lastAtom = null;

    /**
     * holds the file-parsing-status
     *
     * @var boolean
     * @access private
     */
    private $_successfullyParsed = false;

    /**
     * holds the file-parsing-status
     *
     * @var boolean
     * @access private
     */
    private $_fileValid = null;


    /**
     * sets the input file to process
     *
     * this method sets the input file 
     *
     * @param string $filename The input file to fix
     *
     * @return  mixed The error-message (string) if opening the inputfile failed, otherwise TRUE
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public function setInput($filename = null)
    {
        // check and open file
        if (!file_exists($filename) || !($this->_fp = fopen($filename, 'rb'))) {
            return 'cannot open file: '.$filename;
        }
        
        // get size of file
        $this->_filesize = filesize($filename);
        
        // empty file?
        if ($this->_filesize == 0) {
            return 'file '.$filename.' seems to be empyt!';
        }
        
        // parse file's atoms
        while (!feof($this->_fp) && ftell($this->_fp) < $this->_filesize) {
            $this->_fileAtoms[] = $this->_parseAtomsFromInput();
        }
        
        $moovEOFCheck = $this->_moovAtomAtEOF();
        if ($moovEOFCheck !== true) {
            return $moovEOFCheck;
        } else {
            // successful parsed the file!
            $this->_successfullyParsed = true;
        }
        
        // success
        return true;
    }


    /**
     * parse atoms / just parse and store / no check
     *
     * parse atoms / just parse and store / no check
     *
     * @return  object An object of type Atom
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _parseAtomsFromInput()
    {
        // reset file stream pointer to position of the lastAtom.offset + size
        if ((isset($this->_fileAtoms[count($this->_fileAtoms) - 1]))) {
            $lastAtom = $this->_fileAtoms[count($this->_fileAtoms) - 1];
            $position = $lastAtom->getSize() + $lastAtom->getOffset();
            if (ftell($this->_fp) <= $position) {
                fseek($this->_fp, $position);
            }
        }
        
        // get 8 bytes -> get totalSize and boxType
        $atomData = unpack('NtotalSize/NboxType', fread($this->_fp, 8));
        // store offset, size and type
        $offset = ftell($this->_fp) - 8;
        $size = $atomData['totalSize'];
        $type = pack('N', $atomData['boxType']);
        
        // check 64 bit size (files > 4GB)
        if ($size == 1) {
            $highInt = $atomData['totalSize'];
            $size = ($highInt << 32) | $atomData['totalSize'];
        }
        
        // positioning of file-pointer
        fseek($this->_fp, ($size + $offset));
        
        // give back atom
        return self::factory($offset, $size, $type);
    }

    
    /**
     * checks for valid qt-file
     *
     * checks for valid qt-file
     *
     * @return  boolean True if everything wents fine, otherwise false
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _isValidQTFile()
    {
        // check if file was succesfully parsed
        if (!$this->_successfullyParsed) {
            return 'please open a file first!';
        }
        
        // get first atom
        $firstAtom = $this->_fileAtoms[0];
        
        // check first for being valid (first atom MUST be ftyp)
        if ($firstAtom->getType() != Atom::FTYP_ATOM) {
            $this->_fileValid = false;
            return 'encountered non-QT top-level atom (is this a Quicktime file?)';
        }
        
        // check last for being valid
        $lastAtom = $this->_fileAtoms[count($this->_fileAtoms) - 1];
        
        if (!Atom::isValidAtom($lastAtom->getType())) {
            $this->_fileValid = false;
            return 'encountered non-QT top-level atom (is this a Quicktime file?';
        }
        
        // store validity
        return $this->_fileValid = true;
    }


    /**
     * check moov atoms position
     *
     * check moov atoms position
     *
     * @return  mixed True if everything wents fine, otherwise string error-message
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _moovAtomAtEOF()
    {
        if ($this->_fileAtoms[count($this->_fileAtoms) - 1]->getType() != Atom::MOOV_ATOM) {
            return 'The moov-atom isn\'t located at the end of the file, the file is allready ready for progressive download or it is a invalid file';
        }
        
        // moov is at end of file...
        return true;
    }


    /**
     * set output file
     *
     * set output file
     *
     * @param string $filename The filename ( + path) to put result (fixed data) in 
     *
     * @return  boolean True if everything wents fine, otherwise false
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public function setOutput($filename)
    {
        // store filename
        $this->_outputFile = $filename;
        
        // success
        return true;
    }


    /**
     * input, output and fix call - shortcut
     *
     * input, output and fix call - shortcut
     *
     * @param string  $filename       The input filename
     * @param string  $outputFilename The output filename
     * @param boolean $overwrite      The overwrite status 
     *
     * @return  mixed True if everything wents fine, otherwise error-message as string
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public function relocateMoovAtom($filename, $outputFilename = null, $overwrite = false)
    {
        // read file, preprocess (parse atoms/boxes)
        $result = $this->setInput($filename);
        if ($result !== true) {
            return $result;
        }
        
        if (is_null($outputFilename) && $overwrite === true) {
            $fileResult = $filename;
        } else {
            $fileResult = $outputFilename;
        }
        
        // set the output filename and path
        $result = $this->setOutput($fileResult);
        if ($result !== true) {
            return $result;
        }
        
        // moov positioning fix
        $result = $this->fix();
        if ($result !== true) {
            return $result;
        }
        
        // success
        return true;
    }


    /**
     * fix moov-atom location
     *
     * fix moov-atom location
     * 
     * @return  mixed True if everything wents fine, otherwise error-message as string
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public function fix()
    {
        // check if file was succesfully parsed
        if (!$this->_successfullyParsed) {
            return 'please open a file first! (syntax: '.__CLASS__.'->setInput($filename);)';
        }
        
        if (!$this->_outputFile) {
            return 'please set an outputfile first! (syntax: '.__CLASS__.'->setOutput($file);)';
        }
        
        // check if moov atom is allready at beginning of file
        if (!$this->_moovAtomAtEOF()) {
            return 'nothing to do! moov allready at begin of file!';
        }
        
        // Bytearray's holding bytes from file
        $this->_ftypBytes = new Bytearray();
        $this->_middleBytes = new Bytearray();
        $this->_moovBytes = new Bytearray();
        
        // read in file's bytes
        $result = $this->_readBytes();
        if ($result !== true) {
            return $result;
        }
        
        // now start swapping
        $result = $this->_swapIndex();
        if ($result !== true) {
            return $result;
        }
        
        // write new file
        $result = $this->_writeFile();
        if ($result !== true) {
            return $result;
        }
        
        // everythings fine!
        return true;
    }


    /**
     * reads all bytes from all found atoms
     *
     * reads all bytes from all found atoms
     * 
     * @return  mixed True if everything wents fine, otherwise error-message as string
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _readBytes()
    {
        // read bytes from all found atoms
        for ($atom = 0; $atom < count($this->_fileAtoms); $atom++) {
            
            // get the current atom/box
            $currentAtom = $this->_fileAtoms[$atom];
            $currentAtomType = $currentAtom->getType();
            
            // keep ftyp atom
            if ($currentAtomType == Atom::FTYP_ATOM) {
                // set file pointer to begin of file
                fseek($this->_fp, 0);
                $bytes = fread($this->_fp, $currentAtom->getSize());
                $this->_ftypBytes->writeBytes($bytes);
            
            } else if ($currentAtomType == Atom::MOOV_ATOM) {
                $bytes = fread($this->_fp, $currentAtom->getSize());
                $this->_moovBytes->writeBytes($bytes);
            
            } else {
                $bytes = fread($this->_fp, $currentAtom->getSize());
                $this->_middleBytes->writeBytes($bytes);
            }
        }
        
        return true;
    }


    /**
     * swaps the index from end to beginning
     *
     * swaps the index from end to beginning
     * 
     * @return  mixed True if everything wents fine, otherwise error-message as string
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _swapIndex()
    {
        $moovSize = $this->_moovBytes->bytesAvailable();
        
        $moovAType = '';
        $moovASize = 0;
        $offsetCount = 0;
        
        $compressionCheck = $this->_moovBytes->readBytes(12, 4);
        
        if ($compressionCheck == Atom::CMOV_ATOM) {
            throw new Exception('compressed MP4/QT-file can\'t do this file: '.$file);
        }
        
        // begin of metadata
        $metaDataOffsets = array();
        $metaDataStrings = array();
        $metaDataCurrentLevel = 0;
        
        $moovStartOffset = 12;
        
        for ($i = $moovStartOffset; $i < $moovSize - $moovStartOffset; $i++) {
            $moovAType = $this->_moovBytes->readUTFBytes($i, 4);
            
            if (Atom::isValidAtom($moovAType)) {
                
                $moovASize = $this->_moovBytes->readUnsignedInt($i - 4);
                
                if (($moovASize > 8) && ($moovASize + $i < ($moovSize - $moovStartOffset))) {
                    
                    try {
                        $containerLength = 0;
                        $containerString = $moovAType;
                        
                        for ($mi = count($metaDataOffsets) - 1; $mi > - 1; $mi--) {
                            
                            $containerLength = $metaDataOffsets[$mi];
                            
                            if ($i - $moovStartOffset < $containerLength && $i - $moovStartOffset + $moovASize > $containerLength) {
                                throw new Exception('bad atom nested size');
                            }
                            
                            if ($i - $moovStartOffset == $containerLength) {
                                array_pop($metaDataOffsets);
                                array_pop($metaDataStrings);
                            } else {
                                $containerString = $metaDataStrings[$mi].".".$containerString;
                            }
                        }
                        
                        if (($i - $moovStartOffset) <= $containerLength) {
                            array_push($metaDataOffsets, ($i - $moovStartOffset + $moovASize));
                            array_push($metaDataStrings, $moovAType);
                        }
                        
                        if ($moovAType != Atom::STCO_ATOM && $moovAType != Atom::CO64_ATOM) {
                            $i += 4;
                        } elseif ($moovAType == Atom::URL_ATOM || $moovAType == Atom::XML_ATOM) {
                            $i += $moovASize - 4;
                        }
                    }
                    catch(Exception $e) {
                        echo 'EXCEPTION: '.$e->getMessage();
                    }
                }
            }

            
            if ($moovAType == Atom::STCO_ATOM) {
                $moovASize = $this->_moovBytes->readUnsignedInt($i - 4);
                
                if ($i + $moovASize - $moovStartOffset > $moovSize) {
                    throw new Exception('bad atom size');
                    return;
                }
                
                $offsetCount = $this->_moovBytes->readUnsignedInt($i + 8);
                
                for ($j = 0; $j < $offsetCount; $j++) {
                    $position = ($i + 12 + $j * 4);
                    
                    $currentOffset = $this->_moovBytes->readUnsignedInt($position);
                    
                    // cause of mooving the moov-atom right before the rest of data
                    // (behind ftyp) the new offset is caluclated:
                    // current-offset + size of moov atom (box) = new offset
                    $currentOffset += $moovSize;
                    
                    $this->_moovBytes->writeBytes(Transform::toUInt32BE($currentOffset), $position + 1);
                }
                $i += $moovASize - 4;
            
            } else if ($moovAType == Atom::CO64_ATOM) {
                $moovASize = $this->_moovBytes->readUnsignedInt($i - 4);
                
                if ($i + $moovASize - $moovStartOffset > $moovSize) {
                    throw new Exception('bad atom size');
                    return;
                }
                
                $offsetCount = $this->_moovBytes->readDouble($i + 8);
                
                for ($j2 = 0; $j2 < $offsetCount; $j2++) {
                    $position = ($i + 12 + $j * 8);
                    
                    $currentOffset = $this->_moovBytes->readUnsignedInt($position);
                    
                    // cause of mooving the moov-atom right before the rest of data
                    // (behind ftyp) the new offset is caluclated:
                    // current-offset + size of moov atom (box) = new offset
                    $currentOffset += $moovSize;
                    
                    // TODO implement!
                    //$this->_moovBytes->writeBytes(Transform::toUInt64BE($currentOffset), $position+1);
                }
                $i += $moovASize - 4;
            }
        }
        
        return true;
    }


    /**
     * write the new byteorder to a new file
     *
     * write the new byteorder to a new file
     * 
     * @return  mixed True if everything wents fine, otherwise error-message as string
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  private
     */
    private function _writeFile()
    {
        // check if we need to unlink exisiting outputfile
        if (file_exists($this->_outputFile)) {
            
            // close handle
            if ($this->_fp) {
                @fclose($this->_fp);
                $this->_fp = null;
            }
            
            if (!@unlink($this->_outputFile)) {
                return 'error deleting file: '.$this->_outputFile.' outputfile "'.$this->_outputFile.'" exists (overwite = true)!';
            }
        }
        
        // open predefined output file
        if (!$fh = fopen($this->_outputFile, 'wb+')) {
            return 'error opening outputfile: '.$this->_outputFile.' for wb+ access!';
        }
        
        // put ftyp atom/box in
        if (!fwrite($fh, $this->_ftypBytes->readAllBytes())) {
            return 'error writing ftyp-atom to outputfile: '.$this->_outputFile;
        }
        
        // put moov atom in
        if (!fwrite($fh, $this->_moovBytes->readAllBytes())) {
            return 'error writing moov-atom to outputfile: '.$this->_outputFile;
        }
        
        // put rest data in
        if (!fwrite($fh, $this->_middleBytes->readAllBytes())) {
            return 'error writing other atom(s) to outputfile: '.$this->_outputFile;
        }
        
        // close handle
        fclose($fh);
        
        // everything's fine
        return true;
    }


    /**
     * factory for creating atom object instances
     *
     * factory for creating atom object instances
     * 
     * @param integer $offset The offset of the atom (in bytes)
     * @param integer $size   The size of the atom (in bytes)
     * @param string  $type   The type of the atom
     * 
     * @return  object An instance of the Atom-class
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public static function factory($offset, $size, $type)
    {
        // instanciate a new atom-object
        $atom = new Atom();
        
        // setup ...
        $atom->setOffset($offset);
        $atom->setSize($size);
        $atom->setType($type);
        
        // and give back ...
        return $atom;
    }

    
    /**
     * instance singleton requester
     *
     * method for singleton instantiation of this class
     *
     * @return  object Instance of this class
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     * @access  public
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    
    /**
     * prevents cloning of the class
     *
     * this method prevents the cloning of a class - but can be overridden by
     * child
     *
     * @return  void
     * @access  private
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     */
    private function __clone()
    {
        // empty container
    }

    
    /**
     * gets called on garbage collecting (object destroyed)
     *
     * destruct method - gets called when instance of this class get
     * collected from garbage collector. close the open filehandel on
     * destruction.
     *
     * @return  void
     * @access  public
     * @author  Benjamin Carl <phpfluesterer@googlemail.com>
     * @version 0.1
     * @since   Method available since Release 0.1
     */
    public function __destruct()
    {
        // close handle
        if ($this->_fp) {
            @fclose($this->_fp);
        }
    }
}

?>