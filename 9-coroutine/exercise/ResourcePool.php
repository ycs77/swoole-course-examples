<?php

namespace SwooleCourse;

use Swoole\Coroutine\Channel;

class ResourcePool implements PoolInterface
{
    /**
     * @var \Swoole\Coroutine\Channel
     */
    protected $pool;

    public function __construct($size = 100)
    {
        $this->pool = new Channel($size);

        for ($i = 0; $i < $size; $i++) {
            $this->pool->push($this->makeResource());
        }
    }

    public function makeResource()
    {
        return new Lucas();
    }

    public function get()
    {
        return $this->pool->pop();
    }

    public function put($resource): bool
    {
        return $this->pool->push($resource);
    }
}

class Lucas
{
    public $name = 'Lucas Yang';
}
