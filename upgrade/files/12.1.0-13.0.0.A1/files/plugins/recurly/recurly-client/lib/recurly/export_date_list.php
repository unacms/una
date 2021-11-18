<?php

class Recurly_ExportDateList extends Recurly_Pager
{
  /**
   * Fetch a list of dates that have exports.
   * @param array $params An array of parameters to include with the request
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return Recurly_ExportDateList
   */
  public static function get($params = null, $client = null) {
    return new self(self::_uriWithParams('/export_dates', $params), $client);
  }

  protected function getNodeName() {
    return 'export_dates';
  }
}
