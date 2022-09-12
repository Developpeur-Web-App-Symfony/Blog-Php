<?php

namespace Repository;

use Framework\Model;
use \PDO;

class Role extends \Framework\Model
{

    public function getAllRoles(): array
    {
        $sql = 'SELECT * FROM roles';

        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Role::class);
    }

}