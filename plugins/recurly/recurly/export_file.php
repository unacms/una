<?php

class Recurly_ExportFile extends Recurly_Resource
{
  /**
   * Look up a file by date and name.
   * @param string date
   * @param string name
   * @return Recurly_ExportFile
   */
  public static function get($date, $name, $client = null) {
    return self::_get('/export_dates/' . rawurlencode($date) . '/export_files/' . rawurlencode($name), $client);
  }

  protected function getNodeName() {
    return 'export_file';
  }

  protected function getWriteableAttributes() {
    return array();
  }

  /**
   * Download the file.
   *
   * @param resource  $file_pointer Resourced returned from fopen() with write mode.
   */
  function download($file_pointer) {
    if (empty($this->download_url)) {
      $this->_save(Recurly_Client::GET, $this->getHref());
    }
    $this->_client->getFile($this->download_url, $file_pointer);
  }
}

