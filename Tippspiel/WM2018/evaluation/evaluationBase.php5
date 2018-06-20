<?php
include_once("scoredefinitions.php5");
class evaluationBase {
    
    function getParticipantScore($matchtype)
    {
        if($matchtype=='Achtelfinale')
        {
            return scoredefinitions::FINALMATCH_PARTICIPANT_EIGHTH;
        }
        else if($matchtype=='Viertelfinale')
        {
            return scoredefinitions::FINALMATCH_PARTICIPANT_QUARTER;
        }
        else if($matchtype=='Halbfinale')
        {
            return scoredefinitions::FINALMATCH_PARTICIPANT_HALF;
        }
        else if($matchtype=='Platz3')
        {
            return scoredefinitions::FINALMATCH_PARTICIPANT;
        }
        else if($matchtype=='Finale')
        {
            return scoredefinitions::FINALMATCH_PARTICIPANT;
        }
    }
}

?>
