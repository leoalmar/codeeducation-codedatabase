<?php

namespace Leoalmar\CodeDatabase;

use Leoalmar\CodeDatabase\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

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
        return $this->model->findOrFail($id,$columns);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field,$value)->get($columns);
    }
}