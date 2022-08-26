<?php

namespace analib\Core\Data;

/**
 *
 * @author acround
 */
interface AnaObjectInterface
{

    public function getClassName();

    public function getFields();

    public function getValues();

    public function init(array $row = array());
}
