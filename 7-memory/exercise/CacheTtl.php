<?php

namespace SwooleCourse;

use Swoole\Table;

class CacheTtl implements CacheInterface
{
    /**
     * @var \Swoole\Table
     */
    protected $table;

    public function __construct()
    {
        $this->createTable();
    }

    public function createTable(): void
    {
        $this->table = new Table(1024, 0.2);
        $this->table->column('value', Table::TYPE_STRING, 1024);
        $this->table->column('ttl', Table::TYPE_INT, 4);
        $this->table->create();
    }

    public function isExpired(array $result): bool
    {
        return $result['ttl'] <= time();
    }

    public function put(string $key, string $value, int $ttl = 60): void
    {
        $this->table->set($key, [
            'value' => $value,
            'ttl' => time() + $ttl,
        ]);
    }

    public function get(string $key): ?string
    {
        $data = $this->table->get($key);

        if ($data === false) {
            $data = ['ttl' => 0];
        }

        if ($this->isExpired($data)) {
            $this->table->del($key);
            return null;
        }

        return $data['value'];
    }

    public function forget(string $key): void
    {
        $this->table->del($key);
    }

    public function flush(): void
    {
        foreach ($this->table as $key => $data) {
            $this->table->del($key);
        }
    }

    public function recycle(): void
    {
        foreach ($this->table as $key => $data) {
            if ($this->isExpired($data)) {
                $this->table->del($key);
            }
        }
    }
}
