<?php
namespace RatePAY;

use RatePAY\Service\ModelMapper;
use RatePAY\Exception\ModelException;

class ModelBuilder
{

    /**
     * Current model object
     *
     * @var mixed
     */
    private $model;

    /**
     * ModelBuilder constructor. Loads requested model.
     *
     * @param null|string $modelName
     */
    public function __construct($modelName = null)
    {
        if (!is_null($modelName)) {
            $modelWithPath = ModelMapper::getFullPathRequestSubModel(ucfirst($modelName));

            if (class_exists($modelWithPath)) {
                $this->setModel($modelWithPath);
            } else {
                throw new ModelException("Model '" . $modelName . "' invalid");
            }
        } else { // If no model is set initialize with Head model
            $this->setModel(ModelMapper::getFullPathRequestSubModel("Head"));
        }
    }

    /**
     * Catches all calls.
     * If called as setter, call magic setter of current model object
     * If called as model name, start and return new ModelBuilder instance with requested model
     *
     * @param $name
     * @param $arguments
     * @return mixed|ModelBuilder
     */
    public function __call($name, $arguments)
    {
        $fieldToSet = false;
        $requestedModel = false;

        if (substr($name, 0, 3) == 'set') {
            $fieldToSet = substr($name, 3);
        } else {
            $requestedModel = $name;
        }

        if ($fieldToSet) {
            if (!key_exists($fieldToSet, $this->model->admittedFields)) {
                throw new ModelException("Field '" . $fieldToSet . "' invalid");
            }

            $this->model->commonSetter($fieldToSet, $arguments);

            return $this->model;
        } else {
            return new ModelBuilder($requestedModel);
        }
    }

    /**
     * Returns the current model object
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets model as current model (expects full namespaced model path)
     *
     * @param $model
     */
    private function setModel($model)
    {
        $this->model = new $model;
    }

    /**
     * Sets and fills model instance by array
     *
     * @param $array
     */
    public function setArray($array)
    {
        foreach ($array as $field => $value) {
            if (!is_array($value)) {
                $this->model->commonSetter($field, [0 => $value]);
            } else {
                if (is_numeric($field)) {
                    $field = key($value);
                    $value = current($value);
                }
                $mb = new ModelBuilder($field);
                $mb->setArray($value);
                $this->model->commonSetter($field, [0 => $mb->getModel()]);
            }
        }
    }

    /**
     * Converts JSON input to array and uses setArray method
     *
     * @param $json
     */
    public function setJson($json) {
        $this->setArray(json_decode($json, true));
    }

}