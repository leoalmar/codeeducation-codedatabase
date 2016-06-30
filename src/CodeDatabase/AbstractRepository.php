<?php

namespace Leoalmar\CodeDatabase;

use Leoalmar\CodeDatabase\Contracts\CriteriaCollectionInterface;
use Leoalmar\CodeDatabase\Contracts\CriteriaInterface;
use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface, CriteriaCollectionInterface
{
    protected $model;

    protected $criteriaCollection = [];

    public function __construct()
    {
        $this->makeModel();
    }

    public abstract function model();

    public function makeModel()
    {
        $class = $this->model();
        $this->model = new $class;
        return $this->model;
    }

    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->findOrFail($id,$columns);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field,$value)->get($columns);
    }

    public function addCriteria(CriteriaInterface $criteria)
    {
        $this->criteriaCollection[] = $criteria;
        return $this;
    }

    public function getCriteriaCollection()
    {
        return $this->criteriaCollection;
    }

    public function getByCriteria(CriteriaInterface $criteria)
    {
        $this->model = $criteria->apply($this->model,$this);
        return $this;
    }

    public function applyCriteria()
    {
        foreach ($this->getCriteriaCollection() as $criteria) {
            $this->model = $criteria->apply($this->model,$this);
        }
        return $this;
    }
}