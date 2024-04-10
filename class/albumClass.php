<?php

class Album
{
    private $albums;

    public function __construct($jsonFilePath) {
        $this->loadAlbums($jsonFilePath);
    }

    private function loadAlbums($jsonFilePath) {
        $jsonContents = file_get_contents($jsonFilePath);
        $this->albums = json_decode($jsonContents, true);
    }

    public function fillAlbum($albumSlug)
    {
        if (isset($this->albums[$albumSlug])) {
            return $this->albums[$albumSlug]["songs"];
        } else {
            return [];
        }
    }

    public function pegarNomeAlbums()
    {
        return json_encode(array_keys($this->albums), JSON_UNESCAPED_UNICODE);
    }

    public function getAlbumTitle($albumSlug)
    {
        return isset($this->albums[$albumSlug]) ? $this->albums[$albumSlug]["title"] : "Unknown Album";
    }
}
