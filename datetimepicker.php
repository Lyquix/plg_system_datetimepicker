<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemDatetimepicker extends JPlugin {

	public function onAfterInitialise() {
		self::registerFunction();
	}

	public static function registerFunction() {
		JHtml::_('register', 'calendar', array('plgSystemDatetimepicker', 'calendar'));
	}

	public static function joomla2MomentJS($format) {
		// Convert Joomla format into MomentJS format
		// Year
		$format = str_replace('%y', 'YY', $format); // A two-digit representation of a year
		$format = str_replace('%Y', 'YYYY', $format); // A full numeric representation of a year, 4 digits
		// Month
		$format = str_replace('%b', 'MMM', $format); // A short textual representation of a month, three letters
		$format = str_replace('%B', 'MMMM', $format); // A full textual representation of a month
		$format = str_replace('%m', 'MM', $format); // Numeric representation of a month, with leading zeros
		// Day
		$format = str_replace('%a', 'ddd', $format); // A textual representation of a day, three letters
		$format = str_replace('%A', 'dddd', $format); // A full textual representation of the day of the week
		$format = str_replace('%e', 'D', $format); // Day of the month without leading zeros
		$format = str_replace('%d', 'DD', $format); // Day of the month, 2 digits with leading zeros
		$format = str_replace('%j', 'DDD', $format); // The day of the year (starting from 0)
		$format = str_replace('%w', 'd', $format); // Numeric representation of the day of the week, 0 (for Sunday) through 6 (for Saturday)
		// Hour
		$format = str_replace('%H', 'HH', $format); // Numeric representation of the hour, range 00 to 23 (24h format)
		$format = str_replace('%I', 'hh', $format); // Numeric representation of the hour, range 01 to 12 (12h format)
		// Minute
		$format = str_replace('%M', 'mm', $format); // Numeric representation of the minute, range 00 to 59
		// Second
		$format = str_replace('%S', 'ss', $format); // Numeric representation of the second, range 00 to 59
		// Meridian
		$format = str_replace('%p', 'a', $format); // Lowercase Ante Meridiem or Post Meridiem
		$format = str_replace('%P', 'A', $format); // Uppercase Ante Meridiem or Post Meridiem

		return $format;
	}

	public static function calendar($value, $name, $id, $format, $attribs = null) {
		// Get calendar field attributes
		$readonly = isset($attribs['readonly']) && $attribs['readonly'] == 'readonly';
		$disabled = isset($attribs['disabled']) && $attribs['disabled'] == 'disabled';
		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		}
		
		// Get plugin params
		static $done = array(), $options = null;
		$plugin = JPluginHelper::getPlugin('system', 'datetimepicker');
		$params = new JRegistry($plugin -> params);

		// $display_format: date/time format to be displayed to user
		// Default format: uses the format passed by calendar field
		if ($params -> get('use_default', 0)) $display_format = $format;
		else {
			// Custom format: uses the format configured in plugin
			// Detect if the field format includes date and/or time
			if (strpos($format, '%Y') !== false || strpos($format, '%y') !== false || strpos($format, '%b') !== false || strpos($format, '%B') !== false || strpos($format, '%m') !== false || strpos($format, '%a') !== false || strpos($format, '%A') !== false || strpos($format, '%e') !== false || strpos($format, '%j') !== false || strpos($format, '%d') !== false || strpos($format, '%w') !== false){
				$display_format = $params -> get('date_format', '%Y-%m-%d') . ' ';
			}
			if (strpos($format, '%H') !== false || strpos($format, '%I') !== false || strpos($format, '%M') !== false || strpos($format, '%S') !== false || strpos($format, '%p') !==false || strpos($format, '%P') !== false){
				$display_format .= $params -> get('time_format', '%H:%M:%S');
			}
		}

		// Convert formats from Joomla to MomentJS
		$format = self::joomla2MomentJS($format);
		$display_format = self::joomla2MomentJS($display_format);


		// Add MomentJS
		JHtml::_('script', 'media/plg_system_datetimepicker/js/moment-with-locales.js', true);
		
		// Start preparing custom script for field
		// Reformat input text value to display format
		$js = "jQuery(document).ready(function(){" .
					"var pickerField = jQuery('#dateTimePicker" . $id . "');" .
					"pickerField.val(moment('" . $value . "', '" . $format . "').format('" . $display_format . "'));";

		if (!$readonly && !$disabled) {

			// Add datetimepicker stylesheets and scripts
			JHtml::_('stylesheet', 'media/plg_system_datetimepicker/css/bootstrap-datetimepicker.css');
			JHtml::_('script', 'media/plg_system_datetimepicker/js/bootstrap-datetimepicker.js', true);
			
			// Render js trigger
			// Load date-time picker
			// Listen for changes to update hidden field value
			$js .= 		"var valueField = jQuery('#" . $id . "');" .
						"pickerField.datetimepicker({" .
							"format: '" . $display_format . "', " .
							"dayViewHeaderFormat: '" . $params -> get('header_format', 'MMMM YYYY') . "', " .
							"viewMode: '" . $params -> get('view_mode', 'days') . "', " .
							"calendarWeeks: " . ($params -> get('week_number', 0) ? 'true' : 'false') . ", " .
							"toolbarPlacement: '" . $params -> get('toolbar_position', 'default') . "', " .
							"showTodayButton: " . ($params -> get('show_today', 0) ? 'true' : 'false') . ", " .
							"showClear: " . ($params -> get('show_clear', 0) ? 'true' : 'false') . ", " .
							"showClose: " . ($params -> get('show_close', 0) ? 'true' : 'false') . ", " .
							"keepOpen: " . ($params -> get('keep_open', 0) ? 'true' : 'false') . ", " .
							"inline: " . ($params -> get('inline', 0) ? 'true' : 'false') . ", " .
							"debug: " . ($params -> get('debug', 0) ? 'true' : 'false') . 
						"});" .
						"pickerField.on('dp.change', function(){" .
							"valueField.val(moment(pickerField.val(), '" . $display_format . "').format('" . $format . "'));" .
						"});";
		}

		// Comple custom script and add to header
		$js .= 	"});";
		JFactory::getDocument() -> addScriptDeclaration($js);

		// Render fields
		return '<div style="position:relative;">' .
					'<input type="text" id="dateTimePicker' . $id . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />' .
					'<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" />' .
				'</div>';

	}
}
