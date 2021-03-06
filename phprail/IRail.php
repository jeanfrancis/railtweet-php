<?php 

include_once("dom/IRailParser.php");

include_once("data/Connection.php");
include_once("data/Liveboard.php");
include_once("data/Station.php");
include_once("data/Vehicle.php");
include_once("data/VehicleInformation.php");


class IRail
{
    const DEFAULT_LANGUAGE    = "en";
    const WRAPPER_SUFFIX      = "IRailForPhp";
    const NO_MAX_RESULTS      = 0;
    
    private $language;
    private $maxResults;
    private $providerURL;

	/* --------------------------------------------------------------------------------------------- */
	
    function __construct(){
    
    	$a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    }
    
    function __construct1($providerURL) 
    { 
        $this->providerURL = $providerURL;
        $this->language = $DEFAULT_LANGUAGE;
        $this->maxResults = $NO_MAX_RESULTS;
    } 
    
    function __construct2($providerURL,$language) 
    { 
        $this->providerURL = $providerURL;
        $this->language = $language;
        $this->maxResults = self::NO_MAX_RESULTS;
    } 
    
    function __construct3($providerURL, $language, $maxResults) 
    { 
        $this->providerURL = $providerURL;
        $this->language = $language;
        $this->maxResults = $maxResults;
    } 
    
    function __construct4($providerURL, $language, $maxResults, $agentName) 
    { 
        $this->providerURL = $providerURL;
        $this->language = $language;
        $this->maxResults = $maxResults;
        setAgent($agentName);
    } 

    /* --------------------------------------------------------------------------------------------- */
    
    //return VehicleInformation 
    public function getVehicleInformation(Vehicle $vehicle)
    {
		  $url = $this->providerURL."/vehicle/?&id=".$vehicle->getId()."&lang=".$this->language;
		  return IRailParser::parseVehicle($url);
    }
	
	
	 //return Liveboard
    public function getLiveboard(Station $station)
    {
    	  $url = $this->providerURL."/liveboard/?&station=".$station->getName()."&lang=".$this->language;
        return IRailParser::parseLiveboard($url);
    }
    
	 
	 //return ArrayList<Connection> 
    public function getConnections($from, $to,  DateTime $dateTime = NULL, $wantArrivalTime=false)
    {
        $url = $this->providerURL."/connections/?from=".$from."&to=".$to;
        
        if($dateTime = NULL){$dateTime = new DateTime();} 

		
                    if($this->maxResults > 0)
                    {
                    		$url .= "&results=".$maxResults;
                    }

                    if($dateTime != NULL)
                    {
                    		//TODO format 
                        $dateFormat = $dateTime->format("ddMMyy");
                        $timeFormat = $dateTime->format("HHmm");
                        $url .= "&date=".$date."&time=".$time;
                    }
                    
                    if($wantArrivalTime)
                    {
                    		$url .= "&timeSel=arr";
                    }
						
						  $url .= "&lang=".$this->language;


        return IRailParser::parseConnections($url);
    }

 	 //return ArrayList<Station>  
    public function getStations()
    {
        $url = $this->providerURL."/stations/?&lang=".$this->language;
        return IRailParser::parseStations($url);
    }

    /* -------------------------------------------------------------------------------------- */

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
        return $this;
    }

    /**
     *
     * @param agentName is the parameter that is used to identify yourself in the IRail api. Set it to null if you want to remain anonymous. Set it to your application name if you want us to see it's you
     * @return
     */
    public function setAgent($agentName)
    {
    	  ini_set('user_agent', $agentName." ".self::WRAPPER_SUFFIX);
        return $this;
    }
}
?>