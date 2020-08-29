<?php

class ShowHandler {
  private $episodes;
  private $audioFileDir;

  public function __construct() {
    $this->episodes = $this->GetShowData();
    $this->audioFileDir = $_SERVER['DOCUMENT_ROOT'] . '/library/audio-files/';
  }

  private function GetShowData() {
    $episodes = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/library/data/show-schedule.json');
    $episodes = json_decode($episodes, true);
    ksort($episodes);

    return $episodes;
  }

  public function DownloadShowFiles() {
    foreach ($this->episodes as $episodeDate => $episodeData) {
      $showDateAudioFileDir = $this->audioFileDir . $episodeDate;
      $showsContainAnEpisode = $episodeData['shows'][0]['sourceFile'];

      if (!file_exists($showDateAudioFileDir) && $showsContainAnEpisode) {
        mkdir($showDateAudioFileDir);
        chmod($showDateAudioFileDir, 0755);

        foreach ($episodeData['shows'] as $show) {
          $destFilePath = $showDateAudioFileDir . '/' . basename($show['sourceFile']);
          $mp3File = file_get_contents($show['sourceFile']);
          sleep(1); // Throttle how fast shows are downloaded for archive.org's benefit

          if ($mp3File) {
            file_put_contents($destFilePath, $mp3File);
          } else {
            echo 'Unable to download ' . $show['sourceFile'] . '<br>';
          }
        }
      }
    }
  }

  public function GetEpisodesToDisplay() {
    $episodesToDisplay = array();
    $allEpisodes = $this->episodes;
    krsort($allEpisodes);
    $maxNumberOfShowsToDisplay = 4;
    $count = 0;
    $currentTime = isset($_GET['test-time']) ? strtotime($_GET['test-time']) : time();

    foreach ($allEpisodes as $date => $details) {
      if (strtotime($date) <= $currentTime) {
        $episodesToDisplay[$date] = $details;
        $count++;

        if ($count === $maxNumberOfShowsToDisplay) {
          break;
        }
      }
    }

    $episodesToDisplay = $this->AddLocalFilePaths($episodesToDisplay);

    return $episodesToDisplay;
  }

  private function AddLocalFilePaths($episodes) {
    foreach ($episodes as $date => &$episode) {
      foreach ($episode['shows'] as &$show) {
        $show['relativeFilePath'] = '/library/audio-files/' . urlencode($date) . '/' . urlencode(basename($show['sourceFile']));
      }
    }
    
    return $episodes;
  }
}

?>