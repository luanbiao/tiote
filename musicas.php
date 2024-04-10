<?php
// Captura o parâmetro 'song' da URL
$song = isset($_GET['song']) ? $_GET['song'] : 'default';
?>

<?php include './header.php'?>

<!DOCTYPE html>
<head>
  <title>Eu nem acredito, que é o Tiotê!</title>
</head>

  <div id="corpo">
   <!-- Conteúdo da página de música -->
   <h2><?php echo $song; ?></h2>
  <!-- Conteúdo específico da música -->
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
        <i id="play-icon" class="fas fa-play"></i>
        <!-- Coloque aqui o código para a barra de duração da música -->
      </div>
    </div>
  </footer>

  
</body>
</html>

<?php include './footer.php'?>