<?php

namespace App\Validators;

use App\Models\Stock;
use App\QueryFilters\StockQueryFilter;
use App\QuerySorters\StockQuerySorter;
use Exception;

class StockRequestValidator extends Validator
{
    /**
     * When page number is not provided, 0 indicates that we do not need pagination.
     */
    const DEFAULT_PAGE_NUMBER = 0;

    const DEFAULT_RECORD_PER_PAGE = 10;

    public function getValidatedQueryParamsForIndexRequest(): array
    {
        $queryParams = $this->makeMissingQueryParams();

        $this->validateArrayParams($queryParams);
        $this->validateOrderBY($queryParams);
        $this->validateNonNegativePageAndPerPage($queryParams);
        $this->validateAllowedQueryFields($queryParams['fields']);
        $this->validateAllowedFilterFields($queryParams['filters']);
        $this->validateAllowedSorterFields($queryParams['orderBy']);


        return $queryParams;
    }

    private function validateAllowedQueryFields(array $requestedFields): void
    {
        $allowed = Stock::QUERIABLE_FIELDS;
        foreach($requestedFields as $field)
        {
            if(!in_array($field, $allowed)) {
                throw new Exception("field $field is not allowed to be queried", 400);
            }
        }
    }

    private function validateAllowedFilterFields(array $filterFields): void
    {
        $allowed = StockQueryFilter::FILTERABLE_COLUMNS;
        foreach($filterFields as $filter => $value)
        {
            if(!in_array($filter, $allowed)) {
                throw new Exception("$filter is not allowed to be used for filtering", 400);
            }
        }
    }

    private function validateAllowedSorterFields(array $orderFields): void
    {
        $allowed = StockQuerySorter::SORTABLE_COLUMNS;
        foreach($orderFields as $field => $direction)
        {
            if(!in_array($field, $allowed)) {
                throw new Exception("$field is not allowed for sorting results", 400);
            }
        }
    }

    private function validateNonNegativePageAndPerPage(array $queryParams): void
    {
        $hasAnyNegativeValues = $queryParams['page'] < 0 || $queryParams['recordPerPage'] < 0;
        if ($hasAnyNegativeValues) {
            throw new Exception('page number and record per page should both be non-negative', 400);
        }
    }

    private function validateOrderBy(array &$queryParams): void
    {
        $orderBys = $queryParams['orderBy'];
        $allowedOrderByDirection = ['ASC', 'DESC'];

        foreach($orderBys as $orderByColumn => $orderDirection)
        {
            $isDirectionValid = in_array(strtoupper($orderDirection),$allowedOrderByDirection);
            if (!$isDirectionValid) {
                throw new Exception('only ASC or DESC allowed for order directions', 400);
            }

            $orderBys[$orderByColumn] = strtoupper($orderDirection);
        }

        $queryParams['orderBy'] = $orderBys;
    }

    private function validateArrayParams(array $queryParams) {
        $this->validateIsArray($queryParams['fields'], 'queryFields');
        $this->validateIsArray($queryParams['filters'], 'filters');
        $this->validateIsArray($queryParams['orderBy'], 'orderBy');
    }

    private function makeMissingQueryParams(): array
    {
        $queryParams = [];
        $queryParams['fields'] = $_GET['fields'] ?? [];
        $queryParams['filters'] = $_GET['filters'] ?? [];
        $queryParams['orderBy'] = $_GET['orderBy'] ?? [];
        $queryParams['page'] = $_GET['page'] ?? self::DEFAULT_PAGE_NUMBER;
        $queryParams['recordPerPage'] = $_GET['recordPerPage'] ?? self::DEFAULT_RECORD_PER_PAGE;

        return $queryParams;
    }

    private function validateIsArray($forValidating, $fieldName): void
    {
        if(!is_array($forValidating)) {
            throw new Exception("$fieldName should be in an array", 400);
        }
    }
}