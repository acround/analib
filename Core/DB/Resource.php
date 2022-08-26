<?php

namespace analib\Core\DB;

/**
 * Description of Resource
 *
 * @author acround
 */
class Resource extends BaseResource
{
    protected id $id;

    public function getId(): id
    {
        return $this->id;
    }

    public function setId($id): Resource
    {
        $this->id = $id;
        return $this;
    }
}
