<?php

namespace Maerlyn\Repository;

use Knp\Repository;

class LevelHistory extends Repository
{
    public function findByCharacterId($character_id)
    {
        return $this->db->fetchAll(sprintf("SELECT * FROM %s WHERE character_id = ? ORDER BY `date` DESC;",
            $this->getTableName()), array($character_id));
    }

    public function recent()
    {
        return $this->db->fetchAll(sprintf("SELECT c.name, lh.level, lh.date, lh.is_death FROM %s lh "
            . "INNER JOIN tibiahu_character c ON c.id = lh.id ORDER BY lh.date DESC LIMIT 10;", $this->getTableName()));
    }

    public function getTableName()
    {
        return "tibiahu_levelhistory";
    }
}
