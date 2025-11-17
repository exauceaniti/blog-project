<?php
namespace Src\Entity;

class Post {
    public int $id;
    public string $titre;
    public string $contenu;
    public int $auteur_id;
    public string $date_publication;
    public ?string $media_path = null;
    public ?string $media_type = null;

    /** @var int Nombre de commentaires (calculé, non persistant) */
    public int $comment_count = 0;

    public function __construct(array $data = []) {
        $this->id = (int)($data['id'] ?? 0);
        $this->titre = $data['titre'] ?? '';
        $this->contenu = $data['contenu'] ?? '';
        $this->auteur_id = (int)($data['auteur_id'] ?? 0);
        $this->date_publication = $data['date_publication'] ?? '';
        $this->media_path = $data['media_path'] ?? null;
        $this->media_type = $data['media_type'] ?? null;

        // Optionnel: hydrater si fourni
        if (isset($data['comment_count'])) {
            $this->comment_count = (int)$data['comment_count'];
        }
    }
}
