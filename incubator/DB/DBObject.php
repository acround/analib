<?php

namespace analib\incubator\DB;

/**
 * @author acround
 */
abstract class DBObject //implements Identifiable
{

    /**
     * @var array
     */
    private $fields = array(
        'id' => null
    );

    /**
     * @return int
     */
    public function getId()
    {
        return $this->fields['id'];
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->fields['id'] = $id;
        return $this;
    }

    protected function addFields(array $fields)
    {
        foreach ($fields as $field) {
            if (!isset($this->fields[$field])) {
                $this->fields[$field] = null;
            }
        }
    }

    protected function getFieldNames()
    {
        return array_keys($this->fields);
    }

    public function loadFromRow(array $row)
    {
        foreach ($row as $field => $value) {
            if (isset($this->fields[$field])) {
                $this->fields[$field] = $value;
            }
        }
    }

}
