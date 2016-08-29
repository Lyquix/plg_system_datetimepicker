# plg_system_datetimepicker

Replaces the default Joomla date picker with good-looking and full-featured Bootstrap Datepicker from https://eonasdan.github.io/bootstrap-datetimepicker/

It separates presentation/display format from data/DB format.

Supports all Joomla date-time format strings as specified in https://github.com/joomla/joomla-cms/blob/staging/media/system/js/calendar-uncompressed.js#L1741-L1774

Some features:

* Allows for time picking
* Several configuration options available in plugin settings
  * Custom header format
  * Week number
  * Show buttons for today, clear and close
  * Keep open after selecting date
  * Inline rendering
  * Debug mode

Wishlist:

* Integrare Joomla locale (I started working on it but moments.js locales don't match directly with Joomla's standard structure)

### Installation

* Download `plg_system_datetimepicker-x.x.x.zip` from Releases
* Install using Joomla Installer
* Enable plugin **System - Lyquix Date-Time Picker**
* Using default settings the only change you will see is the new date-time picker

### Options

* Header Format: enter a custom date-time format to show at the top of the picker, using MomentJS format
* Week Number: show/hide week number in the calendar
* View Mode: select the default view of picker
* Use Default Format: use the date-time format passed by Joomla, or override with custom date and time format
* Toolbar Position
* Show/hide buttons for Today, Clear, Close
* Keep open after date selection
* Show inline instead of overlay
* Debug: doesn't close picker when clicking outside
