<?php

/*

Based on the JavaScript

Lunar Phase Calculator

by Stephen R. Schmitt

http://home.att.net/~srschmitt/script_moon_phase.html

which was adapted from a BASIC program from the Astronomical Computing column of Sky & Telescope, April 1994

*/

function isdayofmonth($month, $day, $year)

{

    $dim = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    if ($month != 2)

    {

        if (1 <= $day && $day <= $dim[$month - 1])

            return true;

        else

            return false;

    }

    $feb = $dim[1];

    if (isleapyear($year))

    {

        $feb++;// is leap year

    }

    if (1 <= $day && $day <= $feb)

    {

        return true;

    }

    return false;

}

function isleapyear($year)

{

    $a = floor($year - 4 * floor($year / 4));

    $b = floor($year - 100 * floor($year / 100));

    $c = floor($year - 400 * floor($year / 400));

    // possible leap year

    if ($a == 0)

    {

        if ($b == 0 && $c != 0)

            return false;// not leap year

        else

            return true;// is leap year

    }

    return false;

}

// compute moon position and phase

function moon_posit($month = null, $day = null, $year = null)

{

    $moon = array();

    if(!isdayofmonth($month, $day, $year))

    {

        $moon['errors'] = 'Invalid date';

    }

    else

    {

        $moon['errors'] = null;

        $age = 0.0;// Moon's age in days from New Moon

        $distance = 0.0;// Moon's distance in Earth radii

        $latitude = 0.0;// Moon's ecliptic latitude in degrees

        $longitude = 0.0;// Moon's ecliptic longitude in degrees

        $phase = '';

        $zodiac = '';

        $YY = 0;

        $MM = 0;

        $K1 = 0;

        $K2 = 0;

        $K3 = 0;

        $JD = 0;

        $IP = 0.0;

        $DP = 0.0;

        $NP = 0.0;

        $RP = 0.0;

        // calculate the Julian date at 12h UT

        $YY = $year - floor((12 - $month) / 10);

        $MM = $month + 9;

        if ($MM >= 12)

        {

            $MM = $MM - 12;

        }

        $K1 = floor(365.25 * ($YY + 4712));

        $K2 = floor(30.6 * $MM + 0.5);

        $K3 = floor(floor(($YY / 100) + 49) * 0.75) - 38;

        $JD = $K1 + $K2 + $day + 59;// for dates in Julian calendar

        if ($JD > 2299160)

        {

            $JD = $JD - $K3;// for Gregorian calendar

        }

        // calculate moon's age in days

        $IP = normalize(($JD - 2451550.1) / 29.530588853);

        $age = $IP * 29.53;

        if ($age <  1.84566)

            $phase = 'NEW MOON';

        else if ($age <  5.53699)

            $phase = 'Evening crescent';

        else if ($age <  9.22831)

            $phase = 'First quarter';

        else if ($age < 12.91963)

            $phase = 'Waxing gibbous';

        else if ($age < 16.61096)

            $phase = 'FULL MOON';

        else if ($age < 20.30228)

            $phase = 'Waning gibbous';

        else if ($age < 23.99361)

            $phase = 'Last quarter';

        else if ($age < 27.68493)

            $phase = 'Morning crescent';

        else

            $phase = 'NEW MOON';

        $IP = $IP * 2 * pi();// Convert phase to radians

        // calculate moon's distance

        $DP = 2 * pi() * normalize(($JD - 2451562.2) / 27.55454988);

        $distance = 60.4 - 3.3 * cos($DP) - 0.6 * cos(2 * $IP - $DP) - 0.5 * cos(2 * $IP);

        // calculate moon's ecliptic latitude

        $NP = 2 * pi() * normalize(($JD - 2451565.2) / 27.212220817);

        $latitude = 5.1 * sin($NP);

        // calculate moon's ecliptic longitude

        $RP = normalize(($JD - 2451555.8) / 27.321582241);

        $longitude = 360 * $RP + 6.3 * sin($DP) + 1.3 * sin(2 * $IP - $DP) + 0.7 * sin(2 * $IP);

        if ($longitude <  33.18)

            $zodiac = 'Pisces';

        else if ($longitude <  51.16)

            $zodiac = 'Aries';

        else if ($longitude <  93.44)

            $zodiac = 'Taurus';

        else if ($longitude < 119.48)

            $zodiac = 'Gemini';

        else if ($longitude < 135.30)

            $zodiac = 'Cancer';

        else if ($longitude < 173.34)

            $zodiac = 'Leo';

        else if ($longitude < 224.17)

            $zodiac = 'Virgo';

        else if ($longitude < 242.57)

            $zodiac = 'Libra';

        else if ($longitude < 271.26)

            $zodiac = 'Scorpio';

        else if ($longitude < 302.49)

            $zodiac = 'Sagittarius';

        else if ($longitude < 311.72)

            $zodiac = 'Capricorn';

        else if ($longitude < 348.58)

            $zodiac = 'Aquarius';

        else

            $zodiac = 'Pisces';

        // so longitude is not greater than 360!

        if ($longitude > 360)

            $longitude = $longitude - 360;

        $moon['age'] = round($age, 2);
        $moon['illumination']= (1 - cos(deg2rad($moon['age']))) / 2;  
        $moon['distance'] = round($distance, 2);

        $moon['latitude'] = round($latitude, 2);

        $moon['longitude'] = round($longitude, 2);

        $moon['phase'] = $phase;

        $moon['zodiac'] = $zodiac;

    }

    return $moon;

}

// normalize values to range 0...1

function normalize($v)

{

    $v = $v - floor($v);

    if ($v < 0)

    {

        $v++;

    }

    return $v;

}

?>
