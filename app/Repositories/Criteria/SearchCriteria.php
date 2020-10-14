<?php

namespace Foundry\System\Repositories\Criteria;

class SearchCriteria
{
    protected $columns;

    protected $keyword;

    public function __construct($columns, $keyword)
    {
        $this->columns = $columns;
        $this->keyword = $keyword;
    }

    public function apply($query, $repository)
    {
        $query->where(function ($q) {
            foreach ($this->columns as $column) {
                $q->orWhere("{$column}", "LIKE", "%{$this->keyword}%");
            }
        });

        return $query;
    }
}
