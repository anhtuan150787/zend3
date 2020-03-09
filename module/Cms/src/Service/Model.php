<?php
namespace Cms\Service;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class Model {

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getAdapter() {
        return $this->adapter;
    }

    public function getTableGateway() {
        $this->tableGateway = new TableGateway($this->tableName, $this->adapter);

        return $this->tableGateway;
    }

    public function getModel($model) {
        $modelObject = new $model($this->adapter);
        return $modelObject;
    }

    public function getWithPaging($page, $pageItemPerCount)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary . ' DESC');

        $paginatorAdapter = new DbSelect($select, $this->adapter);
        $result = new Paginator($paginatorAdapter);

        $result->setCurrentPageNumber($page);
        $result->setItemCountPerPage($pageItemPerCount);

        return $result;
    }

    public function get($id) {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->primary . ' = ' . $id;
        $statement = $this->adapter->query($sql);
        $result = $statement->execute();
        $data = $result->current();

        return $data;
    }

    public function add($args) {
        $this->getTableGateway()->insert($args);
        $id = $this->getTableGateway()->lastInsertValue;

        return $id;
    }

    public function update($args, $id) {
        return $this->getTableGateway()->update($args, array($this->primary => $id));
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $this->primary . '=' . $id;
        $statement = $this->adapter->query($sql);
        $result = $statement->execute();

        return $result;
    }

    public function deleteWhere($where)
    {
        return $this->getTableGateway()->delete($where);
    }
}