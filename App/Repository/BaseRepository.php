<?php

namespace App\Repository;

use App\Contract\BaseRepositoryInterface;
use PDO;

class BaseRepository implements BaseRepositoryInterface
{
    protected PDO $db;

    // ===== CONFIG (child defines this) =====
    protected string $source = 'table';
    // table | procedure

    protected string $table = '';

    protected string $primaryKey = 'id';

    protected array $searchColumns = [];

    protected ?string $countProcedure = null;
    protected ?string $listProcedure = null;
    protected ?string $searchProcedure = null;
    protected ?string $findProcedure = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        if ($this->source === 'procedure') {
            throw new \Exception("Create not implemented for procedure mode");
        }

        $columns = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ")
            VALUES ($placeholders)";

        return $this->execute($sql, array_values($data));
    }

    public function update(int $id, array $data): bool
    {
        if ($this->source === 'procedure') {
            throw new \Exception("Update not implemented for procedure mode");
        }

        $fields = [];
        $values = [];

        foreach ($data as $column => $value) {
            $fields[] = "$column = ?";
            $values[] = $value;
        }

        $values[] = $id;

        $sql = "UPDATE {$this->table}
            SET " . implode(',', $fields) . "
            WHERE {$this->primaryKey} = ?";

        return $this->execute($sql, $values);
    }

    public function delete(int $id): bool
    {
        if ($this->source === 'procedure') {
            throw new \Exception("Delete not implemented for procedure mode");
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";

        return $this->execute($sql, [$id]);
    }

    public function count(?string $category = null, ?string $search = null): int
    {
        // PROCEDURE MODE
        if ($this->source === 'procedure' && $this->countProcedure) {
            $stmt = $this->executeSP($this->countProcedure, [$search, $category]);

            $value = $stmt->fetchColumn();
            $stmt->closeCursor();

            return (int) $value;
        }

        // TABLE MODE
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        [$sql, $params] = $this->applyFilters($sql, $search, $category);

        return (int) $this->fetchValue($sql, $params);
    }

    public function getAll(?int $limit = null, int $offset = 0): array
    {
        if ($this->source === 'procedure' && $this->listProcedure) {
            $stmt = $this->executeSP($this->listProcedure, [$limit, $offset]);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $data ?: [];
        }

        $sql = "SELECT * FROM {$this->table}";
        $sql .= $this->applyLimit($limit, $offset);

        return $this->fetchAll($sql);
    }

    public function findById(int $id): ?array
    {
        if ($this->source === 'procedure' && $this->findProcedure) {

            $stmt = $this->executeSP($this->findProcedure, [$id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $stmt->closeCursor();
                return null;
            }

            $stmt->nextRowset();

            $relations = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $relations[] = $row;
            }

            $stmt->closeCursor();

            return $this->afterFindById($data, $relations);
        }

        $data = $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );

        return $data ? $this->afterFindById($data, []) : null;
    }

    public function search(?string $search, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        if ($this->source === 'procedure' && $this->searchProcedure) {
            $stmt = $this->executeSP($this->searchProcedure, [
                $search,
                $category,
                $limit,
                $offset,
            ]);

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $stmt->closeCursor();

            return $data ?: [];
        }

        $sql = "SELECT * FROM {$this->table}";
        [$sql, $params] = $this->applyFilters($sql, $search, $category);

        $sql .= $this->applyLimit($limit, $offset);

        return $this->fetchAll($sql, $params);
    }

    protected function afterFindById(array $data, array $relations): array
    {
        if (!empty($relations)) {
            $data['relations'] = $relations;
        }

        return $data;
    }

    /* =========================
     * FILTER BUILDER 
     * ========================= */
    protected function applyFilters(string $sql, ?string $search, ?string $category): array
    {
        $conditions = [];
        $params = [];

        if ($search && $this->searchColumns) {
            $likes = [];

            foreach ($this->searchColumns as $col) {
                $likes[] = "$col LIKE ?";
                $params[] = "%$search%";
            }

            $conditions[] = '(' . implode(' OR ', $likes) . ')';
        }

        if ($category) {
            $conditions[] = "category = ?";
            $params[] = $category;
        }

        if ($conditions) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        return [$sql, $params];
    }

    protected function applyLimit(?int $limit, int $offset): string
    {
        return $limit ? " LIMIT {$limit} OFFSET {$offset}" : "";
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
        $placeholders = '';

        if (!empty($params)) {
            $placeholders = '(' . implode(',', array_fill(0, count($params), '?')) . ')';
        }

        $stmt = $this->db->prepare("CALL {$sp}{$placeholders}");
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
