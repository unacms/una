<?php

class ChargeBee_AdvanceInvoiceScheduleFixedIntervalSchedule extends ChargeBee_Model
{
  protected $allowed = array('end_schedule_on', 'number_of_occurrences', 'days_before_renewal', 'end_date', 'created_at', 'terms_to_charge');

}

?>