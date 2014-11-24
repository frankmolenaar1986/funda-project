<?php
    // Variable that could be set in JS or other calling resource, hardcoded for
    // the assignment
    $numerOfAgents = 10;
    // Variable that could be set in JS or other calling resource, hardcoded for
    // the assignment
    $urlKey = "005e7c1d6f6c4f9bacac16760286e3cd"; 
    // Base of the url to be added onto later
    $urlBase = "http://partnerapi.funda.nl/feeds/Aanbod.svc/json/$urlKey/?type=koop&zo=/amsterdam/tuin/&page=1&pagesize=";
    // Get the total number of houses from the API   
    $numberOfHouses = json_decode(file_get_contents("$urlBase"))->TotaalAantalObjecten;
    
    // This does not seem to be working; I'm attempting to retrieve get all the 
    // houses in Amsterdam with a yard, based on the number previously 
    // retrieved, but I only seem to be able to retrieve 25 max. Technically i 
    // could loop using the page number, but that'd mean sending 1410 / 25 
    // (the $numberOfHouses at this time) = 56 requests to the api and since i'm
    // i'd be locked out of the service after more than 100 per minute i left it 
    // like this for now.
    $houses = json_decode(file_get_contents($urlBase.$numberOfHouses));
            
    // Set an empty array and fill them with all the agents with houses
    // containing the set options
    $agents = [];
    foreach ($houses->Objects as $arr) 
    {
        array_push($agents, $arr->MakelaarId);       
    }
    
    // Count the number of houses per agent
    $agentsWithOccurences = array_count_values($agents);
    // Sort the keys on amounts
    arsort($agentsWithOccurences);   
    // Return an array of the agent id's 
    $agentIds = array_keys($agentsWithOccurences);
    
    // Build tables sorted by the agents with the most houses in their 
    // portfolio. Normally I would always build a json string or different 
    // data object but for the sake of this assignment I figured this would
    // do to demonstrate what i'm trying to do.
    $htmlReturnString = "";
    
    // Loop the array of houses once for every agent in the top 10
    // The choice here was to loop the entire array 
    for ($i = 0; $i < $numerOfAgents; $i++)
    {
        $htmlReturnString .= "";
        foreach ($houses->Objects as $arr) 
        {
            if ($arr->MakelaarId == $agentIds[$i])
            {
                $htmlReturnString .= "<tr>";
                $htmlReturnString .= "<td>".$arr->Adres."</td>";
                $htmlReturnString .= "<td>".$arr->AangebodenSindsTekst."</td>";
                $htmlReturnString .= "<td><img src='".$arr->FotoMedium."'/></td>";                
                $htmlReturnString .= "<td>".$arr->MakelaarNaam."</td>";
                $htmlReturnString .= "</tr/>";
            }                   
        } 
    }
      
    echo $htmlReturnString;
?>