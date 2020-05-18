<?php

namespace App\Traits;


trait Pagination
{
    private $pageSizeLimit = 500;

    /**
     * @return bool|mixed
     */
    public function getPerPage()
    {
        $pageSize = request('per_page', 50);
        if (strtolower($pageSize) == 'all') {
            return false;
        }
        return min($pageSize, $this->pageSizeLimit);
    }

    /**
     * @return bool
     */
    public function isWithoutPagination()
    {
        $pageSize = request('per_page', 50);
        if (strtolower($pageSize) == 'all') {
            return true;
        }
        return false;
    }
}
