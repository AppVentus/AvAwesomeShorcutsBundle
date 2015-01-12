<?php

namespace AppVentus\Awesome\ShortcutsBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Base Entity Repository trait
 *
 * This trait was written to give some cool actions to use in a repo.
 */
trait AwesomeRepositoryTrait
{
    private $qb;
    private $mainAlias;

    /**
     * Get query builder instance
     * @param string $alias The entity alias
     *
     * @return QueryBuilder The active or default query builder
     */
    public function getInstance($alias = null)
    {
        if (!$alias && !$this->mainAlias) {
            $namespace= explode("\\", $this->_entityName);
            $alias = strtolower(end($namespace));
            $this->mainAlias = $alias;
        }elseif ($alias){
            $this->mainAlias = $alias;
        }

        return $this->qb ? $this->qb : $this->qb = $this->createQueryBuilder($this->mainAlias);
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
     * @param boolean       $autoClear     AutoClear means reset active instance
     *
     * @return array()
     */
    public function run($method = 'getResult', $hydrationMode = Query::HYDRATE_OBJECT, $autoClear = true)
    {
        $results = $this->qb->getQuery()->$method($hydrationMode);
        if ($autoClear) {
            $this->clearInstance();
        }

        return $results;
    }
}
