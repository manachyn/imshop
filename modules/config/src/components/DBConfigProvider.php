<?php

namespace im\config\components;

use yii\base\Component;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class DBConfigProvider
 * @package im\config\components
 */
class DBConfigProvider extends Component implements ConfigProviderInterface
{
    /**
     * @var string
     */
    public $tableName = '{{%config}}';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function get($key, $context = null, $default = null)
    {
        $value = $this->load($key, $context);
        $value = ArrayHelper::map($value, 'key', 'value');
        if (count($value) == 1 && substr($key, -1) != '*') {
            $value = $value[$key];
        }

        return $value ?: $default;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $context = null)
    {
        if ($this->has($key, $context)) {
            Yii::$app->db->createCommand()->update(
                $this->tableName,
                ['value' => $value],
                ['key' => $key, 'context' => $context]
            )->execute();
        } else {
            Yii::$app->db->createCommand()->insert($this->tableName, [
                'key' => $key,
                'value' => $value,
                'context' => $context
            ])->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($key, $context = null)
    {
        return (new Query())->from($this->tableName)->where(['key' => $key, 'context' => $context])->count();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key, $context = null)
    {
        $value = $this->get($key);
        $where = ['key' => $key];
        if ($context) {
            $where['context'] = $context;
        }
        Yii::$app->db->createCommand()->delete($this->tableName, $where)->execute();

        return $value;
    }

    /**
     * @param string $key
     * @return string|array
     */
    protected function load($key, $context = null)
    {
        $query = (new Query())->select(['key', 'value', 'context'])->from($this->tableName);
        if (substr($key, -1) == '*') {
            $where = ['like', 'key', substr($key, 0, -1)];
            if ($context) {
                $where = ['and', $where, ['or', ['context' => $context], ['context' => null]]];
            } else {
                $where = ['and', $where, ['context' => $context]];
            }
        } else {
            if ($context) {
                $where = ['and', ['key' => $key], ['or', ['context' => $context], ['context' => null]]];
            } else {
                $where = ['key' => $key, 'context' => $context];
            }
        }
        $result = $query->where($where)->all();
        if ($result && $context) {
            $resultByContext = [];
            foreach ($result as $item) {
                $resultByContext[$item['context'] ?: '__noContext__'][$item['key']] = $item;
            }
            if (isset($resultByContext[$context])) {
                $result = $resultByContext[$context];
                if (isset($resultByContext['__noContext__'])) {
                    foreach ($resultByContext['__noContext__'] as $item) {
                        if (!isset($result[$item['key']])) {
                            $result[$item['key']] = $item;
                        }
                    }
                }
            } else {
                $result = $resultByContext['__noContext__'];
            }
        }

        return $result;
    }
}

