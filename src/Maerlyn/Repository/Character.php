<?php

namespace Maerlyn\Repository;

use Knp\Repository;

class Character extends Repository
{
    public function findAll()
    {
        return $this->db->fetchAll(sprintf("SELECT * FROM %s ORDER BY name;", $this->getTableName()));
    }

    public function getTableName()
    {
        return "tibiahu_character";
    }
}
