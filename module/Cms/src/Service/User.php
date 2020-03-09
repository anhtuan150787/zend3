<?php
namespace Cms\Service;

use Laminas\Db\Adapter\Adapter;

class User extends Model {
    public function __construct($adapter) {
        parent::__construct($adapter);

        $this->tableName = 'user';
        $this->primary  = 'id';
    }
}