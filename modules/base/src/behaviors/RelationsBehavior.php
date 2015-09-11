<?php

namespace im\base\behaviors;

use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\ModelEvent;
use yii\base\UnknownMethodException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\Command;
use yii\helpers\ArrayHelper;

/**
 * Class RelationsBehavior.
 *
 * @property ActiveRecord $owner
 * @package im\base\behaviors
 */
class RelationsBehavior extends Behavior
{

    /**
     * @var array
     */
    public $relations = [];

    public $settings = [];

    protected $relatedData = [];

    protected $relatedModels = [];

    protected $extraColumns = [];

    /**
     * @var array relations cache
     */
    private $_ownerRelations = [];

    /**
     * @var array primary keys cache
     */
    private $_pks = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave'
        ];
    }

    public function beforeInsert(ModelEvent $event)
    {
        $this->beforeSave();
        if (!$this->owner->getIsNewRecord()) {
            $event->isValid = false;
        }
    }

    public function beforeUpdate()
    {
        $this->beforeSave();
    }

    public function beforeSave()
    {
        foreach (array_keys($this->relatedData) as $name) {
            $relation = $this->getOwnerRelation($name);
            if (!$relation->multiple) {
                $this->saveOne($name);
                unset($this->relatedData[$name]);
            }
        }
    }

    /**
     * Handles afterSave event of the owner.
     */
    public function afterSave()
    {
        foreach (array_keys($this->relatedData) as $name) {
            $relation = $this->getOwnerRelation($name);
            if ($relation->multiple) {
                $this->saveMany($name);
                $this->owner->populateRelation($name, $this->relatedModels[$name]);
                unset($this->relatedData[$name]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (parent::canSetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasOwnerRelation($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (parent::canGetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasOwnerRelation($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try { parent::__set($name, $value); }
        catch (\Exception $e) {
            $this->setRelation($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try { return parent::__get($name); }
        catch (\Exception $e) {
            if ($this->isDataName($name)) {
                $name = $this->normalizeName($name);
                return $this->getRelationData($name);
            } else {
                return $this->getRelationModels($name);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (parent::hasMethod($name)) {
            return true;
        } else {
            if (strncmp($name, 'get', 3) === 0) {
                $name = substr($name, 3);
            }
            return $this->hasRelation($this->getRelationName($this->normalizeName($name)));
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (strncmp($name, 'get', 3) === 0) {
            $name = substr($name, 3);
        }
        if ($relation = $this->getRelation($this->getRelationName($this->normalizeName($name)))) {
            return $relation;
        }

        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * @param string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        if (strncmp($name, 'related ', 7) === 0) {
            $name = substr($name, 7);
        }
        if (substr_compare($name, 'Data', -4, 4) == 0) {
            $name = substr($name, 0, -4);
        }
        if (substr_compare($name, 'Relation', -8, 8) == 0) {
            $name = substr($name, 0, -8);
        }

        return lcfirst(trim($name));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getRelationName($name)
    {
        return $this->normalizeName($name) . 'Relation';
    }


    /**
     * @param string $name
     * @return bool
     */
    protected function isDataName($name)
    {
        return $this->normalizeName($name) . 'Data' == $name;
    }

    public function getRelationSetting($relation, $name, $default = null)
    {
        return isset($this->settings[$relation][$name]) ? $this->settings[$relation][$name] : $default;
    }

    /**
     * @param string $name
     * @return ActiveQuery
     */
    protected function getOwnerRelation($name)
    {
        if (!isset($this->_ownerRelations[$name])) {
            $this->_ownerRelations[$name] = $this->owner->getRelation($this->getRelationName($name));
        }

        return $this->_ownerRelations[$name];
    }

    /**
     * @param string $name
     * @return ActiveQuery
     */
    protected function getRelation($name)
    {
        $relation = $this->relations[$name];
        if ($relation instanceof \Closure) {
            $relation = call_user_func($relation);
        }

        return $relation;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasOwnerRelation($name)
    {
        return isset($this->_ownerRelations[$name]) || $this->owner->getRelation($this->getRelationName($name), false) !== null;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasRelation($name)
    {
        return isset($this->relations[$name]);
    }

    /**
     * @param string $modelClassName
     * @return mixed
     */
    protected function getPrimaryKey($modelClassName)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $modelClassName;
        if (!isset($this->_pks[$modelClassName])) {
            $this->_pks[$modelClassName] = $modelClass::primaryKey();
        }

        return $this->_pks[$modelClassName];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    protected function setRelation($name, $value)
    {
        if (!empty($value) || $value === null) {
            if ($this->owner->isRelationPopulated($name)) {
                unset($this->owner->$name);
                unset($this->relatedModels[$name]);
            }
            $relation = $this->getOwnerRelation($name);
            if ($relation->multiple) {
                $this->setMany($name, $value);
            } else {
                $this->setOne($name, $value);
            }
        }
    }

    protected function setOne($name, $value)
    {
        if ($value === null) {
            $this->relatedData[$name] = $this->relatedModels[$name] = $value;
        } else {
            if (is_array($value) && !ArrayHelper::isAssociative($value)) {
                $v = ArrayHelper::remove($value, 0);
                $this->extraColumns[$name] = $value;
                $value = $v;
            }
            if (is_array($value)) {
                if (ArrayHelper::isAssociative($value)) {
                    $this->relatedData[$name] = $value;
                } else {
                    $this->relatedData[$name] = $value[0];
                }
            } else {
                $this->relatedData[$name] = $value;
                $relation = $this->getOwnerRelation($name);
                if ($this->isModel($relation, $value)) {
                    $this->owner->populateRelation($name, $value);
                }
            }
        }
    }

    protected function setMany($name, $value)
    {
        $this->relatedModels[$name] = [];
        unset($this->extraColumns[$name]);
        if ($value === null) {
            $this->relatedData[$name] = $value;
        } else {
            $this->relatedData[$name] = [];
            foreach ($value as $key => $item) {
                if (is_array($item) && !ArrayHelper::isAssociative($item)) {
                    $this->relatedData[$name][$key] = ArrayHelper::remove($item, 0);
                    $this->extraColumns[$name][$key] = $item;
                } else {
                    $this->relatedData[$name][$key] = $item;
                }
            }
            if ($this->isLoaded($name)) {
                $this->relatedModels[$name] = $this->relatedData[$name];
                $this->owner->populateRelation($name, $this->relatedData[$name]);
            }
        }
    }

    protected function getRelationData($name)
    {
        return $this->relatedData[$name];
    }

    protected function getRelationModels($name)
    {
        $this->loadRelation($name);
        if (array_key_exists($name, $this->relatedModels)) {
            return $this->relatedModels[$name];
        } else {
            return $this->getOwnerRelation($name);
        }
    }

    protected function isLoaded($name)
    {
        $loaded = true;
        if ($this->relatedData[$name] !== null) {
            $relation = $this->getOwnerRelation($name);
            $modelClass = $relation->modelClass;
            if ($relation->multiple) {
                foreach ($this->relatedData[$name] as $item) {
                    if (!$item instanceof $modelClass && $item !== null) {
                        $loaded = false;
                        break;
                    }
                }
            } else {
                $loaded = $this->relatedData[$name] instanceof $modelClass;
            }
        }

        return $loaded;
    }

    protected function loadRelation($name)
    {
        if (isset($this->relatedData[$name])) {
            $relation = $this->getOwnerRelation($name);
            if ($relation->multiple) {
                $this->loadMany($name);
            } else {
                $this->loadOne($name);
            }
            if (!empty($this->relatedModels[$name])) {
                $this->owner->populateRelation($name, $this->relatedModels[$name]);
            }
        }
    }

    /**
     * @param $name
     */
    protected function loadOne($name)
    {
        if ($this->relatedData[$name] === null) {
            $this->relatedModels[$name] = null;
        } else {
            $relation = $this->getOwnerRelation($name);
            if ($this->isModel($relation, $this->relatedData[$name])) {
                // Load instantiated model
                $this->relatedModels[$name] = $this->relatedData[$name];
            } elseif ($this->isPrimaryKey($relation, $this->relatedData[$name])) {
                // Load model by primary key
                $pk = $this->getNotLoadedItems($name)[0][0];
                $model = $this->findByPrimaryKeys($name, $pk)->one();
                if ($model !== null) {
                    $this->relatedModels[$name] = $model;
                }
            } else {
                // Create model from data array
                $data = $this->getNotLoadedItems($name)[1];
                if ($data) {
                    $this->relatedModels[$name] = $this->createModel($relation->modelClass, $data[0]);
                }
            }
        }
    }

    /**
     * @param $name
     */
    protected function loadMany($name)
    {
        if ($this->relatedData[$name] === null) {
            $this->relatedModels[$name] = [];
        } else {
            $relation = $this->getOwnerRelation($name);
            list($pks, $data) = $this->getNotLoadedItems($name);
            // Load models by primary keys
            if ($pks) {
                $models = $this->findByPrimaryKeys($name, $pks)->all();
                if ($models) {
                    $pk = $this->getPrimaryKey($relation->modelClass);
                    foreach ($models as $model) {
                        $index = $this->findPrimaryKey($pks, $model->primaryKey, $pk);
                        $this->relatedModels[$name][$index] = $model;
                    }
                }
            }
            // Create model from data arrays
            if ($data) {
                foreach ($data as $index => $item) {
                    $this->relatedModels[$name][$index] = $this->createModel($relation->modelClass, $item);
                }
            }
            // Load instantiated models
            foreach ($this->relatedData[$name] as $index => $model) {
                if ($this->isModel($relation, $model)) {
                    $this->relatedModels[$name][$index] = $model;
                }
            }
            if (!empty($this->relatedModels[$name])) {
                ksort($this->relatedModels[$name]);
            }
        }
    }

    protected function saveOne($name)
    {
        if ($this->relatedData[$name] === null) {
            $this->unlinkOne($name, $this->getRelationSetting($name, 'deleteOnUnlink', false));
        } else {
            $relation = $this->getOwnerRelation($name);
            if ($this->isModel($relation, $this->relatedData[$name])) {
                $model = $this->relatedData[$name];
            } elseif ($this->isLoaded($name)) {
                $model = $this->relatedModels[$name];
            } else {
                $data = $this->getNotLoadedItems($name)[1];
                if ($data) {
                    $model = $this->createModel($relation->modelClass, $data[0]);
                }
            }
            if (!empty($model) && $model->getIsNewRecord() && !$model->save()) {
                return;
            }
            if (!empty($model) || $this->isPrimaryKey($relation, $this->relatedData[$name])) {
                foreach ($relation->link as $pk => $fk) {
                    if (!empty($model)) {
                        $value = $model->$pk;
                    } elseif (is_array($this->relatedData[$name])) {
                        $value = empty($this->relatedData[$name][$pk]) ? null : $this->relatedData[$name][$pk];
                    } else {
                        $value = $this->relatedData[$name];
                    }
                    if ($value !== null) {
                        if (is_array($this->owner->$fk)) {
                            $this->owner->$fk = array_merge($this->owner->$fk, [$value]);
                        } else {
                            $this->owner->$fk = $value;
                        }
                    }
                }
                $extraColumns = !empty($this->extraColumns[$name]) ? $this->extraColumns[$name] : [];
                $extraColumns = array_merge($this->getRelationSetting($name, 'extraColumns', []), $extraColumns);
                if ($extraColumns) {
                    foreach ($extraColumns as $name => $value) {
                        $this->owner->$name = $value;
                    }
                }
            }
        }
    }

    protected function saveMany($name)
    {
        if (!$this->isLoaded($name)) {
            $this->loadRelation($name);
        }
        $relation = $this->getOwnerRelation($name);
        if ($relation->via !== null) {
            $except = [];
            $delete = !is_array($relation->via) ? true : $this->getRelationSetting($name, 'deleteOnUnlink', false);
        } else {
            $except = $this->relatedModels[$name];
            $delete = $this->getRelationSetting($name, 'deleteOnUnlink', false);
        }
        $this->unlinkMany($name, $except, $delete);
        foreach ($this->relatedModels[$name] as $key => $model) {
            /** @var ActiveRecord $model */
            $extraColumns = !empty($this->extraColumns[$name][$key]) ? $this->extraColumns[$name][$key] : [];
            $extraColumns = array_merge($this->getRelationSetting($name, 'extraColumns', []), $extraColumns);
            if ($relation->via !== null) {
                if ($model->getIsNewRecord() && !$model->save() && $model->getIsNewRecord()) {
                    unset($this->relatedModels[$name][$key], $this->relatedData[$name][$key]);
                    continue;
                }
                $this->link($name, $model, $extraColumns);
            } else {
                foreach ($extraColumns as $attr => $value) {
                    $model->$attr = $value;
                }
                $this->link($name, $model, $extraColumns);
                if(!$model->save()) {
                    unset($this->relatedModels[$name][$key], $this->relatedData[$name][$key]);
                }
            }
        }
    }

    protected function createModel($class, $data)
    {
        /** @var ActiveRecord $model */
        $model = new $class;
        $model->load($data, '');
        return $model;
    }

    /**
     * @param $name
     * @param $pks
     * @return ActiveQuery
     */
    protected function findByPrimaryKeys($name, $pks)
    {
        $relation = $this->getOwnerRelation($name);
        /* @var $modelClass ActiveRecord */
        $modelClass = $relation->modelClass;
        $query = $modelClass::find();
        $condition = ['in', $this->getPrimaryKey($modelClass), $pks];
        $query->andWhere($condition);
        return $query;
    }

    protected function getNotLoadedItems($name)
    {
        $pks = [];
        $data = [];
        if ($this->relatedData[$name] !== null) {
            $relation = $this->getOwnerRelation($name);
            $relatedData = $relation->multiple ? $this->relatedData[$name] : [$this->relatedData[$name]];
            foreach ($relatedData as $key => $item) {
                if ($this->isPrimaryKey($relation, $item)) {
                    $pks[$key] = $item;
                } elseif (!$this->isModel($relation, $item)) {
                    $data[$key] = $item;
                }
            }
        }

        return [$pks, $data];
    }

    /**
     * @param ActiveQuery $relation
     * @param $key
     * @return bool
     */
    protected function isPrimaryKey($relation, $key)
    {
        $primaryKey = $this->getPrimaryKey($relation->modelClass);
        return (is_scalar($key) && count($primaryKey) === 1) || (is_array($key) && array_keys($key) == $primaryKey);
    }

    protected function isModel($relation, $model)
    {
        $modelClass = $relation->modelClass;
        return $model instanceof $modelClass;
    }

    protected function findPrimaryKey($values, $value, $pk)
    {
        foreach ($values as $key => $item) {
            if ($item == $value) {
                return $key;
            } else if (count($pk) === 1 && is_array($item) && !empty($item[$pk[0]]) && $item[$pk[0]] == $value) {
                return $key;
            }
        }
        return false;
    }

    /**
     * @param string $name relation name
     * @param ActiveRecord $model
     * @param array $extraColumns
     * @throws \yii\db\Exception
     */
    public function link($name, $model, $extraColumns = [])
    {
        $relation = $this->getOwnerRelation($name);

        if ($relation->via !== null) {
            if ($this->owner->getIsNewRecord() || $model->getIsNewRecord()) {
                throw new InvalidCallException('Unable to link models: both models must NOT be newly created.');
            }
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                // unset $viaName so that it can be reloaded to reflect the change
//                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $columns = [];
            foreach ($viaRelation->link as $a => $b) {
                $columns[$a] = $this->owner->$b;
            }
            foreach ($relation->link as $a => $b) {
                $columns[$b] = $model->$a;
            }
            foreach ($extraColumns as $k => $v) {
                $columns[$k] = $v;
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecordInterface */
                /* @var $record ActiveRecordInterface */
                $record = new $viaClass();
                foreach ($columns as $column => $value) {
                    $record->$column = $value;
                }
                $record->insert(false);
            } else {
                /* @var $viaTable string */
                ActiveRecord::getDb()->createCommand()
                    ->insert($viaTable, $columns)->execute();
            }
        } else {
            $p1 = $model->isPrimaryKey(array_keys($relation->link));
            $p2 = $this->owner->isPrimaryKey(array_values($relation->link));
            if ($p1 && $p2) {
                if ($this->owner->getIsNewRecord() && $model->getIsNewRecord()) {
                    throw new InvalidCallException('Unable to link models: both models are newly created.');
                } elseif ($this->owner->getIsNewRecord()) {
                    $this->bindModels(array_flip($relation->link), $this->owner, $model);
                } else {
                    $this->bindModels($relation->link, $model, $this->owner);
                }
            } elseif ($p1) {
                $this->bindModels(array_flip($relation->link), $this->owner, $model);
            } elseif ($p2) {
                $this->bindModels($relation->link, $model, $this->owner);
            } else {
                throw new InvalidCallException('Unable to link models: the link does not involve any primary key.');
            }
        }

//        // update lazily loaded related objects
//        if (!$relation->multiple) {
//            $this->_related[$name] = $model;
//        } elseif (isset($this->_related[$name])) {
//            if ($relation->indexBy !== null) {
//                $indexBy = $relation->indexBy;
//                $this->_related[$name][$model->$indexBy] = $model;
//            } else {
//                $this->_related[$name][] = $model;
//            }
//        }
    }

    protected function unlinkMany($name, $except = [], $delete = false)
    {
        $relation = $this->getOwnerRelation($name);

        if ($relation->via !== null) {
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
//                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $condition = [];
            $nulls = [];
            foreach ($viaRelation->link as $a => $b) {
                $nulls[$a] = null;
                $condition[$a] = $this->owner->$b;
            }
            if (!empty($viaRelation->where)) {
                $condition = ['and', $condition, $viaRelation->where];
            }
            if ($except) {
                $values = $this->getLinkValues($except, array_flip($relation->link));
                $condition = ['and', $condition, ['not in', array_values($relation->link), array_unique($values, SORT_REGULAR)]];
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecordInterface */
                if ($delete) {
                    $viaClass::deleteAll($condition);
                } else {
                    $viaClass::updateAll($nulls, $condition);
                }
            } else {
                /* @var $viaTable string */
                /* @var $command Command */
                $command = ActiveRecord::getDb()->createCommand();
                if ($delete) {
                    $command->delete($viaTable, $condition)->execute();
                } else {
                    $command->update($viaTable, $nulls, $condition)->execute();
                }
            }
        } else {
            /* @var $relatedModel ActiveRecordInterface */
            $relatedModel = $relation->modelClass;
            if (!$delete && count($relation->link) == 1 && is_array($this->owner{$b = reset($relation->link)})) {
                // relation via array valued attribute
                $this->owner->$b = [];
//                $this->owner->save(false);
            } else {
                $nulls = [];
                $condition = [];
                foreach ($relation->link as $a => $b) {
                    $nulls[$a] = null;
                    $condition[$a] = $this->owner->$b;
                }
                if (!empty($relation->where)) {
                    $condition = ['and', $condition, $relation->where];
                }
                if ($except) {
                    $values = $this->getLinkValues($except, $relation->link);
                    if ($values) {
                        $condition = ['and', $condition, ['not in', array_values($relation->link), array_unique($values, SORT_REGULAR)]];
                    }
                }
                if ($delete) {
                    $relatedModel::deleteAll($condition);
                } else {
                    $relatedModel::updateAll($nulls, $condition);
                }
            }
        }

//        unset($this->_related[$name]);
    }

    protected function unlinkOne($name, $delete = false)
    {
        $relation = $this->getOwnerRelation($name);
        /* @var $relatedModel ActiveRecordInterface */
        $relatedModel = $relation->modelClass;
        $condition = [];
        foreach ($relation->link as $a => $b) {
            $condition[$a] = $this->owner->$b;
            $this->owner->$b = null;
        }
        if ($delete) {
            $relatedModel::deleteAll($condition);
        }
    }

    /**
     * @param array $link
     * @param ActiveRecordInterface $foreignModel
     * @param ActiveRecordInterface $primaryModel
     * @throws InvalidCallException
     */
    private function bindModels($link, $foreignModel, $primaryModel)
    {
        foreach ($link as $fk => $pk) {
            $value = $primaryModel->$pk;
            if ($value === null) {
                throw new InvalidCallException('Unable to link models: the primary key of ' . get_class($primaryModel) . ' is null.');
            }
            if (is_array($foreignModel->$fk)) { // relation via array valued attribute
                $foreignModel->$fk = array_merge($foreignModel->$fk, [$value]);
            } else {
                $foreignModel->$fk = $value;
            }
        }
    }

    protected function getLinkValues($models, $link)
    {
        $attributes = array_values($link);
        $values = [];
        if (count($attributes) === 1) {
            $attribute = reset($link);
            foreach ($models as $model) {
                if (($value = $model[$attribute]) !== null) {
                    if (is_array($value)) {
                        $values = array_merge($values, $value);
                    } else {
                        $values[] = $value;
                    }
                }
            }
        } else {
            foreach ($models as $model) {
                $value = [];
                foreach ($link as $a => $b) {
                    $v = $model[$a];
                    if ($v !== null) {
                        $value[$b] = $model[$a];
                    }
                }
                if ($value) {
                    $values[] = $value;
                }
            }
        }
        return $values;
    }
}