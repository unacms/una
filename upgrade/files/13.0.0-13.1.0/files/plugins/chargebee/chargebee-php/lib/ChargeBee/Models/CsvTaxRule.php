<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class CsvTaxRule extends Model
{

  protected $allowed = [
    'taxProfileName',
    'country',
    'state',
    'zipCode',
    'zipCodeStart',
    'zipCodeEnd',
    'tax1Name',
    'tax1Rate',
    'tax1JurisType',
    'tax1JurisName',
    'tax1JurisCode',
    'tax2Name',
    'tax2Rate',
    'tax2JurisType',
    'tax2JurisName',
    'tax2JurisCode',
    'tax3Name',
    'tax3Rate',
    'tax3JurisType',
    'tax3JurisName',
    'tax3JurisCode',
    'tax4Name',
    'tax4Rate',
    'tax4JurisType',
    'tax4JurisName',
    'tax4JurisCode',
    'status',
    'timeZone',
    'validFrom',
    'validTill',
    'serviceType',
    'ruleWeight',
    'overwrite',
  ];



  # OPERATIONS
  #-----------

  public static function create($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("csv_tax_rules"), $params, $env, $headers);
  }

 }

?>