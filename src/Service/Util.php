<?php

    namespace RatePAY\Service;

    use RatePAY\Model\Request\SubModel\Constants;

    class Util
    {

        /**
         * Changes value to negative if necessary
         *
         * @param $string
         * @return string
         */
        public static function changeValueToNegative($value)
        {
            $value = floatval($value);
            return ($value > 0) ? $value * -1 : $value;
        }

        /**
         * Changes the camel case notation to separation by underscore
         *
         * @param $string
         * @return string
         */
        public static function changeCamelCaseToUnderscore($string)
        {
            return self::changeCase($string, '_', 'upper');
        }

        /**
         * Changes the camel case notation to separation by dash
         *
         * @param $string
         * @return string
         */
        public static function changeCamelCaseToDash($string)
        {
            return self::changeCase($string, '-', 'lower');
        }

        /**
         * Changes the case by definition
         *
         * @param $string
         * @param $delimiter
         * @param string $case
         * @return string
         */
        private static function changeCase($string, $delimiter, $case = '')
        {
            $stringFormatted = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);
            $stringFormatted = implode($delimiter, $stringFormatted);
            if ($case == 'upper') {
                $stringFormatted = strtoupper($stringFormatted);
            } elseif ($case == 'lower') {
                $stringFormatted = strtolower($stringFormatted);
            }

            return $stringFormatted;
        }

        /**
         * Replaces entered string by defined identifiers
         *
         * @param string $template
         * @param array $dataSet
         * @param string|null $key
         * @param string|null $value
         * @return string
         */
        public static function templateReplace($template, $dataSet = [], $key = null, $value = null)
        {
            $tpl = $template;

            if (is_null($key)) {
                foreach ($dataSet as $key => $value) {
                    $tpl = self::templateReplace($tpl, $dataSet, $key, $value);
                }
            } else {
                $tpl = str_replace("{{ " . $key . " }}", $value, $tpl);
            }

            return $tpl;
        }

        /**
         * Replaces entered string by defined identifiers
         *
         * @param $template
         * @param $loopDataSet
         * @return string
         */
        public static function templateLoop($template, $loopDataSet)
        {
            $tpl = $template;

            $loop = "loop";

            if (strstr($tpl, "@" . $loop)) {
                $splitTpl = self::splitStringByCommand($tpl, "@" . $loop, "@end" . $loop);

                $tplLoopedContent = "";
                foreach ($loopDataSet as $key => $loopValue) {
                    if (is_array($loopValue)) {
                        foreach ($loopValue as $value) {
                            $tplLoopedContent .= self::templateReplace($splitTpl['innerText'], [], $key, $value);
                        }
                    }
                }

                $tpl = $splitTpl['preText'] . $tplLoopedContent . $splitTpl['postText'];
            }

            return $tpl;
        }

        /**
         * Merges all entered array and replaces multiple keys by highest value
         *
         * @param array
         * @return array
         */
        public static function merge_array_replace()
        {
            $inputArrays = func_get_args();
            $outputArray = [];

            foreach ($inputArrays as $array) {
                foreach ($array as $key => $value) {
                    if (!key_exists($key, $outputArray)) {
                        $outputArray[$key] = $value;
                    } elseif ((int) $value > (int) $outputArray[$key]) {
                        $outputArray[$key] = $value;
                    }
                }
            }

            return $outputArray;
        }

        /**
         * Splits entered string by delimiter commands
         *
         * @param string $template
         * @param string $cmdBegin
         * @param string $cmdEnd
         * @return array
         */
        private static function splitStringByCommand($template, $cmdBegin, $cmdEnd)
        {
            $tplPreText = strstr($template, $cmdBegin, true); // Get part before cmdBegin
            $tplPosTextBegin = strpos($template, $cmdBegin) + strlen($cmdBegin); // Get innerText starting position
            $tplTextContent = strstr(substr($template, $tplPosTextBegin), $cmdEnd, true); // Get innerText
            $tplPosTextEnd = strpos($template, $cmdEnd) + strlen($cmdEnd); // Get innerText ending position
            $tplPostText = substr($template, $tplPosTextEnd); // Get part after innerText

            return [
                'preText' => $tplPreText,
                'innerText' => $tplTextContent,
                'postText' => $tplPostText
            ];
        }

        /**
         * Changes amount with comma separated thousands sep to float
         *
         * @param $amount
         * @return float
         */
        public static function changeAmountToFloat($amount) {
            if (is_string($amount)) {
                if (Constants::NUMBER_FORMAT == 'EN') {
                    if (strstr($amount, ',')) {
                        $amount = str_replace(',', '', $amount);
                        $amount = number_format($amount, 2, '.', '');
                    }
                } elseif (Constants::NUMBER_FORMAT == 'DE') {
                    if (strstr($amount, ',')) {
                        $amount = str_replace('.', '', $amount);
                        $amount = str_replace(',', '.', $amount);
                        $amount = number_format($amount, 2, '.', '');
                    }
                }
            }
            return $amount;
        }

        /**
         * @param object $object
         * @param string $method
         * @return bool
         */
        public static function existsAndNotEmpty($object, $method)
        {
            if (method_exists($object, $method)) {
                $var = $object->$method();
                if (!empty($var)) {
                    return true;
                }
            }
            return false;
        }
    }