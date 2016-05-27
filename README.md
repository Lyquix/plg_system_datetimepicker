# plg_system_datetimepicker

Replaces the default Joomla date picker with good-looking and full-featured Bootstrap Datepicker from https://eonasdan.github.io/bootstrap-datetimepicker/

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
* Display nice, readable format, even if different to value format