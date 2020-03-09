<?php
namespace Cms\Service;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\PredicateSet;

class Article extends Model {
    public function __construct($adapter) {
        parent::__construct($adapter);

        $this->tableName = 'article';
        $this->primary  = 'article_id';
    }

    public function getWithPaging($page, $pageItemPerCount, $type = 1)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary . ' DESC');

        $where = new Where();
        $select->where($where->equalTo('article_type', $type));

        $paginatorAdapter = new DbSelect($select, $this->adapter);
        $result = new Paginator($paginatorAdapter);

        $result->setCurrentPageNumber($page);
        $result->setItemCountPerPage($pageItemPerCount);

        return $result;
    }
}