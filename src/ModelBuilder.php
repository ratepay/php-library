<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY;

use RatePAY\Exception\ModelException;
use RatePAY\Service\ModelMapper;

class ModelBuilder
{
    /**
     * Current model object.
     *
     * @var mixed
     */
    private $model;

    /**
     * ModelBuilder constructor. Loads requested model.
     *
     * @param string|null $modelName
     *
     * @throws ModelException
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
            $this->setModel(ModelMapper::getFullPathRequestSubModel('Head'));
        }
    }

    /**
     * Catches all calls.
     * If called as setter, call magic setter of current model object
     * If called as model name, start and return new ModelBuilder instance with requested model.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed|ModelBuilder
     *
     * @throws ModelException
     */
    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);
        $field = false;
        $requestedModel = false;

        if ($prefix == 'set' || $prefix == 'get') {
            $field = substr($name, 3);
        } else {
            $requestedModel = $name;
        }

        if ($field) {
            if (!key_exists($field, $this->model->admittedFields)) {
                throw new ModelException("Field '" . $field . "' invalid");
            }

            if ($prefix == 'set') {
                $this->model->commonSetter($field, $arguments);

                return $this->model;
            } else {
                return $this->model->commonGetter($field);
            }
        } else {
            return new ModelBuilder($requestedModel);
        }
    }

    /**
     * Returns the current model object.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets model as current model (expects full namespaced model path).
     *
     * @param $model
     */
    private function setModel($model)
    {
        $this->model = new $model();
    }

    /**
     * Sets and fills model instance by array.
     *
     * @param $array
     *
     * @throws ModelException
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
     * Converts JSON input to array and uses setArray method.
     *
     * @param $json
     *
     * @throws ModelException
     */
    public function setJson($json)
    {
        $this->setArray(json_decode($json, true));
    }
}
