<?php
require_once 'getid3/getid3.php';

// Pasta onde estão as playlists
$playlistFolder = './';

// Obtém o nome da playlist a ser carregada a partir dos parâmetros da solicitação AJAX
$playlistName = isset($_GET['playlist']) ? $_GET['playlist'] : '';

// Caminho completo para a pasta da playlist selecionada
$playlistPath = $playlistFolder . '/' . $playlistName;

// Array para armazenar detalhes de todas as músicas na playlist
$allMusicDetails = array();

// Título do álbum (inicializado como vazio)
$albumTitle = '';

// Verifica se a pasta da playlist existe
if (is_dir($playlistPath)) {
    // Cria uma instância da classe getID3
    $getID3 = new getID3();

    // Loop pelos arquivos na pasta da playlist
    foreach (glob($playlistPath . '/*.mp3') as $musicFile) {
        // Analisa o arquivo MP3
        $fileInfo = $getID3->analyze($musicFile);

        // Obtém a imagem incorporada (cover art) se existir
        $coverData = isset($fileInfo['comments']['picture'][0]['data']) ? $fileInfo['comments']['picture'][0]['data'] : null;

        // Converte os dados da imagem para o formato base64
        $coverBase64 = $coverData ? 'data:image/jpeg;base64,' . base64_encode($coverData) : '';

        // Obtém o título do álbum a partir dos metadados do arquivo MP3 (se ainda não foi obtido)
        if (empty($albumTitle) && isset($fileInfo['tags']['id3v2']['album'][0])) {
            $albumTitle = $fileInfo['tags']['id3v2']['album'][0];
        }

        // Obtém o caminho do arquivo de letra correspondente
        $lyricsFile = $playlistPath . '/' . pathinfo($musicFile, PATHINFO_FILENAME) . '.txt';

        // Verifica se o arquivo de letra existe
        $lyrics = file_exists($lyricsFile) ? file_get_contents($lyricsFile) : '';


        // Adiciona detalhes da música ao array, incluindo a letra
        $musicDetails = array(
            'file' => './player/' . $musicFile,
            'title' => pathinfo($musicFile, PATHINFO_FILENAME),
            'cover' => $coverBase64,
            'lyrics' => $lyrics,
        );

        $allMusicDetails[] = $musicDetails;
    }
} else {
    // Se a pasta da playlist não existir, envie uma resposta de erro
    header('HTTP/1.1 404 Not Found');
    exit('Pasta da playlist não encontrada.');
}

$output = array(
    'albumTitle' => $albumTitle,
    'musicDetails' => $allMusicDetails
);

if (empty($albumTitle) || empty($allMusicDetails)) {
    // Se o título do álbum ou detalhes da música estiverem ausentes, envie uma resposta de erro
    header('HTTP/1.1 500 Internal Server Error');
    exit('Dados da playlist inválidos ou ausentes.');
}

// Saída dos detalhes da playlist como JSON
header('Content-Type: application/json');
echo json_encode($output);
?>
