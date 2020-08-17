<?php

//****************************************
// Create blank json entries for shows starting from a certain date
//****************************************

function CreateBlankEpisodeEntries() {
  $saturday = '2020-08-01T19:00:00-06:00';
  $sunday = '2020-08-02T19:00:00-06:00';
  
  $dates = array();
  $dates_string = '';
  $shows = array('shows' => array(
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    ),
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    ),
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    ),
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    ),
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    ),
    array(
      "title" => "",
      "broadcastDate" => "",
      "sourceFile" => ""
    )
  ));
  
  for ($i = 0; $i < 52; $i++) {
    $new_sat = date('c', strtotime($saturday . ' +' . $i . ' week'));
    $new_sun = date('c', strtotime($sunday . ' +' . $i . ' week'));
    $dates[$new_sat] = $shows;
    $dates[$new_sun] = $shows;
    $dates_string .= $new_sat . '<br>';
    $dates_string .= $new_sun . '<br>';
  }
  
  echo $dates_string;
  
  echo '<pre>';
  echo json_encode($dates, JSON_PRETTY_PRINT);
  echo '</pre>';
}

?>