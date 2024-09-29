<?php

use PDODb as DB;

class DatabaseHelper
{
    private ?array $result = null;

    private DB $connect;
    private const PARAM_DB = [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'username' => 'elite_api', 
        'password' => 'Trash2012!',
        'dbname'=> 'elite_api',
        'charset' => 'utf8'
    ];

    public function __construct(
        private string $table
    )
    {
        $this->connect = new PDODb(self::PARAM_DB);
        $this->table = $table;
    }

    public function fetchAll(
        ?array $where = null,
        ?array $orderBy = null,
        ?int $page = null
    ): self
    {
        if (!is_null($where)) {
            $this->setWhere($where);
        }
        if (!is_null($orderBy)) {
            $this->setOrderBy($orderBy);
        }
        $this->setResult(
            !is_null($page) ? $this->connect->paginate(
                $this->table, $page
            ) : $this->connect->get(
                $this->table
            )
        );

        return $this;
    }

    public function getTotalPages()
    {
        return $this->connect->totalPages;
    }

    public function fetchOne(int $id, array $where = null): self
    {
        if (!is_null($where)) {
            $this->setWhere($where);
        }
        $this->setResult(
            $this->connect->where('id', $id)->getOne(
                $this->table
            ) ?: null
        );

        return $this;
    }

    public function insertData(array $data): int
    {
        return $this->connect->insert(
            $this->table,
            $data
        );
    }

    public function updateData(
        int $id,
        array $data
    ): bool
    {
        $this->setWhere(['id' => $id]);

        return $this->connect->update(
            $this->table,
            $data
        );
    }

    public function deleteItem(int $id): bool
    {
        $this->setWhere(['id' => $id]);

        return $this->connect->delete(
            $this->table
        );
    }

    public function updateStat(
        int $id
    ): bool
    {
        $this->setWhere(['id' => $id]);

        return $this->connect->update(
            $this->table,
            [
                'view_day' => $this->connect->inc(1),
                'view_month' => $this->connect->inc(1)
            ]
        );
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    private function setResult(?array $result): self
    {
        $this->result = $result;

        return $this;
    }

    private function setWhere(array $where): self
    {
        foreach ($where as $k => $v) {
            $this->connect->where($k, $v);
        }

        return $this;
    }

    private function setOrderBy(array $orderBy): self
    {
        foreach ($orderBy as $k => $v) {
            $this->connect->orderBy($k, $v);
        }

        return $this;
    }

}