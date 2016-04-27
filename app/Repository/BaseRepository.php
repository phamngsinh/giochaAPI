<?php
/**
 * Created by PhpStorm.
 * User: smagic39
 * Date: 4/25/16
 * Time: 10:59 PM
 */

namespace App\Repository;


use Bosnadev\Repositories\Contracts\CriteriaInterface;
use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Repository
 * @package Bosnadev\Repositories\Eloquent
 */
abstract class BaseRepository implements RepositoryInterface, CriteriaInterface
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @param App $app
     * @param Collection $collection
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model();

    /**
     * @param array $columns
     * @param array $with Define relationships that we also get in this query
     * @return mixed
     */
    public function all($columns = array('*'), $with = array())
    {
        $this->applyCriteria();

        // Add this to get archived (soft deleted) records
        $archive = Input::get('archive', false);
        if ($archive === 'both') {
            $mod = $this->model->newQueryWithoutScopes()->where($this->model->table . '.id', '>', 0);
        } else if ($archive) {
            $mod = $this->model->newQueryWithoutScopes()->where('deleted_at', '>', 0)->where($this->model->table . '.id', '>', 0);
        } else {
            $mod = $this->model->where($this->model->table . '.id', '>', 0);
        }

        // Add relationship to the query
        if ($with) {
            foreach ($with as $relation) {
                $mod = $mod->with($relation);
            }
        }

        $keys = Input::get('keys', '');
        $keyword = strtolower(trim(Input::get('keyword', '')));
        if ($keyword != '') {
            $keys = explode(',', $keys);
            if ($keys)
                foreach ($keys AS $key) {
                    $mod = $this->model->where(trim($key), 'LIKE', "%%{$keyword}%%");
                }
        }

        $mod = $this->filters($mod, Input::get('filter', ''), $this->model);
        $mod = $this->relatedFilters($mod, Input::get('relatedFilter', ''), $this->model);
        $mod = $this->sorts($mod, Input::get('sort', ''), $this->model);
        $mod = $this->relatedSort($mod, Input::get('relatedSort', ''), $this->model);
        return $mod->get($columns);


    }

    /**
     * @param  string $value
     * @param  string $key
     * @return array
     */
    public function lists($value, $key = null)
    {
        $this->applyCriteria();
        $lists = $this->model->lists($value, $key);
        if (is_array($lists)) {
            return $lists;
        }
        return $lists->all();
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @param array $with Define relationships that we also get in this query
     * @return mixed
     */
    public function paginate($perPage = 25, $columns = array('*'), $with = array())
    {
        $this->applyCriteria();

        // Add this to get archived (soft deleted) records
        $archive = Input::get('archive', false);

        if ($archive === 'false') {
            $archive = false;
        }

        if ($archive === 'both') {
            $mod = $this->model->newQueryWithoutScopes()->where($this->model->table . '.id', '>', 0);
        } else if ($archive) {
            $mod = $this->model->newQueryWithoutScopes()->where('deleted_at', '>', 0)->where($this->model->table . '.id', '>', 0);
        } else {
            $mod = $this->model->where($this->model->table . '.id', '>', 0);
        }

        // Add relationship to the query
        if ($with) {
            foreach ($with as $relation) {
                $mod = $mod->with($relation);
            }
        }

        $keys = Input::get('keys', '');
        $keyword = strtolower(trim(Input::get('keyword', '')));
        if ($keyword != '') {
            $keys = explode(',', $keys);
            if ($keys)
                foreach ($keys AS $key) {
                    $mod = $this->model->where(trim($key), 'LIKE', "%%{$keyword}%%");
                }
        }

        if (Input::get('limit', 0) > 0) {
            $page = round(Input::get('start', 0) / Input::get('limit', 0));
            $page = ($page > 0 ? $page : 0) + 1;
            Input::merge(array('page' => $page));

            $mod = $this->filters($mod, Input::get('filter', ''), $this->model);
            $mod = $this->relatedFilters($mod, Input::get('relatedFilter', ''), $this->model);
            $mod = $this->relatedSort($mod, Input::get('relatedSort', ''), $this->model);
            $mod = $this->sorts($mod, Input::get('sort', ''), $this->model);

        }
        return $mod->paginate($perPage, $columns);
    }

    public function relatedFilters($mod, $filter = '', $model)
    {
        $rs = json_decode($filter);
        if (trim($filter) == '' || $rs == null) {
            return $mod;
        }

        foreach ($rs AS $el) {
            $table = isset($el->table) ? explode('.', $el->table) : null;
            if (isset($el->model) && $el->model != '') {

                if (count($el->model = explode('.', $el->model)) == 1) {
                    if (isset($el->func) && $el->func) {
                        if ($el->operator == 'btw') {
                            $values = explode('|', $el->value);
                            $mod->whereHas($el->model[0], function () {

                            }, '>=', $values[0]);
                            $mod->whereHas($el->model[0], function () {

                            }, '<=', $values[1]);
                        } else {
                            $mod->whereHas($el->model[0], function () {

                            }, $el->operator, $el->value);
                        }
                    } else {
                        $mod->whereHas($el->model[0], function ($query) use ($el, $model) {
                            if (!isset($el->table)) {
                                $el->table = rtrim($el->model[0], 's') . 's';
                            }
                            $this->doFilter($query, $el, $model);

                        });
                    }
                } else {

                    if (!empty($table)) {
                        $mod->whereHas($el->model[0], function ($query) use ($el, $model, $table) {
                            $query->whereHas($el->model[1], function ($query) use ($el, $model, $table) {
                                $el->table = $table[1];
                                $this->doFilter($query, $el, $model);
                            });
                        });
                    } else {
                        $mod->whereHas($el->model[0], function ($query) use ($el, $model) {
                            $query->whereHas($el->model[1], function ($query) use ($el, $model) {
                                $el->table = rtrim($el->model[1], 's') . 's';
                                $this->doFilter($query, $el, $model);
                            });
                        });
                    }

                }
            }
        }
        return $mod;
    }

    public function filters($query, $filter = '', $model)
    {

        $rs = json_decode($filter);
        if (trim($filter) == '' || $rs == null)
            return $query;
        if ($rs) {
            foreach ($rs AS $el) {
//                $this->doFilter($mod, $el, $model);
                $defDO = ['==' => '=', 'gt' => '>', 'ge' => '>=', 'lt' => '<', 'le' => '<=', 'eq' => '=', 'ne' => '!='];
                foreach ($defDO as $k => $v) {
                    if ($el->operator == $k) {
                        $query->where($el->property, $v, $el->value);
                    }
                }
                if (in_array($el->operator, $defDO)) {
                    $query->where($el->property, $defDO[$el->operator], $el->value);
                } elseif (strcasecmp($el->operator, 'in') == 0 && is_array($el->value)) {
                    $query->whereIn($el->property, $el->value);
                } elseif (strcasecmp($el->operator, 'notin') == 0 && is_array($el->value)) {
                    $query->whereNotIn($el->property, $el->value);
                } elseif (strcasecmp($el->operator, 'like') == 0) {
                    $query->where($el->property, $el->operator, "%%" . ($el->value) . "%%");
                }
            }
        }
        return $query;
    }

    private function doFilter($query, $el, $model)
    {
        $defDO = ['==' => '=', 'gt' => '>', 'ge' => '>=', 'lt' => '<', 'le' => '<=', 'eq' => '=', 'ne' => '!='];
        foreach ($defDO as $k => $v) {
            if ($el->operator == $k) {
                $query->where($el->table . '.' . $el->property, $v, $el->value);
            }
        }
        if (in_array($el->operator, $defDO)) {
            $query->where($el->table . '.' . $el->property, $defDO[$el->operator], $el->value);
        } elseif (strcasecmp($el->operator, 'in') == 0 && is_array($el->value)) {
            $query->whereIn($el->table . '.' . $el->property, $el->value);
        } elseif (strcasecmp($el->operator, 'notin') == 0 && is_array($el->value)) {
            $query->whereNotIn($el->table . '.' . $el->property, $el->value);
        } elseif (strcasecmp($el->operator, 'like') == 0) {
            $query->where($el->table . '.' . $el->property, $el->operator, "%%" . ($el->value) . "%%");
        } elseif (strcasecmp($el->operator, 'btw') == 0) {
            $values = explode('|', $el->value);
            $query->whereBetween($el->table . '.' . $el->property, array($values[0], $values[1]));
        }
    }

    public function sorts($mod, $sort = '', $table)
    {
        $tablename = rtrim($table->table, 's') . 's';
        $rs = json_decode($sort);
        if (trim($sort) == '' || $rs == null)
            return $mod;
        if ($rs)
            foreach ($rs AS $el) {
                $mod->orderBy($tablename . '.' . $el->property, strtoupper($el->direction));
            }

        return $mod;
    }

    public function relatedSort($mod, $conditions, $model)
    {
        $conditions = json_decode($conditions);
        if (!$conditions) {
            return $mod;
        }

        $softDeleteTables = config('database.soft_deletes');
        foreach ($conditions AS $condition) {

            if (isset($condition->model) && $condition->model != '') {
                if (count($condition->model = explode('.', $condition->model)) == 1) {
                    $fkPrefix = rtrim($model->table, 's');
                    if (isset($condition->func) && $condition->func) {
                        $mod->leftJoin($condition->model[0], $model->table . '.id', '=', $condition->model[0] . '.' . $fkPrefix . '_id');

                        $alias = (isset($condition->alias)) ? " AS $condition->alias" : '';
                        $mod->select($model->table . '.*', DB::raw($condition->func . '(' . $condition->model[0] . '.id)' . $alias));

                        // Add condition to remove archived / soft deleted records query results
                        if (in_array($condition->model[0], $softDeleteTables)) {
                            $mod->where($condition->model[0] . '.deleted_at', null);
                        }

                        $mod->groupBy($model->table . '.id');

                        $mod->orderBy(DB::raw($condition->func . '(' . $condition->model[0] . '.id)'), strtoupper($condition->direction));
                    } else {
                        if (!empty($condition->table)) {
                            $mod->leftJoin($condition->table, $model->table . '.' . $condition->model[0], '=', $condition->table . '.id');

                            $mod->groupBy($condition->table . '.id');

                            // Add condition to remove archived / soft deleted records query results
                            if (in_array($model->table, $softDeleteTables)) {
                                $mod->where($model->table . '.deleted_at', null);
                            }

                            $mod->orderBy(DB::raw($condition->table) . '.' . $condition->property, strtoupper($condition->direction));

                        } else {
                            $mod->leftJoin($condition->model[0], $model->table . '.' . rtrim($condition->model[0], 's') . '_id', '=', $condition->model[0] . '.id');

                            // Add condition to remove archived / soft deleted records query results
                            if (in_array($condition->model[0], $softDeleteTables)) {
                                $mod->where($condition->model[0] . '.deleted_at', null);
                            }

                            $mod->groupBy($model->table . '.id');

                            $mod->orderBy(DB::raw($condition->model[0]) . '.' . $condition->property, strtoupper($condition->direction));
                        }
                    }
                } else {
                    $mod->whereHas($condition->model[0], function ($query) use ($condition) {
                        $query->whereHas($condition->model[1], function ($query2) use ($condition) {
                            $query2->orderBy($condition->property, strtoupper($condition->direction));
                        });
                    });
                }
            }
        }


        return $mod;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * save a model without massive assignment
     *
     * @param array $data
     * @return bool
     */
    public function saveModel(array $data)
    {
        foreach ($data as $k => $v) {
            $this->model->$k = $v;
        }
        return $this->model->save();
    }

    /**
     * Join entity many to one
     *
     * @param Entity $relations
     * return mixed
     */
    public function with($relations)
    {
        $return = $this->model->newQueryWithoutScopes();
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        foreach ($relations as $relation) {
            $return = $return->with($relation);
        }

        return $return;
    }

    /**
     * Join entity one to many
     *
     * @param Entity $relations
     *
     * return mixed
     */
    public function has($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
    {
        return $this->model->has($relation, $operator, $count, $boolean, $callback);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param  array $data
     * @param  $id
     * @return mixed
     */
    public function updateRich(array $data, $id)
    {
        if (!($model = $this->model->find($id))) {
            return false;
        }

        return $model->fill($data)->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function deleteAll($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->newQueryWithoutScopes()->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findAllBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->get($columns);
    }

    /**
     * Find a collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $columns
     * @param bool $or
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $this->applyCriteria();

        $model = $this->model;

        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $model = (!$or) ? $model->where($value) : $model->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list($field, $operator, $search) = $value;
                    $model = (!$or) ? $model->where($field, $operator, $search) : $model->orWhere($field, $operator, $search);
                } elseif (count($value) === 2) {
                    list($field, $search) = $value;
                    $model = (!$or) ? $model->where($field, '=', $search) : $model->orWhere($field, '=', $search);
                }
            } else {
                $model = (!$or) ? $model->where($field, '=', $value) : $model->orWhere($field, '=', $value);
            }
        }
        return $model->get($columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model;
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true)
            return $this;

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria)
                $this->model = $criteria->apply($this->model, $this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function newQueryWithoutScopes()
    {
        return $this->model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function apiFindOrFail($id)
    {
        $model = $this->find($id);

        if (empty($model)) {
            throw new HttpException(1001, "Data  not found");
        }

        return $model;
    }
}
