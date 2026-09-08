<?php

namespace analib\Core\DB;

/**
 * Description of Resource
 *
 * @author acround
 */
class Resource extends BaseResource
{
    protected int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): Resource
    {
        $this->id = $id;
        return $this;
    }
}
