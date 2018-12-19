<?php

namespace App\JsonApi;


use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\SortParameterInterface;

/**
 * Class BaseAdapter
 *
 * @package App\Api\JsonApi\v1
 */
abstract class DefaultAdapter extends AbstractAdapter
{
    /**
     * Model class
     */
    const MODEL = null;

    /**
     * @var array
     */
    protected $defaultPagination = null;

    /**
     * BaseAdapter constructor.
     *
     * @param StandardStrategy $paging
     *
     * @throws \Exception
     */
    public function __construct(StandardStrategy $paging)
    {
        if (!defined('static::MODEL')) {
            throw new Exception('Either you implement your own constructor -or- define the `MODEL` constant');
        }

        $modelClass = static::MODEL;

        return parent::__construct(new $modelClass, $paging);
    }

    /**
     * Apply the supplied filters to the builder instance.
     *
     * @param Builder    $query
     * @param Collection $filters
     *
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        // TODO: Implement filter() method.
    }

    /**
     * Sorting by related columns
     *
     * @param Builder                $query
     * @param SortParameterInterface $param
     */
    protected function sortBy($query, SortParameterInterface $param)
    {
        $column = $this->getQualifiedSortColumn($query, $param->getField());
        $order = $param->isAscending() ? 'asc' : 'desc';
        if (!starts_with(
            $column, $query->getModel()
                           ->getTable())) {
            // related sorting
            $relation = substr($column, 0, strpos($column, '.'));
            $attribute = substr($column, strlen($relation) + 1);
            $relation = $query->getModel()
                              ->$relation();
            $relatedTable = $relation->getRelated()
                                     ->getTable();
            // Include related table
            $query->selectRaw(
                $query->getModel()
                      ->getTable() . ".*")
                  ->leftJoin(
                      $relatedTable,
                      $query->getModel()
                            ->getTable() . '.' . $relation->getForeignKey(),
                      '=',
                      $relatedTable . '.' . $relation->getOwnerKey()
                  );
            // correct column name to match model table
            $column = $relatedTable . '.' . $attribute;
        }
        $query->orderBy($column, $order);
    }
}
