<?php

namespace RatePAY\Tests\Mocks;

use RatePAY\Model\Request\SubModel\AbstractModel;

class SimpleRequestModel extends AbstractModel
{
    public $admittedFields = [
        'FieldDefault' => [
            'mandatory' => false,
        ],
        'FieldRequired' => [
            'mandatory' => true,
        ],
        'FieldMultiple' => [
            'mandatory' => false,
            'multiple' => true
        ]
    ];

    public function __construct($admittedFields = null)
    {
        $this->admittedFields = $admittedFields ?: $this->admittedFields;

        parent::__construct();
    }

}
