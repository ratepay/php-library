<?php

namespace RatePAY\Model\Request\SubModel\Content;

use RatePAY\Model\Request\SubModel\AbstractModel;

class Additional extends AbstractModel
{

    /**
     * List of admitted fields.
     * Each field is public accessible by certain getter and setter.
     * E.g:
     * Set additional value by using setAdditional_01(var). Get additional by using getAdditional_01(). (Please consider the camel case)
     *
     * @var array
     */
    public $admittedFields = [
        'Additional_01' => [],
        'Additional_02' => [],
        'Additional_03' => [],
        'Additional_04' => [],
        'Additional_05' => [],
        'Additional_06' => [],
        'Additional_07' => [],
        'Additional_08' => [],
        'Additional_09' => [],
        'Additional_10' => [],
    ];

}
