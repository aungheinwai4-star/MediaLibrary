<?php

namespace App\Repository;

use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /* =========================
     * CORE EXECUTION HELPERS
     * ========================= */

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $data ?: [];
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $data ?: null;
    }

    protected function fetchValue(string $sql, array $params = []): mixed
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $value = $stmt->fetchColumn();

        $stmt->closeCursor();

        return $value;
    }

    protected function executeSP(string $sp, array $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare("CALL {$sp}");

        $stmt->execute($params);

        return $stmt;
    }

    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        $stmt->closeCursor();

        return $result;
    }
}