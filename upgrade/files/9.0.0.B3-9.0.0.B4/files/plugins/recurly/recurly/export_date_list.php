<?php

class Recurly_ExportDateList extends Recurly_Pager
{
  /**
   * Fetch a list of dates that have exports.
   */
  public static function get($params = null, $client = null) {
    return new self(self::_uriWithParams('/export_dates', $params), $client);
  }

  protected function getNodeName() {
    return 'export_dates';
  }
}
