<?php

namespace Api\Models\Tools;

use Api\Models\Tools\QueryConverter as QC;
use Exception;

class QueryObject
{
    private string $action;
    private string $query;
    private string $table;
    private array $columns;
    private string $like;
    private string $where;
    private string $join;
    private string $groupBy;
    private array $orderBy;
    private int $limit;
    private int $offset;
    private int $numOfJoins;
    private bool $grouppable;
    private array $values;
    private array $aliasMap;

    private function __construct(string $action)
    {
        $this->action = $action;
        $this->query = '';
        $this->table = '';
        $this->columns = ['*'];
        $this->like = '';
        $this->where = '';
        $this->join = '';
        $this->groupBy = '';
        $this->orderBy = [];
        $this->limit = 0;
        $this->offset = 0;
        $this->numOfJoins = 0;
        $this->grouppable = false;
        $this->values = [];
        $this->aliasMap = [];
    }

    public static function select(): QueryObject
    {
        return new QueryObject('select');
    }

    public static function insert(): QueryObject
    {
        return new QueryObject('insert');
    }

    public static function update(): QueryObject
    {
        return new QueryObject('update');
    }

    public static function delete(): QueryObject
    {
        return new QueryObject('delete');
    }

    public static function count(string $column = '*', string $alias = null): string
    {
        if (!empty($alias)) {
            return "COUNT($column) AS $alias";
        } else {
            return "COUNT($column)";
        }
    }

    public static function max(string $column = '*', string $alias = null): string
    {
        if (!empty($alias)) {
            return "MAX($column) AS $alias";
        } else {
            return "MAX($column)";
        }
    }

    public function __toString(): string
    {
        switch ($this->action) {
            case 'select':
                $this->query = QC::composeSelect(
                    $this->table,
                    $this->columns,
                    $this->where,
                    $this->join,
                    $this->like,
                    $this->groupBy,
                    $this->orderBy,
                    $this->limit,
                    $this->offset,
                    $this->numOfJoins
                );
                break;
            case 'insert':
                $this->query = QC::composeInsert(
                    $this->table,
                    $this->columns,
                    $this->values
                );
                break;
            case 'update':
                $this->query = QC::composeUpdate(
                    $this->table,
                    $this->columns,
                    $this->values,
                    $this->where
                );
                break;
            case 'delete':
                $this->query = QC::composeDelete(
                    $this->table,
                    $this->where
                );
                break;
        }
        return $this->query;
    }

    public function table($table): QueryObject
    {
        if (!empty($this->table)) {
            throw new Exception('Incorrect query formation: using two tables without joining them');
        }
        if ('string' == gettype($table)) {
            $this->table = $table;
        } elseif ('QueryObject' == gettype($table)) {
            if ('select' == $table->action) {
                ob_start();
                echo $table;
                $tableString = ob_get_clean();
                $this->table = "($tableString)";
            } else {
                throw new Exception('Incorrect query formation: using composed table for other purpose than selection');
            }
        }
        return $this;
    }

    public function join($tables, $conditions, ...$joinedTableName): QueryObject
    {

        if (!empty($joinedTableName)) {
            $joinCond = $tables[1] . '.' . $conditions[1] . ' = ' . $joinedTableName[0] . '.' . $conditions[0];
            $tables[0] = $tables[0] . " AS $joinedTableName[0]";
        } else {
            $joinCond = $tables[1] . '.' . $conditions[1] . ' = ' . $tables[0] . '.' . $conditions[0];
        }

        if (!empty($this->join)) {
            $this->join = $this->join . ' JOIN ' . $tables[0] . ' ON ' . $joinCond;
        } else {
            $this->join = $tables[0] . ' ON ' . $joinCond;
        }

        return $this;
    }

    public function columns(string ...$columns): QueryObject
    {
        if ('update' == $this->action && count($columns) != 1) {
            throw new Exception('Incorrect query formation: selecting more than 1 column for update');
        }
        foreach ($columns as $column) {
            if ('COUNT' == substr($column, 0, 5)) {
                $this->grouppable = true;
                break;
            }
        }
        $this->columns = $columns;
        return $this;
    }

    public function addColumns(string ...$columns): QueryObject
    {
        if (!$this->grouppable) {
            foreach ($columns as $column) {
                if ('COUNT' == substr($column, 0, 5)) {
                    $this->grouppable = true;
                    break;
                }
            }
        }
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

    public function where(array ...$conditions): QueryObject
    {
        $conditionStrings = [];
        foreach ($conditions as $condition) {
            if (count($condition) > 3) {
                throw new Exception('Incorrect query formation: wrong where condition format');
            }
            if (1 == count($condition)) {
                $conditionStrings[] = $condition[0];
            } else {
                if (gettype($condition[1]) != 'integer') {
                    $condition[1] = "'$condition[1]'";
                }
                $conditionStrings[] = "$condition[0] $condition[2] $condition[1]";
            }
        }
        if (!empty($this->where)) {
            $this->where .= ' AND ' . implode(' AND ', $conditionStrings);
        } else {
            $this->where = implode(' AND ', $conditionStrings);
        }
        return $this;
    }

    public function like(string $key): QueryObject
    {
        if (!empty($this->like)) {
            $this->like .= "'%$key%'";
        } else {
            $this->like = "'%$key%'";
        }
        return $this;
    }

    public function limit(int $limit): QueryObject
    {
        if (!empty($this->limit)) {
            throw new Exception('Incorrect query formation: rewriting the LIMIT statement');
        }
        if ($limit > 0) {
            $this->limit = $limit;
        } else {
            throw new Exception('Incorrect query formation: negative limit setting');
        }
        return $this;
    }

    public function offset(int $offset): QueryObject
    {
        if (!empty($this->offset)) {
            throw new Exception('Incorrect query formation: rewriting the OFFSET statement');
        }
        if ($offset >= 0) {
            $this->offset = $offset;
        } else {
            throw new Exception('Incorrect query formation: negative offset setting');
        }
        return $this;
    }

    public function groupBy(string $column): QueryObject
    {
        if (!$this->grouppable) {
            throw new Exception('Incorrect query formation: grouping ungrouppable statement');
        }
        if (!empty($this->groupBy)) {
            throw new Exception('Incorrect query formation: rewriting the GROUP BY statement');
        }
        $this->groupBy = $column;
        return $this;
    }

    public function orderBy(array ...$orders): QueryObject
    {
        if (!empty($this->orderBy)) {
            throw new Exception('Incorrect query formation: rewriting the ORDER BY statement');
        }
        $orderBy = [];
        $orderTypes = ['DESC', 'ASC'];
        foreach ($orders as $order) {
            if (!empty($order[1])) {
                if (!in_array($order[1], $orderTypes)) {
                    throw new Exception('Incorrect query formation: unknown order type');
                }
            }
            $orderBy[] = implode(' ', $order);
        }

        $this->orderBy = $orderBy;
        return $this;
    }

    public function values(...$values): QueryObject
    {
        if ('select' == $this->action) {
            throw new Exception('Incorrect query formation: using VALUES statement while selecting');
        }
        if ('update' == $this->action && count($values) != 1) {
            throw new Exception('Incorrect query formation: selecting more than 1 value for update');
        }
        if (count($this->columns) != count($values)) {
            throw new Exception('Incorrect query formation: number of inserted values doesn`t match number of columns');
        }
        foreach ($values as &$value) {
            if (gettype($value) != 'integer') {
                $value = "'$value'";
            }
        }
        $this->values = $values;
        return $this;
    }

    public function getNumOfJoins(): int
    {
        return $this->numOfJoins;
    }
}
