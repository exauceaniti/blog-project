<?php

namespace Src\Entity;

/**
 * Entité Comment
 * --------------
 * Représente un commentaire lié à un article.
 */
class Comment
{
    public int $id;
    public string $contenu;
    public int $auteur_id;
    public int $article_id;
    public string $date_commentaire;

    public function __construct(array $data = [])
    {
        $this->id              = $data['id'] ?? 0;
        $this->contenu         = $data['contenu'] ?? '';
        $this->auteur_id       = $data['auteur_id'] ?? 0;
        $this->article_id      = $data['article_id'] ?? 0;
        $this->date_commentaire = $data['date_commentaire'] ?? '';
    }
}
