<?php

namespace Api\Models\Tools;

class QueryConverter
{
    public static function composeSelect(
        string $table,
        array  $columns,
        string $where,
        string $join,
        string $like,
        string $groupBy,
        array  $orderBy,
        int    $limit,
        int    $offset,
        int    $numOfJoins = 0
    ): string
    {
        $columnString = implode(', ', $columns);
        $queryString = "SELECT $columnString FROM $table";
        if (!empty($join)) {
            $queryString .= " JOIN $join";
        }
        if (!empty($where)) {
            $queryString .= " WHERE $where";
        }

        if (!empty($like)) {
            $queryString .= " AND (title OR content LIKE $like)";
        }

        if (!empty($groupBy)) {
            if ($numOfJoins > 0) {
                $groupBy = "$groupBy";
            }
            $queryString .= " GROUP BY $groupBy";
        }
        if (!empty($orderBy)) {
            if ($numOfJoins > 0) {
                foreach ($orderBy as &$order) {
                    $order = "$order";
                }
            }
            $queryString .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        if (!empty($limit)) {
            $queryString .= " LIMIT $limit";
        }
        if (!empty($offset)) {
            $queryString .= " OFFSET $offset";
        }

        $queryString .= ';';
        return $queryString;
    }

    public static function composeInsert(
        string $table,
        array  $columns,
        array  $values
    ): string
    {
        $columnString = implode(', ', $columns);
        $valueString = implode(', ', $values);
        return "INSERT INTO $table ($columnString) VALUES ($valueString);";
    }

    public static function composeUpdate(
        string $table,
        array  $columns,
        array  $values,
        string $where
    ): string
    {
        if ($values[0] === "'NULL'") {
            $values[0] = 'NULL';
        }
        $queryString = "UPDATE $table SET $columns[0] = $values[0]";
        if (!empty($where)) {
            $queryString .= " WHERE $where";
        }
        $queryString .= ';';
        return $queryString;
    }

    public static function composeDelete(string $table, string $where): string
    {
        $queryString = "DELETE FROM $table WHERE $where";
        $queryString .= ';';
        return $queryString;
    }
}
