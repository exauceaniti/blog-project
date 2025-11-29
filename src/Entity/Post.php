<?php

namespace Src\Entity;

class Post
{
    public int $id;
    public string $titre;
    public string $contenu;
    public int $auteur_id;
    public string $date_publication;
    public ?string $media_path = null;
    public ?string $media_type = null;

    // NOUVEAUX CHAMPS CALCULÉS/JOINTÉS
    public string $auteur_nom;
    public int $comment_count = 0;
    // FIN NOUVEAUX CHAMPS

    public function __construct(array $data)
    {
        // Hydratation de l'objet à partir du tableau de données
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        // Conversion de $comment_count en int (Entier)
        $this->comment_count = (int) ($this->comment_count ?? 0);
    }
}
