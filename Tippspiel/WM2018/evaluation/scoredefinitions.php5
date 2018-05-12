<?php
class scoredefinitions
{
	// gilt für Gruppen- und Finalspiele: für das exakt richtige Ergebnis gibt's 2 Punkt extra  
	// (Ausnahme Finale und Spiel um Platz 3, dort gibt's 3 Punkte)
	const MATCH_RESULT_EXACTLY = 2; 
	const MATCH_RESULT_EXACTLY_FINAL = 3; 
	
	const GROUPRANK_CORRECT = 2;
	const GROUPMATCH_WINNER_CORRECT = 2;

	// Finalteilnehmer
	const FINALMATCH_PARTICIPANT_EIGHTH = 2;   // Achtelfinale
	const FINALMATCH_PARTICIPANT_QUARTER = 3;  // Viertelfinale
	const FINALMATCH_PARTICIPANT_HALF = 4;     // Halbfinale
	const FINALMATCH_PARTICIPANT_THIRD = 5;    // Platz 3
	const FINALMATCH_PARTICIPANT = 5;          // Finale
	
	// Finalsiegertipp bei richtiger Paarung
	const FINALMATCH_WINNER_EIGHTH = 2;   // Achtelfinale
	const FINALMATCH_WINNER_QUARTER = 3;  // Viertelfinale
	const FINALMATCH_WINNER_HALF = 4;     // Halbfinale

	const CHAMPIONSHIP_RANK_1 = 15; // Welt-/Europameister
	const CHAMPIONSHIP_RANK_2 = 10; // Vize
	const CHAMPIONSHIP_RANK_3 = 8;  // Platz 3
	
	// 12 Punkte für Torschützenkönig müssen per Hand in die DB eingetragen werden, da hier (noch) keine automatische Auswertung möglich ist 
}
?>
