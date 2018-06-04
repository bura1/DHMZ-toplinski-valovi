<?php

/*
Plugin Name: Toplinski valovi DHMZ
Description: Prikazuje tablicu upozorenja na toplinske valove sa DHMZ-a. Potrebno zaljepiti shortcode <strong>[toplinski_valovi]</strong> u objavu ili stranicu.
Version: 1.2
Author: TB
*/

function toplinski_valovi_tablica() {

	$url = 'http://vrijeme.hr/toplinskival_5.xml';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml_raw = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($xml_raw);

	$date1 = new DateTime;
	$date2 = new DateTime('+1 day');
	$date3 = new DateTime('+2 day');
	$date4 = new DateTime('+3 day');
	$date5 = new DateTime('+4 day');

	$day1 = new DateTime;
	$day2 = new DateTime('+1 day');
	$day3 = new DateTime('+2 day');
	$day4 = new DateTime('+3 day');
	$day5 = new DateTime('+4 day');

	function dan($day) {
		switch ($day) {
			case 'Monday':
				return 'Ponedjeljak';
				break;
			case 'Tuesday':
				return 'Utorak';
				break;
			case 'Wednesday':
				return 'Srijeda';
				break;
			case 'Thursday':
				return 'Četvrtak';
				break;
			case 'Friday':
				return 'Petak';
				break;
			case 'Saturday':
				return 'Subota';
				break;
			case 'Sunday':
				return 'Nedjelja';
				break;
		}
	}

	foreach ($xml->section as $element) {
		if ($element->station) {

			$tablica = '<div style="overflow-x:auto;"><table style="max-width:496px; margin:0 auto; cellspacing="0">';

			$tablica.= '<tr style="font-size:13px;">';
				$tablica.= '<td style="border:none;"></td><td style="border:none;">'.dan($day1->format("l")).'<br>'.$date1->format("j.n.Y.").'</td><td style="border:none;">'.dan($day2->format("l")).'<br>'.$date2->format("j.n.Y.").'</td><td style="border:none;">'.dan($day3->format("l")).'<br>'.$date3->format("j.n.Y.").'</td><td style="border:none;">'.dan($day4->format("l")).'<br>'.$date4->format("j.n.Y.").'</td><td style="border:none;">'.dan($day5->format("l")).'<br>'.$date5->format("j.n.Y.").'</td>';
			$tablica.= '</tr>';

			foreach ($element->station as $gradovi) {
				$tablica.= '<tr>';
				$tablica.= '<td style="border:none;">';
				$tablica.= $gradovi->attributes()->name;
				$tablica.= '</td>';
				foreach ($gradovi->param as $podatci) {
					if ($podatci->attributes()->value == "G") {
						$tablica.= '<td style="background-color:#32CD32; width:80px; height:35px; border:1px solid white;"></td>';
					} elseif ($podatci->attributes()->value == "Y") {
						$tablica.= '<td style="background-color:#FFFF00; max-width:80px; height:35px;"></td>';
					} elseif ($podatci->attributes()->value == "O") {
						$tablica.= '<td style="background-color:#FFA500; max-width:80px; height:35px;"></td>';
					} elseif ($podatci->attributes()->value == "R") {
						$tablica.= '<td style="background-color:#FF0000; max-width:80px; height:35px;"></td>';
					}
				}
				$tablica.= '</tr>';
			}
			$tablica.= '</table></div>';

			$tablica.= '<div style="max-width:690px; margin:30px auto; display:table; font-size: 12px;">
				<div style="float:left;padding:0 15px;width:172px;text-align:center;"><div style="width:30px;height:20px;background-color:#32CD32;margin:0 auto;"></div> nema opasnosti</div>
				<div style="float:left;padding:0 15px;width:172px;text-align:center;"><div style="width:30px;height:20px;background-color:#FFFF00;margin:0 auto;"></div> umjerena opasnost</div>
				<div style="float:left;padding:0 15px;width:172px;text-align:center;"><div style="width:30px;height:20px;background-color:#FFA500;margin:0 auto;"></div> velika opasnost</div>
				<div style="float:left;padding:0 15px;width:172px;text-align:center;"><div style="width:30px;height:20px;background-color:#FF0000;margin:0 auto;"></div> vrlo velika opasnost</div>
			</div>';
		}
		return $tablica;
	}
}

add_shortcode('toplinski_valovi', 'toplinski_valovi_tablica');

?>