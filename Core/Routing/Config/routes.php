<?php 

namespace Core;

/**
 * Classe utilitaire pour gérer les réponses HTTP.
 * Fournit une méthode pour effectuer une redirection vers une autre URL.
 */

class Http
{
    /**
     * Redirige l'utilisateur vers une URL donnée.
     *
     * @param string $url L'URL vers laquelle rediriger l'utilisateur.
     *
     * @return void
     */
    protected function redirectResponse(string $url): void
    {
        header("Location: $url");
        exit();
    }
}
