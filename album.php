<?php include './header.php' ?>
<?php include './class/albumClass.php' ?>

<?php
$albumObj = new Album('./albums.json');
$albumSlug = isset($_GET['album']) ? $_GET['album'] : 'default';
$albumTitle = $albumObj->getAlbumTitle($albumSlug);
$songs = $albumObj->fillAlbum($albumSlug);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiotê: <?php echo $albumTitle; ?></title>
  <!-- Include Howler.js library -->
  <script src="https://unpkg.com/howler"></script>
</head>
<body>

<div id="corpo">
  <h2><?php echo $albumTitle; ?></h2>
  <div id="playlist">
    <?php
    foreach ($songs as $index => $song) {
      $title = $song["title"];
      $slug = $song["slug"];
      $artists = $song["artists"];

      echo "<p>" .
      "<a href=\"/$albumSlug/$slug\" class=\"song-link\">$title - $artists</a>" .
      "<span class=\"play-button\" data-song-index=\"$index\"><i class=\"fas fa-play\"></i></span>" .
      "<span class=\"lyrics-button\" data-song-index=\"$index\"><i class=\"fas fa-file-alt\"></i></span>" .
      "<span class=\"download-button\" data-song-index=\"$index\"><i class=\"fas fa-download\"></i></span>" .
      "</p>";
    }
    ?>
  </div>
</div>

<footer>
  <div id="footer-content">
    <div id="cover">
      <!-- Coloque aqui o código para a imagem -->
    </div>
    <div id="artista">
      <!-- Artista info -->
    </div>
    <div id="controles-musica">
      <i id="volume-icon" class="fas fa-volume-up"></i>
      <i id="play-pause-button" class="fas fa-play"></i>
      <!-- Coloque aqui o código para a barra de duração da música -->
    </div>
  </div>

  <!-- Social media icons here -->

  <script>
    var albumSlug = '<?php echo $albumSlug; ?>';

    var playlist = [
      <?php
      foreach ($songs as $song) {
        $title = $song['title'];
        $artist = $song['artists'];
        $slug = $song['slug'];
        echo "{ title: '$title', artist: '$artist', src: '/player/$albumSlug/$slug.mp3', slug: '$slug'},";
      }
      ?>
    ];

    var currentSong = 0;
    var sound = new Howl({
      src: [playlist[currentSong].src],
      html5: true,
      onplay: function () {
        updateArtistInfo(currentSong);
        updateImageInfo(currentSong);
      }
    });

    // Function to update artist information
    function updateArtistInfo(index) {
      var artistInfo = document.getElementById('artista');
      artistInfo.innerHTML = "<h5>" + playlist[index].title + "</h5><p>" + playlist[index].artist + "</p>";
    }

    // Function to update artist information
    function updateImageInfo(index) {
      var imageInfo = document.getElementById('cover');
      var imageCorpo = document.getElementById('corpo');
      imageInfo.innerHTML = "<img src='/player/" + albumSlug + "/covers/" + playlist[index].slug + ".jpg' height=75></img>";

      imageCorpo.style.backgroundImage = 'url("/player/' + albumSlug + '/covers/' + playlist[index].slug + '.jpg")';
    }

    // Play/pause button click event
    var playPauseButton = document.getElementById('play-pause-button');
    playPauseButton.addEventListener('click', function () {
      if (sound.playing()) {
        sound.pause();
      } else {
        sound.play();
      }
      playPauseButton.className = sound.playing() ? 'fas fa-pause' : 'fas fa-play';
    });

    // Song links click events
    var songLinks = document.querySelectorAll('.song-link');
    songLinks.forEach(function (link, index) {
      link.addEventListener('click', function (event) {
        event.preventDefault();
        currentSong = index;
        sound.stop();
        sound = new Howl({
          src: [playlist[currentSong].src],
          html5: true,
          onplay: function () {
            updateArtistInfo(currentSong);
            updateImageInfo(currentSong);
          }
        });
        sound.play();
        playPauseButton.className = 'fas fa-pause';
      });
    });

    // Lyrics button click events
var lyricsButtons = document.querySelectorAll('.lyrics-button');
lyricsButtons.forEach(function (button) {
  button.addEventListener('click', function () {
    var index = button.getAttribute('data-song-index');
    var song = playlist[index];
    // Implement logic to show lyrics modal for the selected song
    // You can use a library or create a custom modal for displaying lyrics
    console.log('Show lyrics for:', song.title);
  });
});

// Download button click events
var downloadButtons = document.querySelectorAll('.download-button');
downloadButtons.forEach(function (button) {
  button.addEventListener('click', function () {
    var index = button.getAttribute('data-song-index');
    var song = playlist[index];

    // Create a download link
    var downloadLink = document.createElement('a');
    downloadLink.href = song.src; // Set the download link to the MP3 file
    downloadLink.download = song.title + '.mp3'; // Set the filename for download

    // Append the link to the document
    document.body.appendChild(downloadLink);

    // Trigger a click on the link to start the download
    downloadLink.click();

    // Remove the link from the document
    document.body.removeChild(downloadLink);

    console.log('Download:', song.title);
  });
});


    // Play button click events for dynamically generated play buttons
    var playButtons = document.querySelectorAll('.play-button');
    playButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var index = button.getAttribute('data-song-index');
        currentSong = index;
        sound.stop();
        sound = new Howl({
          src: [playlist[currentSong].src],
          html5: true,
          onplay: function () {
            updateArtistInfo(currentSong);
            updateImageInfo(currentSong);
          }
        });
        sound.play();
        playPauseButton.className = 'fas fa-pause';
      });
    });
  </script>
</footer>

<?php include './footer.php' ?>
</body>
</html>
