<?php

namespace AppVentus\Awesome\ShortcutsBundle\Repository;

/**
 * Base Entity Repository trait
 *
 * This trait was written to give some cool actions to use in a repo.
 */
trait AwesomeRepositoryTrait
{
    private $qb;

    /**
     * Get query builder instance
     * @param string $alias The entity alias
     *
     * @return QueryBuilder The active or default query builder
     */
    public function getInstance($alias)
    {
        return $this->qb ? $this->qb : $this->createQueryBuilder($alias);
    }

    /**
     * Set query builder instance
     * @param QueryBuilder $qb The queryBuilder
     *
     * @return BaseEntityRepository This repository
     */
    public function setInstance(QueryBuilder $qb)
    {
        $this->qb = $qb;

        return $this;
    }

    /**
     * Clears the current QueryBuilder instance
     * @return BaseEntityRepository This repository
     */
    public function clearInstance()
    {
        $this->qb = null;

        return $this;
    }

    /**
     * Run active query
     * @param method        $method        The method to run
     * @param hydrationMode $hydrationMode How the results will be (Object ? Array )
     *
     * @return array()
     */
    public function run($method = 'getResult', $hydrationMode = Query::HYDRATE_OBJECT)
    {
        return $this->getInstance()->getQuery()->$method($hydrationMode);
    }
}
