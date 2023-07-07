<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $fields)
    {
        $fields = explode(',', $fields);
        $excludedFields = $this->getExcludedFields($value);
        $fields = array_diff($fields, $excludedFields);
        $value = is_array($value) ? implode(',', $value) : mb_strtolower($value);

        $this->applyFilter($query, $value, $fields);
    }

    private function getExcludedFields(string &$value): array
    {
        preg_match_all('/\ -(.*)$/', $value, $out);
        $value = trim(preg_replace('/\ -(.*)$/', '', $value));

        return array_map(function ($item) {
            return ltrim($item, '-');
        }, explode(',', $out[1][0] ?? ''));
    }

    private function applyFilter(Builder $query, string $value, array $fields): void
    {
        $query->where(function ($q) use ($value, $fields) {
            foreach ($fields as $field) {
                $q->orWhere(trim($field), 'LIKE', "%{$value}%");
            }
        });
    }
}
