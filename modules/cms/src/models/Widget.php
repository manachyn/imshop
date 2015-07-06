<?php

namespace im\cms\models;

use ReflectionClass;
use yii\base\InvalidParamException;
use yii\base\View;
use yii\db\ActiveRecord;
use Yii;

/**
 * Base class for all page widgets.
 *
 * @property integer $id
 * @property string $type
 *
 * @property Page[] $pages
 * @property WidgetArea[] $widgetAreas
 */
abstract class Widget extends ActiveRecord
{
    const TYPE = 'default';

    public $sort;

    public $widgetArea;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$app->layoutManager->getWidgetInstance($row['widget_type']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widgets}}';
    }

    /**
     * Widget title
     * @return string
     */
    abstract public function getCMSTitle();

    /**
     * Widget description
     * @return string
     */
    abstract public function getCMSDescription();

    /**
     * The name of the widget edit view
     * This should be in the format of 'path/to/view'.
     * @return string
     */
    abstract public function getEditView();

    /**
     * @return string widget type
     */
    public static function getType()
    {
        return static::TYPE;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetAreaItems()
    {
        return $this->hasMany(WidgetAreaItem::className(), ['widget_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->widget_type = self::getType();
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->layoutManager->invalidateWidgetAreasCache();
    }

    private $_view;

    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return \yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = Yii::$app->getView();
        }

        return $this->_view;
    }

    /**
     * Sets the view object to be used by this widget.
     * @param View $view the view object that can be used to render views or view files.
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
    }

    /**
     * Renders a view.
     * The view to be rendered can be specified in one of the following formats:
     *
     * - path alias (e.g. "@app/views/site/index");
     * - absolute path within application (e.g. "//site/index"): the view name starts with double slashes.
     *   The actual view file will be looked for under the [[Application::viewPath|view path]] of the application.
     * - absolute path within module (e.g. "/site/index"): the view name starts with a single slash.
     *   The actual view file will be looked for under the [[Module::viewPath|view path]] of the currently
     *   active module.
     * - relative path (e.g. "index"): the actual view file will be looked for under [[viewPath]].
     *
     * If the view name does not contain a file extension, it will use the default one `.php`.
     *
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string the rendering result.
     * @throws InvalidParamException if the view file does not exist.
     */
    public function render($view, $params = [])
    {
        return $this->getView()->render($view, $params, $this);
    }

    /**
     * Renders a view file.
     * @param string $file the view file to be rendered. This can be either a file path or a path alias.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string the rendering result.
     * @throws InvalidParamException if the view file does not exist.
     */
    public function renderFile($file, $params = [])
    {
        return $this->getView()->renderFile($file, $params, $this);
    }

    /**
     * Returns the directory containing the view files for this widget.
     * The default implementation returns the 'views' subdirectory under the directory containing the widget class file.
     * @return string the directory containing the view files for this widget.
     */
    public function getViewPath()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views';
    }
} 