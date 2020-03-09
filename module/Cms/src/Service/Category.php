<?php
namespace Cms\Service;

use Laminas\Db\Adapter\Adapter;

class Category extends Model {
    public function __construct($adapter) {
        parent::__construct($adapter);

        $this->tableName = 'category';
        $this->primary  = 'category_id';
    }

    public function getAll($parent = 0, $level = -1, $data = array())
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE category_parent = ' . $parent;
        $statement = $this->adapter->query($sql);
        $result = $statement->execute();

        $level++;

        foreach($result as $row) {
            $category_id = $row[$this->primary];

            $data[$category_id] = (array) $row;
            $data[$category_id]['category_level'] = $level;

            $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE category_parent = ' . $category_id;

            $statement = $this->adapter->query($sql);
            $result = $statement->execute();

            if ($result->count() > 0) {
                $data = $this->getAll($category_id, $level, $data);
            }
        }
        return $data;
    }
}