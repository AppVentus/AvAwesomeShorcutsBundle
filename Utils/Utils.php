<?php

namespace AppVentus\Awesome\ShortcutsBundle\Utils;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
/**
 * Description of Utils
 *
 * @author leny
 */
class Utils {



	public static function truncate($string, $max_length = 30, $replacement = '', $trunc_at_space = false)
	{
		$max_length -= strlen($replacement);
		$string_length = strlen($string);
		
		if($string_length <= $max_length)
			return $string;
		
		if( $trunc_at_space && ($space_position = strrpos($string, ' ', $max_length-$string_length)) )
			$max_length = $space_position;
		
		return substr_replace($string, $replacement, $max_length);
	}


/**
 * Modifies a string to remove al non ASCII characters and spaces.
 */
	static public function slugify($text)
	{
	    // replace non letter or digits by -
	    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	 
	    // trim
	    $text = trim($text, '-');
	 
	    // transliterate
	    if (function_exists('iconv'))
	    {
	        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	    }
	 
	    // lowercase
	    $text = strtolower($text);
	 
	    // remove unwanted characters
	    $text = preg_replace('~[^-\w]+~', '', $text);
	 
	    if (empty($text))
	    {
	        return 'n-a';
	    }
	 
	    return $text;
	}

    public static function convertHtmlToText($html) {
	// $document contient un document HTML
	// Ce script va effacer les balises HTML, les javascript
	// et les espaces. Il remplace aussi quelques entités HTML
	// courante en leur équivalent texte.

	$search = array('@<script[^>]*?>.*?</script>@si', // Supprime le javascript
	    '@<[\/\!]*?[^<>]*?>@si', // Supprime les balises HTML
	    '@([\r\n])[\s]+@', // Supprime les espaces
	    '@&(quot|#34);@i', // Remplace les entités HTML
	    '@&(amp|#38);@i',
	    '@&(lt|#60);@i',
	    '@&(gt|#62);@i',
	    '@&(nbsp|#160);@i',
	    '@&(iexcl|#161);@i',
	    '@&(cent|#162);@i',
	    '@&(pound|#163);@i',
	    '@&(copy|#169);@i',
	    '@&#(\d+);@e');		    // Evaluation comme PHP

	$replace = array('',
	    '',
	    '\1',
	    '"',
	    '&',
	    '<',
	    '>',
	    ' ',
	    chr(161),
	    chr(162),
	    chr(163),
	    chr(169),
	    'chr(\1)');
	return preg_replace($search, $replace, $html);
    }

    public static function camelize($string, $pascalCase = false) {
	$string = str_replace(array('-', '_'), ' ', $string);
	$string = ucwords($string);
	$string = str_replace(' ', '', $string);

	if (!$pascalCase) {
	    return lcfirst($string);
	}
	return $string;
    }
	
	
	public static function generatePassword($length=10) {
        $conso = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");
        $vocal = array("a", "e", "i", "o", "u", "@", 1, 3, 4, "A", "E", "I", "O", "U");
        $password = "";
        srand((double) microtime() * 1000000);
        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++) {
            $password.=$conso[rand(0, count($conso) - 1)];
            $password.=$vocal[rand(0, count($vocal) - 1)];
        }
        $newpass = $password;
        return $newpass;
        // $animals = array('Cat',"Dog","Horse","Bird","Monkey","Chimp","Tiger","Leopard","Lion","Chicken","Koala","Elephant","Panda","Moose");
        // $colors = array("Red","Blue","Orange","Green","Yellow","Pink","Brown","Purple","Gray","White","Black");
        // return $colors[rand(0,count($colors)-1)].$animals[rand(0,count($animals)-1)].rand(1,9999);
    }
	public static function getDateByLanguage($date = null,Translator $translator){
		$date = $date == null ? date("Y-m-d H:i:s") : $date;
		return $translator->trans(strtolower(strftime("%A",time($date->format('Y-m-d H:i:s')))))." ".$date->format('d')." ".$translator->trans(strtolower(strftime("%B",time($date->format('Y-m-d H:i:s')))));
    }
}

?>
