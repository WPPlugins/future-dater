=== Future Dater ===
Contributors: SWCP
Tags: date, future, shortcode, quarter
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 0.9.1

Adds a shortcode to include a specific kind of future date (such as
"first day of the next quarter" or "Last day of next month").

== Description ==

Future Dater is a simple plugin to provide a shortcode to include
certain types of future (or past) dates in your posts or pages. The
type of dates supported are "beginning of next quarter", "last day
of this quarter", "first day of next month", etc.

*Features*

There are 4 arguments to the [futuredate] shortcode:

* unit - Required.  Which part of the current date you want to
  modify. Valid values: "month" and "quarter".

* relunit - Optional.  Whether you want reference the first day or
  last day of the unit, or the same day as the current date.  Valid
  values: "first", "last", or none.

* count - Required.  How much to modify the current date, in units
  specified by the unit argument.  Most commonly 1 (for next month
  or next quarter) or 0 (for the current month or quarter).  Also
  accepts negative values.

* format - Optional.  PHP date-formatting string to specify how the
  date should be printed.  Most common value: `Y-m-d'.

*Examples*

* The end of this month:
    [futuredate unit=month relunit=last count=0 format="Y-m-d H:i:s"]

* The end of this quarter:
    [futuredate unit=quarter relunit=last count=0 format="Y-m-d"]

* The first day of next quarter:
    [futuredate unit=quarter relunit=first count=1 format="Y-m-d"]

* The end of the quarter, 5 quarters from now:
    [futuredate unit=quarter relunit=last count=5 format="Y-m-d"]

* The end of previous quarter:
    [futuredate unit=quarter relunit=last count=-1 format="Y-m-d"]

* First day of next month:
    [futuredate unit=month relunit=first count=1 format="Y-m-d"]

* Two months from now:
    [futuredate unit=month count=2 format="Y-m-d"]

== Installation ==

1. Upload the entire `future-dater` folder to the `/wp-content/plugins/`
directory.
1. Activate the plugin.  There is no configuration or setup to do.
1. Add [futuredate] shortcodes to your Posts or Pages.

Support:

If you have questions or problems with this plugin, please email futuredater at swcp dot com.

== Changelog ==

= 0.9 =
* Initial version

