<?php
namespace Src\Entity;

/**
 * Entité Post
 * ----------
 * Représente un article du blog.
 */
class Post {
    public int $id;
    public string $titre;
    public string $contenu;
    public int $auteur_id;
    public string $date_publication;
    public ?string $media_path;
    public ?string $media_type;

    public function __construct(array $data = []) {
        $this->id              = $data['id'] ?? 0;
        $this->titre           = $data['titre'] ?? '';
        $this->contenu         = $data['contenu'] ?? '';
        $this->auteur_id       = $data['auteur_id'] ?? 0;
        $this->date_publication= $data['date_publication'] ?? '';
        $this->media_path      = $data['media_path'] ?? null;
        $this->media_type      = $data['media_type'] ?? null;
    }
}
