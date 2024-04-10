<?php include './header.php'?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>

<!DOCTYPE html>
<head>
  <title>Eu nem acredito, que é o Tiotê!</title>
</head>

<style>
  #playlist li:hover {
    cursor: pointer;
    background-color: #f0f0f0;
  }

  #playlist li.playing {
    font-weight: bold;
  }
</style>

<div id="corpo">
<div id="audio-player">
  <h2>Playlist</h2>
  <ul id="playlist">
    <li data-src="player/buraco-de-minhoca/01 - Tentaram me Parar.mp3">Tentaram me Parar</li>
    <li data-src="player/buraco-de-minhoca/02 - Tempo Correndo.mp3">Tempo Correndo</li>
    <li data-src="player/buraco-de-minhoca/03 - Sentado numa Praça.mp3">Sentado numa Praça</li>
    <!-- Add more songs as necessary -->
  </ul>
  <audio id="player" controls></audio>
</div>

  </div>


  <footer>
    <div id="footer-content">
      <div id="imagem-lado-esquerdo">
        <!-- Coloque aqui o código para a imagem -->
      </div>
      <div id="artista-info">
        <h5>01 - Pombo Rasante</h5>
        <p>Luan Bião</p>
      </div>
      <div id="controles-musica">
       
        <i id="volume-icon" class="fas fa-volume-up"></i>
        <i id="play-pause-button" class="fas fa-play"></i>
        <!-- Coloque aqui o código para a barra de duração da música -->
      </div>
    </div>
  </footer>

  
</body>
</html>

<?php include './footer.php'?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var playlist = document.getElementById("playlist");
    var player = null;
    var isPlaying = false; // Track the playing state

    // Add click event listeners to playlist items
    playlist.addEventListener("click", function (e) {
      if (e.target.tagName === "LI") {
        var source = e.target.getAttribute("data-src");
        var songName = e.target.innerText; // Get the song name from the clicked element
        playAudio(source, songName);
        highlightCurrentTrack(e.target);
        updatePlayPauseButton();
      }
    });

    // Add click event listener to the play-pause button
    var playPauseButton = document.getElementById("play-pause-button");
    playPauseButton.addEventListener("click", function () {
      if (isPlaying) {
        pauseAudio();
      } else {
        resumeAudio();
      }
      updatePlayPauseButton();
    });

    // Function to play audio
    function playAudio(source, songName) {
      if (player) {
        player.stop();
      }
      player = new Howl({
        src: [source],
        html5: true,
        format: ['mp3'],
        onend: function () {
          // Automatically play the next track when the current one ends
          playNext();
        },
      });
      player.play();

      // Update #artista-info with the name of the currently playing song
      updateArtistaInfo(songName);
      isPlaying = true;
    }

    // Function to pause the currently playing track
    function pauseAudio() {
      if (player) {
        player.pause();
      }
      isPlaying = false;
    }

    // Function to resume the paused track
    function resumeAudio() {
      if (player) {
        player.play();
      }
      isPlaying = true;
    }

    // Function to play the next track in the playlist
    function playNext() {
      var nextTrack = playlist.querySelector(".playing").nextElementSibling;
      if (!nextTrack) {
        // If the current track is the last one, play the first track
        nextTrack = playlist.firstElementChild;
      }
      var nextSource = nextTrack.getAttribute("data-src");
      var nextSongName = nextTrack.innerText;
      playAudio(nextSource, nextSongName);
      // Highlight the next track in the playlist
      highlightCurrentTrack(nextTrack);
      updatePlayPauseButton();
    }

    // Function to highlight the currently playing track
    function highlightCurrentTrack(track) {
      var currentlyPlaying = playlist.querySelector(".playing");
      if (currentlyPlaying) {
        currentlyPlaying.classList.remove("playing");
      }
      track.classList.add("playing");
    }

    // Function to update #artista-info with the name of the currently playing song
    function updateArtistaInfo(songName) {
      var artistaInfo = document.getElementById("artista-info");
      artistaInfo.innerHTML = "<h5>" + songName + "</h5>";
    }

    // Function to update the play-pause button text based on the playing state
    function updatePlayPauseButton() {
    var playPauseButton = document.getElementById("play-pause-button");
    playPauseButton.className = isPlaying ? "fas fa-pause" : "fas fa-play";
  }
  });
</script>
