<?php

    namespace RatePAY\Exception;

    class ModelException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Model exception : " . $message);
        }

    }
