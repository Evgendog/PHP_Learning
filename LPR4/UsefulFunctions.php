<?php
// Get browser name and version, OS name [and version], used http_user_agent
// and pattern that was used to determine version of browser
function getBroAndPlatInfo($u_agent = "default")
{
    $default_user_agent = $_SERVER['HTTP_USER_AGENT'];
    $user_agent = $u_agent === "default" ? $default_user_agent : $u_agent;
    $bro_name = "Unknown"; // Correct browser name
    $ub = ""; // Browser name in $_SERVER['HTTP_USER_AGENT']
    $bro_version = "";
    $os_name = "Unknown";

    // First get the OS
    if (preg_match('/Linux|linux/i', $user_agent)) {
        $os_name = "Linux";
    } elseif (preg_match('/Mac OS|Macintosh|mac os x/i', $user_agent)) {
        $os_name = "Mac";
        if (preg_match('/Mac OS|Macintosh/i', $user_agent)) {
            $os_name .= " OS X";
        } else if (preg_match('/mac_powerpc/i', $user_agent)) {
            $os_name .= " OS 9";
        }
    } elseif (preg_match('/Windows|windows|Windows NT|win32/i', $user_agent)) {
        $os_name = "Windows";
        if (preg_match('/Windows NT 10.0/i', $user_agent)) {
            $os_name .= " 10";
        } elseif (preg_match('/Windows NT 6.3/i', $user_agent)) {
            $os_name .= " 8.1";
        } elseif (preg_match('/Windows NT 6.2/i', $user_agent)) {
            $os_name .= " 8";
        } elseif (preg_match('/Windows NT 6.1/i', $user_agent)) {
            $os_name .= " 7";
        } elseif (preg_match('/Windows NT 6.0/i', $user_agent)) {
            $os_name .= " Vista";
        } elseif (preg_match('/Windows NT 5.2/i', $user_agent)) {
            $os_name .= " XP Professional x64 Edition";
        } elseif (preg_match('/Windows NT 5.1/i', $user_agent)) {
            $os_name .= " XP";
        } elseif (preg_match('/Windows 4.90/i', $user_agent)) {
            $os_name .= " ME";
        } elseif (preg_match('/Windows NT 5.0/i', $user_agent)) {
            $os_name .= " 2000";
        } elseif (preg_match('/Windows 4.10/i', $user_agent)) {
            $os_name .= " 98";
        } elseif (preg_match('/Windows NT 4.0/i', $user_agent)) {
            $os_name .= " NT 4.0";
        } elseif (preg_match('/Windows 4.00/i', $user_agent)) {
            $os_name .= " 95";
        } elseif (preg_match('/Windows NT 3.51/i', $user_agent)) {
            $os_name .= " NT 3.51";
        } elseif (preg_match('/Windows NT 3.5/i', $user_agent)) {
            $os_name .= " NT 3.5";
        } elseif (preg_match('/Windows 3.2/i', $user_agent)) {
            $os_name .= " 3.2";
        } elseif (preg_match('/Windows 3.11/i', $user_agent)) {
            $os_name .= " for Workgroups 3.11";
        } elseif (preg_match('/Windows NT 3.1/i', $user_agent)) {
            $os_name .= " NT 3.1";
        } elseif (preg_match('/Windows 3.10/i', $user_agent)) {
            $os_name .= " 3.1";
        } elseif (preg_match('/Windows 3.00/i', $user_agent)) {
            $os_name .= " 3.0";
        } elseif (preg_match('/Windows 2.11/i', $user_agent)) {
            $os_name .= " 2.11";
        } elseif (preg_match('/Windows 2.10/i', $user_agent)) {
            $os_name .= " 2.10";
        } elseif (preg_match('/Windows 2.03/i', $user_agent)) {
            $os_name .= " 2.03";
        } elseif (preg_match('/Windows 1.04/i', $user_agent)) {
            $os_name .= " 1.04";
        } elseif (preg_match('/Windows 1.03/i', $user_agent)) {
            $os_name .= " 1.03";
        } elseif (preg_match('/Windows 1.02/i', $user_agent)) {
            $os_name .= " 1.02";
        } elseif (preg_match('/Windows 1.01/i', $user_agent)) {
            $os_name .= " 1.0";
        }
    }

    // Next get the name of the browser
    if (preg_match('/rv/i', $user_agent) && !preg_match('/Firefox/i', $user_agent)) {
        $bro_name = 'Internet Explorer';
        $ub = "rv";
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $bro_name = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR|Opera/i', $user_agent)) {
        $bro_name = 'Opera/Opera GX';
        $ub = "OPR";
    } elseif (preg_match('/Edg/i', $user_agent)) {
        $bro_name = 'Microsoft Edge';
        $ub = "Edg";
    } elseif (preg_match('/YaBrowser/i', $user_agent)) {
        $bro_name = 'Yandex Browser';
        $ub = "YaBrowser";
    } elseif (preg_match('/Chrome/i', $user_agent)) {
        $bro_name = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $user_agent)) {
        $bro_name = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $user_agent)) {
        $bro_name = 'Netscape';
        $ub = "Netscape";
    }

    // Finally get the version of the browser
    $knownbnames = ['Version', $ub, 'other'];
    $bro_ver_pattern = '#(?<browser>' . join('|', $knownbnames) .
        ')[/ :]+(?<version>[0-9.|a-zA-Z.]*)#';
    preg_match_all($bro_ver_pattern, $user_agent, $matches);
    // See how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        // See if version is before or after the name
        if (strripos($user_agent, "Version") < strripos($user_agent, $ub)) {
            $bro_version = $matches['version'][0];
        } else {
            $bro_version = $matches['version'][1];
        }
    } else {
        $bro_version = $matches['version'][0];
    }

    // Check if we don't have version
    if ($bro_version == null || $bro_version == "") {
        $bro_version = "unknown";
    }

    return [
        'useragent' => $user_agent,
        'broname' => $bro_name,
        'broversion' => $bro_version,
        'broverpattern' => $bro_ver_pattern,
        'os_name' => $os_name,
    ];
}
// More beautiful print_r() function for array of booleans, strings, integers, floats,
// nulls or arrays (aka two-dimensional array) in html format
function print_array(array $array) {
    foreach ($array as $key => $value) {
        echo "(" . gettype($value) . ") " . $key . " => ";
        if (gettype($value) == 'array') {
            echo "[<br>";
            foreach ($value as $k => $v) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;(" . gettype($v) . ") " . $k . " => " . $v . "<br>";
            }
            echo "]<br>";
        } else {
            echo $value . "<br>";
        }
    }
}