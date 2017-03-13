<?php

    namespace RatePAY\Exception;

    class RuleSetException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Rule set exception : " . $message);
        }

    }
