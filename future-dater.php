<?php
/**
 * @package Future_Dater
 * @version 0.9
 */
/*
Plugin Name: Future Dater
Description: Adds a shortcode to include a specific kind of future date.
Author: Mark Costlow
Version: 0.9
Author URI: http://www.swcp.com/future-dater-wordpress-plugin
*/

add_shortcode('futuredate', 'future_date');

//
// shortcode to insert a future date (or past date)
//
// Shortcode arguments:
//   unit: month, quarter
//   relunit: first, last, or none
//   count: a number, usually 0 or 1.
//
// Examples: today is May 20, 2013
//   unit=month, relunit=first, count=1: answer => June 1, 2013
//   unit=quarter, relunit=last, count=0: answer => June 30, 2013
//   unit=quarter, relunit=first, count=1: answer => July 1, 2013
//   unit=month, relunit="", count=2: answer => July 20, 2013
//   unit=quarter, relunit="", count=1: answer => August 20, 2013
//
function future_date($atts="") {
    extract(shortcode_atts(array(
                           'count' => '',
                           'unit' => '',
                           'relunit' => '',
                           'format' => 'Y-m-d'
                           ), $atts));
    $localtime = localtime(time(), true);

    // ignoring leap years
    $lastday = array(1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
		     7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31);

    // Ok, we have the current date and we know how they want to
    // modify it.  Apply those changes, then build a new date object.
    // Kinda tortured that we have to go through createFromFormat, but
    // that's PHP.

    $localtime['tm_year'] += 1900;
    $localtime['tm_mon'] ++;      // 0-11 => 1-12

    if ($relunit == "first") 
      {
	if ($unit == "month") 
	  {
	    add_months($localtime, $count);
	    $localtime['tm_mday'] = 1;
	  }
	elseif ($unit == "quarter") 
	  {
	    // Need to figure out what quarter we are in, set date to first
	    // day of that quarter, then add 3 months per quarter we want
	    // to increment.
	    $localtime['tm_mon'] = first_quarter_month($localtime);
	    add_months($localtime, $count * 3);
	    $localtime['tm_mday'] = 1;
	  }
	else 
	  {
	    // invalid.  bonk the date so it's obvious there is a problem.
	    $localtime['tm_year'] = 1;
	    $localtime['tm_mon'] = 0;
	    $localtime['tm_mday'] = 1;
	  }
      }
    elseif ($relunit == "last") 
      {
	if ($unit == "month") 
	  {
	    add_months($localtime, $count);
	    $localtime['tm_mday'] = $lastday[$localtime['tm_mon']];
	  }
	elseif ($unit == "quarter") 
	  {
	    // Need to figure out what quarter we are in, set date to last
	    // day of that quarter, then add 3 months per quarter we want
	    // to increment.
	    $localtime['tm_mon'] = last_quarter_month($localtime);
	    add_months($localtime, $count * 3);
	    $localtime['tm_mday'] = $lastday[$localtime['tm_mon']];
	  }
	else 
	  {
	    // invalid.  bonk the date so it's obvious there is a problem.
	    $localtime['tm_year'] = 1;
	    $localtime['tm_mon'] = 0;
	    $localtime['tm_mday'] = 1;
	  }
      }
    else
      {
	// move ahead X months/quarters, keeping the same day-of-month
	if ($unit == "month") 
	  {
	    add_months($localtime, $count);
	  }
	elseif ($unit == "quarter") 
	  {
	    add_months($localtime, $count * 3);
	  }
	else 
	  {
	    // invalid.  bonk the date so it's obvious there is a problem.
	    $localtime['tm_year'] = 1;
	    $localtime['tm_mon'] = 0;
	    $localtime['tm_mday'] = 1;
	  }

	// Adjust the day if we landed in a month with fewer days
	if ($localtime['tm_mday'] > $lastday[$localtime['tm_mon']]) 
	  {
	    $localtime['tm_mday'] = $lastday[$localtime['tm_mon']];
	  }
      }
    
    // tm_mon has been tweaked to be in the range 1-12 already
    //print "<pre>\nAFTER:";    print_r($localtime);    print "</pre>\n";
    $newdatestr = sprintf("%d-%d-%d", $localtime['tm_year'],
			 $localtime['tm_mon'],
			 $localtime['tm_mday']);
    //print "<pre>newdatestr=$newdatestr\n</pre>\n";
    
    $date = DateTime::createFromFormat('Y-n-j', $newdatestr);

    //print "<pre>new formatted date: " . $date->format($format) . "\n</pre>\n";
    return $date->format($format);
}

// Return the first month of the quarter (e.g. 4 for 2nd quarter)
function first_quarter_month($t) {
  $mon = $t['tm_mon'];
  if ($mon <= 3) 
    {
      return 1;
    }
  elseif ($mon <= 6) 
    {
      return 4;
    }
  elseif ($mon <= 9) 
    {
      return 7;
    }
  elseif ($mon <= 12) 
    {
      return 10;
    }
  // ?
  return 99;
}

// Return the last month of the quarter (e.g. 6 for 2nd quarter)
function last_quarter_month($t) {
  $mon = $t['tm_mon'];
  if ($mon <= 3) 
    {
      return 3;
    }
  elseif ($mon <= 6) 
    {
      return 6;
    }
  elseif ($mon <= 9) 
    {
      return 9;
    }
  elseif ($mon <= 12) 
    {
      return 12;
    }

  // ?
  return 99;
}

// Increment the month in this date.  If it crosses a year boundary, adjust.
// Handles negative increments.
// No return value.  Modifies $t.
function add_months(&$t, $m)
{
  $t['tm_mon'] += $m;
  if ($t['tm_mon'] > 12) 
    {
      $t['tm_mon'] -= 12;
      $t['tm_year'] ++;
    }
  elseif ($t['tm_mon'] < 1) 
    {
      $t['tm_mon'] += 12;
      $t['tm_year'] --;
    }
  return;
}


?>
