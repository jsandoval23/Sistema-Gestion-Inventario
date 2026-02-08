<?php
/**
 * Conexión PDO a MySQL (singleton)
 * Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
 */

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Obtiene la instancia única de PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }
        return self::$instance;
    }

    private static function createConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            self::$config['host'],
            self::$config['port'],
            self::$config['database'],
            self::$config['charset']
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            return new PDO(
                $dsn,
                self::$config['username'],
                self::$config['password'],
                $options
            );
        } catch (PDOException $e) {
            throw new PDOException('Error de conexión: ' . $e->getMessage(), (int) $e->getCode());
        }
    }

    /** Evitar clonación */
    private function __clone() {}
}
