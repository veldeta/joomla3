/*
 * http://blog.stevenlevithan.com/archives/date-time-format
 * Date Format 1.2.3
 * (c) 2007-2009 Steven Levithan <stevenlevithan.com>
 * MIT license
 *
 * Includes enhancements by Scott Trenda <scott.trenda.net>
 * and Kris Kowal <cixar.com/~kris.kowal/>
 *
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */

var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"<?php echo JText::_("SUN"); ?>", "<?php echo JText::_("MON"); ?>", "<?php echo JText::_("TUE"); ?>", "<?php echo JText::_("WED"); ?>", "<?php echo JText::_("THU"); ?>", "<?php echo JText::_("FRI"); ?>", "<?php echo JText::_("SAT"); ?>",
		"<?php echo JText::_("SUNDAY"); ?>", "<?php echo JText::_("MONDAY"); ?>", "<?php echo JText::_("TUESDAY"); ?>", "<?php echo JText::_("WEDNESDAY"); ?>", "<?php echo JText::_("THURSDAY"); ?>", "<?php echo JText::_("FRIDAY"); ?>", "<?php echo JText::_("SATURDAY"); ?>"
	],
	monthNames: [
		"<?php echo JText::_("JANUARY_SHORT"); ?>", "<?php echo JText::_("FEBRUARY_SHORT"); ?>", "<?php echo JText::_("MARCH_SHORT"); ?>", "<?php echo JText::_("APRIL_SHORT"); ?>", "<?php echo JText::_("MAY_SHORT"); ?>", "<?php echo JText::_("JUNE_SHORT"); ?>", "<?php echo JText::_("JULY_SHORT"); ?>", "<?php echo JText::_("AUGUST_SHORT"); ?>", "<?php echo JText::_("SEPTEMBER_SHORT"); ?>", "<?php echo JText::_("OCTOBER_SHORT"); ?>", "<?php echo JText::_("NOVEMBER_SHORT"); ?>", "<?php echo JText::_("DECEMBER_SHORT"); ?>",
		"<?php echo JText::_("JANUARY"); ?>", "<?php echo JText::_("FEBRUARY"); ?>", "<?php echo JText::_("MARCH"); ?>", "<?php echo JText::_("APRIL"); ?>", "<?php echo JText::_("MAY"); ?>", "<?php echo JText::_("JUNE"); ?>", "<?php echo JText::_("JULY"); ?>", "<?php echo JText::_("AUGUST"); ?>", "<?php echo JText::_("SEPTEMBER"); ?>", "<?php echo JText::_("OCTOBER"); ?>", "<?php echo JText::_("NOVEMBER"); ?>", "<?php echo JText::_("DECEMBER"); ?>"
	]
};

// For convenience...
Date.prototype._format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

function gi_php_date_format_to_mask(format){
	var formatMap = {
		d: 'dd',
		D: 'ddd',
		j: 'd',
		l: 'dddd',
		//N: 'E',//TODO  1 luned??
		S: 'S',
		//w: 'd', //0 per domenica fino a 6 sabato
		/*z: function(){ //giorno dell'anno partendo da 0
			return this.format('DDD') - 1;
		},*/
		//W: 'W',//numero settimana
		F: 'mmmm',
		m: 'mm',
		M: 'mmm',
		n: 'm',
		//t:  //num giorni nel mese
		/*
		L: function(){
			return this.isLeapYear() ? 1 : 0;
		},
		o: 'GGGG',*/
		Y: 'yyyy',
		y: 'yy',
		a: 'tt',
		A: 'TT',
		/*B: function(){
			var thisUTC = this.clone().utc(),
				// Shamelessly stolen from http://javascript.about.com/library/blswatch.htm
				swatch = ((thisUTC.hours()+1) % 24) + (thisUTC.minutes() / 60) + (thisUTC.seconds() / 3600);
			return Math.floor(swatch * 1000 / 24);
		},*/
		g: 'h',
		G: 'H',
		h: 'hh',
		H: 'HH',
		
		i: 'MM',
		s: 'ss',
		//u: '[u]', 
		//e: '[e]', // moment does not have this
		//I: function(){
		O: 'o',
		//P: 'Z',
		T: 'Z', // deprecated in moment
		//Z: function(){
		c: 'yyyy-mm-dd HH:MM:ss',
		//Thu, 21 Dec 2000 16:01:07 +0200
		r: 'ddd, dd mmm yyyy HH:MM:ss',
		//U
	},
	formatEx = /[dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]/g;

	return format.replace(formatEx, function(phpStr){
	  return formatMap[phpStr];
	});
}