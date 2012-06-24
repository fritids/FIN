<?php
/*
Plugin Name: wp-days-ago
Version: 2.0.2
Plugin URI: http://www.vegard.net/archives/3781/
Author: Vegard Skjefstad
Author URI: http://www.vegard.net/
Description: Displays the number of years and days since a post or page was written. 

Copyright 2008-2011 Vegard Skjefstad vegard@vegard.net

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function wp_days_ago ($mode = 0, $prepend = "", $append = "",
		$texts = array("Today", "Yesterday", "One week ago", "days ago", "year",
			"years", "ago", "day ago", "days ago", "Just now", "One minute ago", "minutes ago", "1 hour ago", "hours ago", "Some time in the future")) {

		$days = round((strtotime(date("Y-m-d", gmmktime() + (get_option('gmt_offset') * 3600))) - strtotime(date("Y-m-d", get_the_time("U")))) / 86400);

		$minutes = round((strtotime(date("Y-m-d H:i", gmmktime() + (get_option('gmt_offset') * 3600))) - strtotime(date("Y-m-d H:i", get_the_time("U")))) / 60);
		
		$output = $prepend;
				
		if($minutes < 0) {
			$output .= $texts[14];
		} else if($mode == 0 && $minutes < 1440) {
			if($minutes == 0) {
				$output .= $texts[9];
			} else if($minutes == 1) {
				$output .= $texts[10];
			} else if($minutes < 60) {
				$output .= $minutes . " " . $texts[11];
			} else if($minutes < 120) {
				$output .= $texts[12];
			} else {
				$output .= floor($minutes / 60) . " " . $texts[13];
			}
		} else {
			if($days == 0)
				$output = $output . $texts[0];
			elseif($days == 1)
				$output = $output . $texts[1];
			elseif($days == 7)
				$output = $output . $texts[2];
			else {
				$years = floor($days / 365);
				if($years > 0) {
					if($years == 1)
						$yearappend = $texts[4];
					else
						$yearappend = $texts[5];

					$days = $days - (365 * $years);
					if($days == 0)
						$output = $output . $years . " " . $yearappend . " " . $texts[6];
					else if($days == 1)
						$output = $output . $years . " " . $yearappend . ", " . $days . " " . $texts[7];
					else
						$output = $output . $years . " " . $yearappend . ", " . $days . " " . $texts[8];
				} else {
					$output = $output . $days . " " . $texts[3];
				}
			}
		}

		$output = $output . $append;
		
		echo $output;
}

add_filter('Posts', 'wp_days_ago');
?>