<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * BxDolCron is parent class for all cron jobs, except the cases when the code is evaluated directly.
 *
 * periodic/cron.php file is used for cron jobs. It is started every minute and run cron jobs.
 * The file runs jobs at regular intervals, the jobs are listed in `sys_cron_jobs` table.
 *
 * Fields shark_cron_jobs table:
 *  id - key for the table
 *  name - job name to be executed
 *  time - format of entries are five fields of numbers specifying the minute,
 *              hour, day of the month, month and day of the week that a task must be executed.
 * @code
 *              * * * * *
 *              | | | | |
 *              | | | | +--- day of week(0-6 with 0=Sunday)
 *              | | | +----- month(1-12)
 *              | | +------- day of month(1-31)
 *              | +--------- hour(0-23)
 *              +----------- minute(0-59)
 * @endcode
 *
 *  class - class name which will run
 *  file - path to class file
 *  service_call - serialized service call which will be run
 *
 * The time numbers can be given as a comma separated list of simple numbers,
 * ranges("2-5" is the same as "2,3,4,5"). A single "*" can be used in a field to indicate all
 * valid numbers in that field, so it translates to "always". If a given time is valid in all five
 * fields then a module function is executed. Here are a few examples that illustrate the possibilities:
 *
 * @code
 *  will run at 16:10:
 *  10 16
 *  will run at 2:00 on saturday:
 *  0 2 * * 6
 *  will run at midnight on new years:
 *  0 0 1 1 0
 *  will run every 15 minutes:
 *  *\/15
 *  will run at 22:00 on work weekdays:
 *  0 22 * * 1-5
 *  will run each 23 minutes, 2:00, 4:00 ..., everyday
 *  23 0-23/2
 * @endcode
 *
 * Example add new cron job:
 *
 * 1. Create new class inherited from "BxDolCron" and add method "processing"
 *
 * @code
 *      class BxDolCronMy extends BxDolCron {
 *
 *          function processing()
 *          {
 *              // insert code
 *          }
 *      }
 * @endcode
 *
 * 2. Add record in `sys_cron_jobs` table
 *
 * @see an example of BxDolCronPruning, BxDolCronStorage.
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
abstract class BxDolCron extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    abstract public function processing();
}

/** @} */
