<?php

namespace App\Core;

use ReflectionClass;
use ReflectionParameter;
use RuntimeException;

class Container
{
    /** @var array<string, mixed> Singleton/instances déjà construites */
    private array $instances = [];

    /** @var array<string, callable> Factories/bindings custom (si besoin) */
    private array $bindings = [];

    /**
     * Récupère/instancie une classe avec autowiring
     */
    public function get(string $class)
    {
        // Retourne l'instance si déjà construite (cache simple)
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        // Si une factory est définie, l'utiliser
        if (isset($this->bindings[$class])) {
            $instance = ($this->bindings[$class])($this);
            return $this->instances[$class] = $instance;
        }

        // Autowiring par réflexion
        if (!class_exists($class)) {
            throw new RuntimeException("Classe introuvable: {$class}");
        }

        $ref = new ReflectionClass($class);

        // Pas de constructeur, instanciation directe
        $ctor = $ref->getConstructor();
        if ($ctor === null) {
            $instance = new $class();
            return $this->instances[$class] = $instance;
        }

        // Résoudre chaque paramètre du constructeur
        $args = [];
        /** @var ReflectionParameter $param */
        foreach ($ctor->getParameters() as $param) {
            $type = $param->getType();

            // Paramètre typé (classe) → résolution récursive
            if ($type && !$type->isBuiltin()) {
                $depClass = $type->getName();
                $args[] = $this->get($depClass);
                continue;
            }

            // Paramètre scalaire ou sans type → essayer une valeur par défaut
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            // Paramètre nullable sans valeur → null
            if ($type && $type->allowsNull()) {
                $args[] = null;
                continue;
            }

            // Sinon, impossible de résoudre automatiquement
            $name = $param->getName();
            throw new RuntimeException("Impossible de résoudre le paramètre '{$name}' pour {$class}");
        }

        // Instancie avec les arguments résolus
        $instance = $ref->newInstanceArgs($args);
        return $this->instances[$class] = $instance;
    }

    /**
     * Lie une classe à une factory personnalisée (utile pour config/DB)
     */
    public function bind(string $id, callable $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    /**
     * Permet d'enregistrer des singletons (ex: PDO, config, logger)
     */
    public function set(string $id, $instance): void
    {
        $this->instances[$id] = $instance;
    }
}
