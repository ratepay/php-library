<?php

    namespace RatePAY\Exception;

    class RequestException extends ExceptionAbstract
    {

        /**
         * @param string $message
         */
        public function __construct($message)
        {
            parent::__construct("Request exception : " . $message);
        }

    }
