<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/library/php/show-handler.php');

$showHandler = new ShowHandler();
$showHandler->DownloadShowFiles();
$episodes = $showHandler->GetEpisodesToDisplay();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-site-verification" content="LtNSTfeChMHsPaHl1JS5HeWFu14Cz7Njz_oCuM_2rmE" />
  <meta name="description" content="Enjoy new old time radio drama shows each week, inspired by the long running WPR show">
  <title>Old Time Radio Drama</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Oswald:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/library/styles/site.css">
</head>
<body>
<header>
</header>
<main>
  <h1>Old Time Radio Drama</h1>
  <div class="introduction">
    <div>
      <h2>The End of An Era</h2>
      <p>In June of 2020, WPR (Wisconsin Public Radio) decided to end production of the long-running show "Old Time Radio Drama". In a statment, WPR directory Mike Crane said:
      </p>
      <blockquote>
        Many of these plays and productions were produced more than 60 years ago and include racist and sexist material. Despite significant effort over the years, it has been nearly impossible to find historic programs without offensive and outdated content. And, ultimately, these programs don’t represent the values of WPR and The Ideas Network’s focus on public service through news and information.”
      </blockquote>
      <p>Read more in <a href="https://www.wpr.org/wpr-ends-production-old-time-radio-drama">WPR's statement.</a></p>
    </div>
    <div>
      <h2>The Tradition Continues</h2>
      <p>As an avid listener of the show and long-time WPR supporter I was saddened to learn that the show would no longer be broadcast. The news literally brought tears to my eyes. And while I understand the reasons behind ending the show, I also know that old time radio has brought tremendous value to my life. Each week I got small glimpse into a different era, a time period when my grandparents were young.</p>
      <p>Because I enjoy old time radio and I know many others do too, I've decided to help keep this tradition alive. Each Saturday and Sunday night I'll post a new episode of Old Time Radio Drama, featuring classic shows such as Gunsmoke, Dragnet, The Shadow, Suspense, and many more. The format will closely follow that of the long-running WPR show. If you liked that show, I think you'll like this too.</p>
      <p><strong>New episodes are released every Saturday and Sundy evening at 7pm.</strong></p>
    </div>
  </div>
  <div class="episodes">
<?php
  foreach ($episodes as $date => $details) {
?>
    <article class="episode">
      <h2><?php echo date('l, F j', strtotime($date))?></h2>
      <table class="info">
        <tr>
          <th>Show</th>
          <th>Original Broadcast Date</th>
        </tr>
<?php
    foreach ($details['shows'] as $index => $show) {
?>
        <tr data-audio-file-path='<?php echo $show['relativeFilePath']?>' data-show-index="<?php echo $index; ?>">
          <td><?php echo $show['title']?></td>
          <td><?php echo $show['broadcastDate'] ? date('l, F j, Y', strtotime($show['broadcastDate'])) : 'Unknown'; ?></td>
        </tr>
<?php
    }
?>
      </table>
      <audio controls>
        <source src="/library/audio-files/old-time-radio-drama-2020-07-25.mp3" type="audio/mpeg">
      </audio> 
    </article>
<?php
  }
?>
  </div>
</main>
<footer>
</footer>
<script>

var EpisodePlayer = (function() {
  // Private variables
  var _episode;
  var _showRows;
  var _audioElement;

  // Visible interface
  var visible = {};

  //****************************************
  // Initialize
  //****************************************

  visible.Initialize = function(options) {
    _episode = options.episode;
    _showRows = _episode.querySelectorAll('tr:not(:first-child)');
    _audioElement = _episode.querySelector('audio');

    SetFirstAudioSource();
    ListenForShowChange();
    ChangeToNextShowWhenOneEnds();
  }

  var SetFirstAudioSource = function() {
    _audioElement.src = _showRows[0].dataset.audioFilePath;
    _audioElement.dataset.playingShowIndex = 0;
    _showRows[0].classList.add('currently-playing');
  }

  var ListenForShowChange = function() {
    _showRows.forEach(function(showRow) {
      showRow.addEventListener('click', function() {
        _audioElement.src = this.dataset.audioFilePath;
        _audioElement.dataset.playingShowIndex = this.dataset.showIndex;
        _audioElement.play();

        ClearCurrentlyPlayingClassFromRows();
        this.classList.add('currently-playing');
        console.log(this.classList);
      });
    });
  }

  var ClearCurrentlyPlayingClassFromRows = function() {
    _showRows.forEach(function(row) {
      row.classList.remove('currently-playing');
    });
  }

  var ChangeToNextShowWhenOneEnds = function() {
    _audioElement.addEventListener('ended', function() {
      var currentShowIndex = parseInt(this.dataset.playingShowIndex);
      if (currentShowIndex < _showRows.length) {
        var newShowIndex = currentShowIndex + 1;
        this.dataset.playingShowIndex = newShowIndex;
        this.src = _showRows[newShowIndex].dataset.audioFilePath;
        _audioElement.play();

        ClearCurrentlyPlayingClassFromRows();
        _showRows[newShowIndex].classList.add('currently-playing');
      }
    });
  }

  // Return public interface
  return visible;

})

var episodes = document.querySelectorAll('.episode');

episodes.forEach(function(episode) {
  var episodePlayer = new EpisodePlayer;
  episodePlayer.Initialize({episode : episode});
});
</script>
</body>
</html>