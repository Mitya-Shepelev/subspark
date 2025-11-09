<?php
// Minimal DB helper for progressive PDO migration

class DB {
    private static ?PDO $pdo = null;

    public static function init(?PDO $pdo): void {
        self::$pdo = $pdo;
    }

    public static function pdo(): PDO {
        if (!self::$pdo) {
            throw new RuntimeException('PDO is not initialized');
        }
        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function one(string $sql, array $params = []): ?array {
        $stmt = self::query($sql, $params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function col(string $sql, array $params = []) {
        $stmt = self::query($sql, $params);
        $val = $stmt->fetchColumn();
        return $val;
    }

    public static function all(string $sql, array $params = []): array {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function exec(string $sql, array $params = []): int {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function begin(): void { self::pdo()->beginTransaction(); }
    public static function commit(): void { self::pdo()->commit(); }
    public static function rollBack(): void { if (self::pdo()->inTransaction()) self::pdo()->rollBack(); }
    public static function lastId(): string { return self::pdo()->lastInsertId(); }
}
