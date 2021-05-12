<?php

/**
 * Includes all common functions which can use site wide
 *
*/

class BL_Common {
	
	private $arrTollPrefixes=array(800 => 1, 888 => 1, 877 => 1, 866 => 1, 855 => 1, 844 => 1, 833 => 1, 822 => 1, 880 => 1, 881 => 1, 882 => 1, 883 => 1, 884 => 1, 885 => 1, 886 => 1, 887 => 1, 889 => 1);
	
	function __construct() {
		
	}
	
	function formatCityName($strCityName=false)
	{
		$strCityName = strtolower($strCityName);
		$strCityName = trim($strCityName);
		
		return ucwords($strCityName);
	}
	
	function formatStateName($strStateName=false)
	{
		$strStateName = strtolower($strStateName);
		$strStateName = trim($strStateName);
		$strStateName = ucwords($strStateName);
		$strStateName = str_replace(" Of ", " of ", $strStateName);
		
		return $strStateName;
	}
	
	function formatCategoryName($strCategoryName=false)
	{
            $strCategoryName = strtolower($strCategoryName);
            $strCategoryName = trim($strCategoryName);
            $strCategoryName = ucwords($strCategoryName);
            
            // This was added by Sruthi(Apr-29-2016) to capitalize the acronyms in catefory namne
            
            $arrCapitalize = array('Led','Tv','It','Pvc','Upvc','Av','Atv','Mba','Gmat','Rn','Ltl','Rv','Cnc','Ira','Lcd','Hvac','Vhs','Dvd','Cd', 'Cctv');
            
            $words = explode(' ', $strCategoryName);
            
            foreach($words as $key => $word){
                            
               if(in_array($word, $arrCapitalize)){
                   
                   $words[$key] = strtoupper($words[$key]);
               }            
            }  
                  
            $strCategoryName = implode(' ', $words);
            
            // upto this
            
            return $strCategoryName;
	}
	
	function formatForUrl($strWord=false)
	{
		$strWord = strtolower($strWord);
		$strWord = trim($strWord);
		$strWord = str_replace(" ", "-", $strWord);
		
		return $strWord;
	}
	
	function formatFromUrl($strWord=false)
	{
		$strWord = strtolower($strWord);
		$strWord = trim($strWord);
		$strWord = str_replace("-", " ", $strWord);
		
		return $strWord;
	}
	
	function formatBusinessName($strName=false) {
		
		$strReturnName = "";
		
	    $arrWords = explode(" ", $strName);
	    
	    $intTokenCount = 0;
	    
	    foreach ($arrWords as &$word) {
	        switch (strtolower($word)) {
	            case "uk":
	            case "(uk)":
	                $strReturnName .= " ";
	                $strReturnName .= strtoupper($word);
	                break;
	                
	            case "ltd":
	            case "ltd.":
	            case "(ltd)":
	            	$strReturnName .= " ";
	                $strReturnName .= ucwords($word);
	                break;
	                
	            case "and":
	            case "in":
	            case "of":
	            case "the":
	            case "on":
	            case "at":
	            case "for":
	            case "with":
	            case "upon":
	            case "under":
	                
	                $strReturnName .= " ";
	                
	                if ($intTokenCount > 0) {
	                	
	                    $strReturnName .= strtolower($word);
	                    
	                } else {
	                	
	                    $strReturnName .= $word;
	                    
	                }
	                
	                break;
	                
	            default:
	                $strReturnName .= " ";
	                $strReturnName .= $word;
	                break;
	        }
	        $intTokenCount++;
	    }
	            
	    return trim($strReturnName); 
	}
	
	function formatBusinessNameForUrl($strName=false) {
	
		$strReturnName = "";
		$strReturnName = trim($strName);
		$strReturnName = preg_replace("/[^a-z \d]/i", "", $strReturnName);
		$strReturnName = preg_replace("/\s+/", "-", $strReturnName);
		$strReturnName = strtolower($strReturnName);
		$strReturnName = str_replace("-ltd", "", $strReturnName);
	
		return trim($strReturnName);
	
	}
	
	function formatPhoneNumber($intPhone=false)
	{
		
		if ($intPhone) {
		
			// all numbers are 10 characters in length
			$intPhone = trim($intPhone);
			$intNumOne = substr($intPhone, 0, 3);
			$intNumTwo = substr($intPhone, 3, 3);
			$intNumThree = substr($intPhone, 6, 4);
			
			if (key_exists($intNumOne, $this->getTollPrefixes())) {
				
				return  "1-$intNumOne-$intNumTwo-$intNumThree";
				
			} else {
			
				return $intNumOne."-".$intNumTwo."-".$intNumThree;
				
			}
			
		} else { return ""; }
		
	}
	
	function formatAddress($strAddress)
	{

		// remove all non-alpha characters
		$strStripped = $this->stripNonAlpha($strAddress);
		$strNew = $strAddress;
		
		// first, determine if we have no capital letters, or no lower case letters
		if (ctype_lower($strStripped) or ctype_upper($strStripped)) {
			
			// make string all lower case, to remove all caps
			$strNew = ucwords(strtolower($strAddress));
			
			
			
		}
		
		// break up into individual words
		$arrWords = explode(" ", $strNew);
		
		$intCount = 0;
		foreach ($arrWords as $word) {
			
			if ($word == "Po") { $arrWords[$intCount] = "PO"; }
			else if ($word == "Ne") { $arrWords[$intCount] = "NE"; }
			else if ($word == "Nw") { $arrWords[$intCount] = "NW"; }
			else if ($word == "Se") { $arrWords[$intCount] = "SE"; }
			else if ($word == "Sw") { $arrWords[$intCount] = "SW"; }
			else if ($word == "Pobox") { $arrWords[$intCount] = "PO Box"; }
			
			$intCount++;
			
		}
		
		$strNew = implode(" ", $arrWords);
		
		return $strNew;
		
	}
	
	function formatAbout($strAbout, $strCategory, $strBusinessName) {

		$strNewAbout = "";
		
		if ($strAbout) {
		
			// remove the business name so we don't accidentally replace spelling in there
			$strNewAbout = str_replace($strBusinessName, "[businessname]", $strAbout);
			
			// first, replace all occurances of the category name as lower case.
			$strNewAbout = str_replace($strCategory, strtolower($strCategory), $strNewAbout);
			
			$arrAbout = explode(".", $strNewAbout); // break it up into sentences
			
			$strNewAbout="";
			
			$intTotal = count($arrAbout);
			$intCount=0;
			
			foreach ($arrAbout as $about) {
				
					$intCount++;
				
					
					$strNewAbout .= ucfirst($about);
					if ($intCount < $intTotal) { $strNewAbout .= "."; }
					
			}
			
		}
		
		// put the business name back in
		$strNewAbout = str_replace("[businessname]", $strBusinessName, $strNewAbout);
		
		return $strNewAbout;
		
	}
	
	function vsort($array, $id="id", $sort_ascending=true, $is_object_array = false) {
		
		$temp_array = array();
		
		while(count($array) > 0) {
			
			$lowest_id = 0;
			$index=0;
			
			if($is_object_array){
				
				if (is_array($array)) {
					
					foreach ($array as $item) {
						
						if (isset($item->$id)) {
							
							if ($array[$lowest_id]->$id) {
								
								if ($item->$id<$array[$lowest_id]->$id) {
									
									$lowest_id = $index;
								}
								
							}
							
						}
						
						$index++;
						
					}
					
				}
				
			} else {
				
				if (is_array($array)) {
					
					foreach ($array as $item) {
						
						if (isset($item[$id])) {
							
							if ($array[$lowest_id][$id]) {
								
								if ($item[$id]<$array[$lowest_id][$id]) {
									
									$lowest_id = $index;
									
								}
								
							}
							
						}
						
						$index++;
						
					}
					
				}
				
			}
			
			$temp_array[] = $array[$lowest_id];
			$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
		}
		
		if ($sort_ascending) {
			
			return $temp_array;
			
		} else {
			
			return array_reverse($temp_array);
			
		}
		
	}
	
	function purifyFormInput(
		$strInput="", 
		$blnNumeric=false, 
		$blnAlpha=false, 
		$blnAlphaNumeric=false, 
		$blnAlphaNumericSpaces=false, 
		$blnUseParagraphs=false,
		$blnNoSpaces=false)
	{
		
		$strInput = stripslashes($strInput);
		$strInput = strip_tags($strInput); // remove all html tags
		$strInput = $this->stripTrailingWhitespace($strInput); // remove trailing and leading spaces
		
		if (!$blnUseParagraphs) { 
			
			$strInput = $this->stripExcessWhitespace($strInput); 
		
		}  // remove all excess whitespace
		
		if ($blnNumeric) { $strInput = $this->stripNonNumeric($strInput); } // strip all non-numbers
		elseif ($blnAlpha) { $strInput = $this->stripNonAlpha($strInput); } // strip all non-letters
		elseif ($blnAlphaNumeric) { $strInput = $this->stripNonAlphNumeric($strInput); } // strip all non alphanumeric
		elseif ($blnAlphaNumericSpaces) { 
		
			$strInput = $this->stripTrailingWhitespace($strInput);
			$strInput = $this->stripNonAlphNumericSpaces($strInput); // strip all non-alphanumeric, excluding spaces
			$strInput = $this->stripExcessWhitespace($strInput); // strip excess whitespace, so we don't have double spaces anywhere
				
		} elseif ($blnNoSpaces) {
			$strInput = $this->stripSpaces($strInput);
		}
		
		return $strInput;
		
	}
	
	function stripSpaces($strInput="") {
		return preg_replace(' ', '', $strInput);
	}
	
	function stripNonAlphNumericSpaces($strInput="") {
		return preg_replace("/[^A-Za-z0-9 ]/", "", $strInput);
	}
	
	function stripNonAlphNumeric($strInput="") {
		return preg_replace("/[^A-Za-z0-9]/", "", $strInput);
	}
	
	function stripNonNumeric($strInput="") {
		return preg_replace("/[^0-9]/", "", $strInput);
	}
	
	function stripNonAlpha($strInput="") {
		return preg_replace("/[^A-Za-z]/", "", $strInput);
	}
	
	function stripExcessWhitespace($strInput="")
	{
		return preg_replace('/[  +]/', ' ', $strInput);
	}
	
	function stripTrailingWhitespace($strInput="")
	{
		return preg_replace('/[^ +| +$]/', '', $strInput);
	}
	
	function htmlDisplay($strTextToDisplay)
	{
		return htmlentities($strTextToDisplay, ENT_QUOTES, 'UTF-8');
	}
	
	function replaceStrong($strText) {
		$strText = str_replace("&lt;strong&gt;", "<strong>", $strText);
		return str_replace("&lt;/strong&gt;", "</strong>", $strText);
	}
	
	function purifyToll($intTelephone)
	{
		
		if (strlen($intTelephone) == 11 and substr($intTelephone, 0, 1) == "1") {
				
			$intTelephone = substr($intTelephone, 1, 10);
			
		}
		
		return $intTelephone;
		
	}
	
	function purifyZip($intZip=0) {
		
		if (strlen($intZip) == 4) { $intZip = "0".$intZip; }
		return $intZip;
		
	}
	
	// placeholder function, doesn't apply in the US
	function formatPostalDistrict($strZip="") {

		return "";
		
	}
	
	function setTollPrefixes($arrTollPrefixes) {
	
		$this->arrTollPrefixes = $arrTollPrefixes;
	
	}
	
	function getTollPrefixes() {
	
		return $this->arrTollPrefixes;
		
	}
	
}

?>
