<?php

namespace App\Traits;

trait ConditionQueryTrait
{
    public function applyCondition($query, $column, $condition, $value, $values, $isDate = false)
    {
        if ($isDate) {
            switch ($condition) {
                case 'between':
                    if (isset($values[1])) {
                        $query->whereBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '!between':
                    if (isset($values[1])) {
                        $query->whereNotBetween($column, [$values[0], $values[1]]);
                    }
                    break;
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '=':
                    $query->whereDate($column, $condition, $value);
                    break;
                case '!=':
                case 'not':
                    $query->whereDate($column, '!=', $value);
                    break;
                case 'null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNull($column)->orWhere($column, '');
                    });
                    break;
                case '!null':
                    $query->where(function ($q) use ($column) {
                        $q->whereNotNull($column)->where($column, '!=', '');
                    });
                    break;
            }
        } else {
            if ($column === 'no_receiver') {
                switch ($condition) {
                    case '=':
                    case 'equals':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%\"$value\"%");
                        });
                        break;
                    case '!=':
                    case 'not':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonDoesntContain('no_receiver', $value)
                                ->where('no_receiver', '!=', $value)
                                ->where('no_receiver', 'NOT LIKE', "%\"$value\"%");
                        });
                        break;
                    case 'contains':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%$value%");
                        });
                        break;
                    case '!contains':
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonDoesntContain('no_receiver', $value)
                                ->where('no_receiver', 'NOT LIKE', "%$value%");
                        });
                        break;
                    default:
                        $query->where(function ($q) use ($value) {
                            $q->whereJsonContains('no_receiver', $value)
                                ->orWhere('no_receiver', $value)
                                ->orWhere('no_receiver', 'LIKE', "%\"$value\"%");
                        });
                        break;
                }
            } else {
                switch ($condition) {
                    case 'between':
                        if (isset($values[1])) {
                            $query->whereBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '!between':
                        if (isset($values[1])) {
                            $query->whereNotBetween($column, [$values[0], $values[1]]);
                        }
                        break;
                    case '=':
                    case 'equals':
                        $query->where($column, '=', $value);
                        break;
                    case '!=':
                    case 'not':
                        $query->where($column, '!=', $value);
                        break;
                    case 'contains':
                        $query->where($column, 'LIKE', "%$value%");
                        break;
                    case '!contains':
                        $query->where($column, 'NOT LIKE', "%$value%");
                        break;
                    case 'starts':
                        $query->where($column, 'LIKE', "$value%");
                        break;
                    case '!starts':
                        $query->where($column, 'NOT LIKE', "$value%");
                        break;
                    case 'ends':
                        $query->where($column, 'LIKE', "%$value");
                        break;
                    case '!ends':
                        $query->where($column, 'NOT LIKE', "%$value");
                        break;
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':
                        $query->where($column, $condition, $value);
                        break;
                    case 'null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNull($column)->orWhere($column, '');
                        });
                        break;
                    case '!null':
                        $query->where(function ($q) use ($column) {
                            $q->whereNotNull($column)->where($column, '!=', '');
                        });
                        break;
                }
            }
        }
    }
}
