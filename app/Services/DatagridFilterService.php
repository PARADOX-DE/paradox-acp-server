<?php

namespace App\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DatagridFilterService
{
    /**
     * Filter by operator for datagrid
     *
     * @param Builder $builder
     * @param string $column
     * @param string $filterOperator
     * @param string $value
     * @return Builder
     */
    public static function filterByOperator(Builder $builder, string $column, string $filterOperator, string $value): Builder
    {
        switch ($filterOperator) {
            case "contains":
                return $builder->where($column, "LIKE", "%{$value}%");
                break;

            case "notContains":
                return $builder->where($column, "NOT LIKE", "%{$value}%");
                break;

            case "eq":
                return $builder->where($column, "=", $value);
                break;

            case "neq":
                return $builder->where($column, "!=", $value);
                break;

            case "empty":
                return $builder->where($column, "=", "");
                break;

            case "notEmpty":
                return $builder->where($column, "!=", "");
                break;

            case "startsWith":
                return $builder->where($column, "LIKE", "{$value}%");
                break;

            case "endsWith":
                return $builder->where($column, "LIKE", "%{$value}");
                break;
        }
    }

    /**
     * Filter relation by operator
     *
     * @param Builder $builder
     * @param string $relation
     * @param string $column
     * @param string $filterOperator
     * @param string $value
     * @return Builder
     */
    public static function filterRelationByOperator(Builder $builder, string $relation, string $column, string $filterOperator, string $value): Builder
    {
        switch ($filterOperator) {
            case "contains":
                return $builder->whereRelation($relation, $column, "LIKE", "%{$value}%")
                    ->with("{$relation}:id,{$column}");

            case "notContains":
                return $builder->whereRelation($relation, $column, "NOT LIKE", "%{$value}%");

            case "eq":
                return $builder->whereRelation($relation, $column, "=", $value);
                break;

            case "neq":
                return $builder->whereRelation($relation, $column, "!=", $value);
                break;

            case "empty":
                return $builder->whereRelation($relation, $column, "=", "");
                break;

            case "notEmpty":
                return $builder->whereRelation($relation, $column, "!=", "");
                break;

            case "startsWith":
                return $builder->whereRelation($relation, $column, "LIKE", "{$value}%");
                break;

            case "endsWith":
                return $builder->whereRelation($relation, $column, "LIKE", "%{$value}");
                break;
        }
    }

    public function filter(Builder $builder, int $skip, int $limit, string $jsonFilterString): array
    {
        $filterObject = json_decode($jsonFilterString);
        $columns = [];

        foreach($filterObject as $filterValue) {
            if(isset($filterValue->virtual) && $filterValue->virtual) continue;
            if(isset($filterValue->relationTable) && isset($filterValue->relationField) && $filterValue->relationTable)
            {
                $builder = self::filterRelationByOperator($builder, $filterValue->relationTable, $filterValue->relationField, $filterValue->operator, $filterValue->value);

                continue;
            }

            $builder = self::filterByOperator($builder, $filterValue->name, $filterValue->operator, $filterValue->value);
            $columns[] = $filterValue->name;
        }

        $count = $builder->count();

        $data = $builder->skip($skip)
            ->limit($limit)
            ->select($columns)
            ->get();

        return [
            "data" => $data,
            "count" => $count
        ];
    }
}
