<?php 

/**
 * Checks to see if a business is a duplicate by using the business name, zip code, and any telephone numbers provided
 *
*/


class BL_Duplicate 
{
	
	private $arrDuplicateBusinesses=array();
	private $blnIsDuplicate=false;
	private $objLink=false;

	function __construct($objLink=false, $intZipIn=0, $intTel1=0, $intTel2=0, $intTel3=0, $intNodeId=0, $strBusinessName="") {
		
		$this->objLink=$objLink;
		
		$this->setDuplicateBusinesses($intZipIn, $intTel1, $intTel2, $intTel3);
		
		$this->setIsDuplicate($intNodeId, $strBusinessName);
		
	}
	
	function setDuplicateBusinesses($intZipIn=0, $intTel1=0, $intTel2=0, $intTel3=0) 
	{
		
		$DL_Duplicate = new DL_Duplicate($this->objLink);
		$DL_Duplicate->setDuplicateBusiness($intZipIn, $intTel1, $intTel2, $intTel3);
	
		$this->arrDuplicateBusinesses = $DL_Duplicate->getDuplicateBusiness();
	
	}
	
	function getDuplicateBusinesses() 
	{
	
		return $this->arrDuplicateBusinesses;
	
	}
	
	function setIsDuplicate($intNodeId=0, $strBusinessName="") 
	{

		$blnIsDuplicate = false;
		
		if (is_array($this->arrDuplicateBusinesses)) {
			
			foreach ($this->arrDuplicateBusinesses as $basic) {
						
				$arrBusinessBasic = unserialize($basic['basic']);
				
				if ($basic['business_id'] != $intNodeId) {
						
					if (($this->isSameBusinessName($strBusinessName, $arrBusinessBasic['nam']['trd']))) {
	
						$blnIsDuplicate = true;
						break;
					}
					
				}
						
			}
			
		}
		
		$this->blnIsDuplicate = $blnIsDuplicate;
	
	}
	
	function getIsDuplicate() 
	{
	
		return $this->blnIsDuplicate;
	
	}
	
	function isSameBusinessName($strBusinessNameIn="", $strBusinessNameDupe="")
	{
		
		
		if ($this->purifyBusinessName($strBusinessNameIn) == $this->purifyBusinessName($strBusinessNameDupe)) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
		
	}
	
	function purifyBusinessName($strBusinessName="")
	{
		
		$arrPatterns=array();
		
		// list of noise words to ignore
		$arrPatterns[] = "/\binc\b/";
		$arrPatterns[] = "/\bcorp\b/";
		$arrPatterns[] = "/\bllc\b/";
		$arrPatterns[] = "/\bpty\b/";
		$arrPatterns[] = "/\bplc\b/";
		$arrPatterns[] = "/\bllp\b/";
		$arrPatterns[] = "/\bltd\b/";
		$arrPatterns[] = "/\blimited\b/";
		$arrPatterns[] = "/\bincorp\b/";
		$arrPatterns[] = "/\bincorporated\b/";
		$arrPatterns[] = "/\bthe\b/";
		$arrPatterns[] = "/\bfor\b/";
		$arrPatterns[] = "/\bof\b/";
		$arrPatterns[] = "/\band\b/";
		$arrPatterns[] = "/\bin\b/";
		$arrPatterns[] = "/\bmr\b/";
		$arrPatterns[] = "/\bat\b/";
		$arrPatterns[] = "/\bnet\b/";
		$arrPatterns[] = "/\bcom\b/";
		$arrPatterns[] = "/\borg\b/";
		$arrPatterns[] = "/\bco uk\b/";
		$arrPatterns[] = "/\bby\b/";
		$arrPatterns[] = "/\bon\b/";
		$arrPatterns[] = "/\bto\b/";
		$arrPatterns[] = "/\bdr\b/";
		$arrPatterns[] = "/\bmr\b/";
		$arrPatterns[] = "/\bmrs\b/";
		$arrPatterns[] = "/\bsom\b/";
		$arrPatterns[] = "/\bco\b/";
		$arrPatterns[] = "/\bphotographer\b/";
		$arrPatterns[] = "/\bphotography\b/";
		$arrPatterns[] = "/\baaa\b/";
		$arrPatterns[] = "/\ba1\b/";
		$arrPatterns[] = "/\bcpa pc\b/";
		$arrPatterns[] = "/\bmd\b/";
		$arrPatterns[] = "/\bdds\b/";
		$arrPatterns[] = "/\bmhsc\b/";
		$arrPatterns[] = "/\bsvc\b/";
		$arrPatterns[] = "/\bphd\b/";
		$arrPatterns[] = "/\bpsyd\b/";
		$arrPatterns[] = "/\bod\b/";
		$arrPatterns[] = "/\bca\b/";
		$arrPatterns[] = "/\bcfe\b/";
		$arrPatterns[] = "/\bcfp\b/";
		$arrPatterns[] = "/\bcpc\b/";
		$arrPatterns[] = "/\bmfcc\b/";
		$arrPatterns[] = "/\bmhs\b/";
		
		$strBusinessName = trim(strtolower($strBusinessName));
		$strBusinessName = ereg_replace("[^A-Za-z0-9 ]", " ", $strBusinessName); // strip all non-alphanumeric, excluding spaces, and replace them with spaces
		$strBusinessName = preg_replace('/\s\s+/', ' ', $strBusinessName); // strip excess whitespace, so we don't have double spaces anywhere
		
		$strBusinessName = preg_replace($arrPatterns, " ", $strBusinessName);
		
		// remove double spaces and trim again
		$strBusinessName = preg_replace('/\s\s+/', ' ', $strBusinessName); // strip excess whitespace, so we don't have double spaces anywhere
		$strBusinessName = trim($strBusinessName);
		
		return $strBusinessName;
		
	}
	
	function setUpdateBusinessDedupe($intNodeIdIn=0, $intZipIn=0, $intTel1=0, $intTel2=0, $intTel3=0) 
	{
	
		$DL_Duplicate = new DL_Duplicate($this->objLink);
		$DL_Duplicate->setUpdateBusinessDedupe($intNodeIdIn, $intZipIn, $intTel1, $intTel2, $intTel3);
	
	}
	
	
	
}

?>