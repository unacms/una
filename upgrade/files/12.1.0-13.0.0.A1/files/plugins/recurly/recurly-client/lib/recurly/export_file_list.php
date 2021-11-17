<?php

class Recurly_ExportFileList extends Recurly_Pager
{
  /**
   * Fetch a list of export files for a given date.
   *
   * @param string $date  Date in YYYY-MM-DD format.
   * @param array $params An array of parameters to include with the request
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return Recurly_ExportFileList
   */
  public static function get($date, $params = null, $client = null) {
    return new self(self::_uriWithParams('/export_dates/' . rawurlencode($date) . '/export_files', $params), $client);
  }

  protected function getNodeName() {
    return 'export_files';
  }
}
