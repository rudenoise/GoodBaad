<?php
/*
 *    ___          ___                        ___     
 *   /  /\        /__/\          ___         /  /\    
 *  /  /:/       |  |::\        /__/\       /  /:/    
 * /__/::\       |  |:|:\       \  \:\     /  /:/     
 * \__\/\:\    __|__|:|\:\       \  \:\   /  /:/  ___ 
 *    \  \:\  /__/::::| \:\  ___  \__\:\ /__/:/  /  /\
 *     \__\:\ \  \:\~~\__\/ /__/\ |  |:| \  \:\ /  /:/
 *     /  /:/  \  \:\       \  \:\|  |:|  \  \:\  /:/ 
 *    /__/:/    \  \:\       \  \:\__|:|   \  \:\/:/  
 *    \__\/      \  \:\       \__\::::/     \  \::/   
 *                \__\/           ~~~~       \__\/  
 *
 * V 1.0 � JOEL HUGHES 2008
 *
 * SECURITY : CHECKING CLEANING CONTENT
 *
 */
Class Security
{
    function paranoid($string, $allowed = array()) {
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }

        if (is_array($string)) {
            $cleaned = array();
            foreach ($string as $key => $clean) {
                $cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $clean);
            }
        } else {
            $cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $string);
        }
        return $cleaned;
    }
    
    function matchParanoid($string, $allowed = array()) {
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }

        if (is_array($string)) {
            $cleaned = array();
            foreach ($string as $key => $clean) {
                $cleaned[$key] = preg_match("/[^{$allow}a-zA-Z0-9]/", $clean);
            }
        } else {
            $cleaned = preg_match("/[^{$allow}a-zA-Z0-9]/", $string);
        }
        return $cleaned;
    }

    function html($string, $remove = false) {
        if ($remove) {
            $string = strip_tags($string);
        } else {
            $patterns = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
            $replacements = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
            $string = preg_replace($patterns, $replacements, $string);
        }
        return $string;
    }

    function cleanWhitespace($str) {
        $r = preg_replace('/[\n\r\t]+/', '', $str);
        return preg_replace('/\s{2,}/', ' ', $r);
    }

    function cleanImages($str) {
        $str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
        $str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
        $str = preg_replace('/<img[^>]*>/i', '', $str);
        return $str;
    }

    function cleanScripts($str) {
        return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/i', '', $str);
    }

    function cleanAll($str) {
        $str = Security::cleanWhitespace($str);
        $str = Security::cleanImages($str);
        $str = Security::cleanScripts($str);
        return $str;
    }

    function cleanTags() {
        $params = params(func_get_args());
        $str = $params[0];

        for ($i = 1; $i < count($params); $i++) {
            $str = preg_replace('/<' . $params[$i] . '[^>]*>/i', '', $str);
            $str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
        }
        return $str;
    }
    
    function matchTags($str)
    {
        if (preg_match('/<', $str)||preg_match('/<\/', $str)||preg_match('[^>]*>/i', $str))
        {
            return true;
        }
    }
    
    function matchWhiteSpace($str)
    {
        if (preg_match('/[\n\r\t]+/', $str)||preg_match('/\s{2,}/', $str))
        {
            return true;
        }
    }
    
    
    function __destruct()
    {
        #echo "Security Destroyed<br>";
    }
}
?>