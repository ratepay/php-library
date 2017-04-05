<?php

namespace RatePAY\Service;

use RatePAY\Exception\LanguageException;


class LanguageService
{
    /**
     * Current language
     *
     * @var string
     */
    private $language;

    /**
     * LanguageService constructor. Default language is German (deu). Austrian and Swiss-German will be set as German.
     *
     * @param string $language
     * @throws LanguageException
     */
    public function __construct($language = "DE")
    {
        switch (strtoupper($language)) {
            case "AU":
            case "CH":
                $this->language = "DE";
                break;
            default:
                if (key_exists(strtoupper($language), $this->textBlocks)) {
                    $this->language = $language;
                } else {
                    throw new LanguageException("No translation table for '" . $language . "' available");
                }
        }
    }

    /**
     * Magic getter returns value to entered language key
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws LanguageException
     */
    public function __call($name, $arguments)
    {
        $textBlocks = $this->textBlocks[$this->language];

        if (key_exists($name, $textBlocks)) {
            return $textBlocks[$name];
        } else {
            throw new LanguageException("No translation for '" . $name . "' available");
        }
    }

    /**
     * Returns all text blocks array (of current language)
     *
     * @return array
     */
    public function getArray()
    {
        return $this->textBlocks[$this->language];
    }


    /********************************
     *** Begin of the text blocks ***
     ********************************/

    private $textBlocks = [
        'DE' => [
            ### installment part
            'rp_runtime_title' => "Laufzeit",
            'rp_runtime_description' => "Anzahl der monatlichen Raten",
            'rp_rate_title' => "Ratenh&ouml;he",
            'rp_rate_description' => "H&ouml;he der monatlichen Raten",
            'rp_calculation_intro_part1' => "Im Folgenden k&ouml;nnen Sie entscheiden, wie Sie die Ratenzahlung gestalten m&ouml;chten. ",
            'rp_calculation_intro_part2' => "Legen Sie bequem die Anzahl der Raten und somit <b>die Laufzeit</b> der Ratenzahlung fest. ",
            'rp_calculation_intro_part3' => "Oder bestimmen sie einfach die gew&uuml;nschte <b>monatliche Ratenh&ouml;he.</b>",
            'rp_calculate_rate' => "Rate berechnen",
            'rp_total_amount' => "Gesamtbetrag",
            'rp_cash_payment_price' => "Warenwert",
            'rp_calulation_example' => "Die Ratenberechnung kann zum Ratenplan abweichen",
            'rp_interest_amount' => "Zinsbetrag",
            'rp_service_charge' => "Vertragsabschlussgeb&uuml;hr",
            'rp_effective_rate' => "Effektiver Jahreszins",
            'rp_debit_rate' => "Sollzinssatz pro Monat",
            'rp_duration_time' => "Laufzeit",
            'rp_duration_month' => " monatliche Raten &agrave;",
            'rp_last_rate' => "zzgl. einer Abschlussrate &agrave;",
            'rp_months' => "Monate",
            'rp_error_message' => "Ein Fehler ist aufgetreten. Bitte kontaktieren Sie den Shopbetreiber.",
            'rp_mouseover_cash_payment_price' => "Summe aller Artikel Ihres Warenkorbs, incl. Versandkosten etc.",
            'rp_mouseover_service_charge' => "Bei Ratenzahlung pro Bestellung anfallende, einmalige Bearbeitungsgeb&uuml;hr.",
            'rp_mouseover_effective_rate' => "Gesamtkosten des Kredits als j&auml;hrlicher Prozentsatz.",
            'rp_mouseover_debit_rate' => "Periodischer Prozentsatz, der auf das in Anspruch genommene Darlehen angewendet wird.",
            'rp_mouseover_interest_amount' => "Konkreter Geldbetrag, der sich aus den Zinsen ergibt.",
            'rp_mouseover_total_amount' => "Summe der vom K&auml;ufer zu zahlenden Betr&auml;ge aus Warenwert, Vertragsabschlussgeb&uuml;hr und Zinsen.",
            'rp_mouseover_duration_time' => "Dauer des Ratenplans (kann durch Sondertilgungen verk&uuml;rzt werden).",
            'rp_mouseover_duration_month' => "Monatlich f&auml;lliger Teilbetrag.",
            'rp_mouseover_last_rate' => "Im letzten Monat f&auml;lliger Teilbetrag.",
            'rp_calculator' => "Ratenrechner",
            'rp_personal_calculation' => "Pers&Ouml;nliche Ratenberechnung",
            'rp_reason_code_translation_603' => "Die Wunschrate entspricht den vorgegebenen Bedingungen.",
            'rp_reason_code_translation_671' => "Die letzte Rate war niedriger als erlaubt. Laufzeit und/oder Rate wurden angepasst.",
            'rp_reason_code_translation_688' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit langer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_689' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit kurzer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_695' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert.",
            'rp_reason_code_translation_696' => "Die Wunschrate ist zu niedrig. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_697' => "F&uuml;r die gew&auml;hlte Ratenh&ouml;he ist keine entsprechende Laufzeit verf&uuml;gbar. Die Ratenh&ouml;he wurde angepasst.",
            'rp_reason_code_translation_698' => "Die Rate war zu niedrig f&uuml;r die maximal verf&uuml;gbare Laufzeit. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_699' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert.",
        ],
        //'EN' => [''],
        //'NL' => ['']
    ];
}
