<?php

    namespace RatePAY\Exception;

    class FrontendException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Frontend exception : " . $message);
        }

    }