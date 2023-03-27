<?php

namespace Livewire;

trait WithPagination
{
    public $paginators = [];

    public function queryStringWithPagination()
    {
        return collect($this->paginators)->mapWithKeys(function ($page, $pageName) {
            return ['paginators.'.$pageName => ['use' => 'push', 'as' => $pageName, 'alwaysShow' => false]];
        })->toArray();
    }

    public function previousPage($pageName = 'page')
    {
        $this->setPage(max(($this->paginators[$pageName] ?? 1) - 1, 1), $pageName);
    }

    public function nextPage($pageName = 'page')
    {
        $this->setPage(($this->paginators[$pageName] ?? 1) + 1, $pageName);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function setPage($page, $pageName = 'page')
    {
        if (is_numeric($page)) {
            $page = (int) ($page <= 0 ? 1 : $page);
        }

        $beforePaginatorMethod = 'updatingPaginators';
        $afterPaginatorMethod = 'updatedPaginators';

        $beforeMethod = 'updating' . $pageName;
        $afterMethod = 'updated' . $pageName;

        if (method_exists($this, $beforePaginatorMethod)) {
            $this->{$beforePaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $beforeMethod)) {
            $this->{$beforeMethod}($page, null);
        }

        $this->paginators[$pageName] = $page;

        if (method_exists($this, $afterPaginatorMethod)) {
            $this->{$afterPaginatorMethod}($page, $pageName);
        }

        if (method_exists($this, $afterMethod)) {
            $this->{$afterMethod}($page, null);
        }
    }
}
