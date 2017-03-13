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
    public function __construct($language = "DEU")
    {
        switch (strtoupper($language)) {
            case "AUT":
            case "CHE":
                $this->language = "DEU";
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


    /****************************
     *** Begin of text blocks ***
     ****************************/

    private $textBlocks = [
        'DEU' => [
            'rp_insert_wishrate' => "Wunschrate eingeben",
            'rp_insert_runtime' => "Laufzeit w&auml;hlen",
            'rp_calculate_runtime' => "Laufzeit jetzt berechnen",
            'rp_choose_runtime' => "Laufzeit ausw&auml;hlen",
            'rp_calculate_rate' => "Rate jetzt berechnen",
            'rp_or' => "oder",
            'rp_payment_text_wishrate' => "<b>Monatliche Rate</b> angeben und die sich daraus ergebende Laufzeit berechnen lassen.",
            'rp_payment_text_runtime' => "<b>Laufzeit</b> angeben und die sich daraus ergebende monatliche Rate berechnen lassen.",
            'rp_hint_rate_1' => "Geben Sie dazu die von Ihnen gew&uuml;nschte Ratenh&ouml;he ein und lassen",
            'rp_hint_rate_2' => "Sie sich die daraus ergebenden Konditionen anzeigen.",
            'rp_hint_runtime_1' => "Geben Sie dazu die von Ihnen gew&uuml;nschte Laufzeit ein und lassen",
            'rp_hint_runtime_2' => "Sie sich die daraus ergebenden Konditionen anzeigen.",
            'rp_please' => "Bitte",
            'rp_months' => " Monate",
            'rp_total_amount' => "Gesamtbetrag",
            'rp_cash_payment_price' => "Warenwert",
            'rp_calulation_example' => "*die Ratenberechnung kann zum Ratenplan abweichen",
            'rp_calulation_result_text' => "Aus Ihren Eingaben ergibt sich folgende Wunschrate",
            'rp_cash_payment_price_part_one' =>  "Bitte entscheiden Sie sich nun, wie der Warenwert von",
            'rp_cash_payment_price_part_two' =>  "auf die monatlichen Raten verteilt werden soll. Hierzu haben Sie zwei M&ouml;glichkeiten:",
            'rp_interest_amount' => "Zinsbetrag",
            'rp_service_charge' => "Vertragsabschlussgeb&uuml;hr",
            'rp_effective_rate' => "Effektiver Jahreszins",
            'rp_debit_rate' => "Sollzinssatz pro Monat",
            'rp_duration_time' => "Laufzeit",
            'rp_duration_month' => " monatliche Raten à",
            'rp_last_rate' => "zzgl. einer Abschlussrate à",
            'rp_server_off' => "Die rp-Server sind zur Zeit nicht erreichbar. Bitte versuchen Sie es sp&auml;ter noch einmal.",
            'rp_config_error_else' => "Ein Fehler ist aufgetreten. Bitte kontaktieren Sie umgehend den Shopbetreiber.",
            'rp_request_error_else' => "Ein Fehler ist aufgetreten. Bitte kontaktieren Sie den Shopbetreiber.",
            'rp_wrong_value' => "Falsche Eingabe. Bitte ändern Sie ihre Eingabe.",
            'rp_information' => "Information",
            'rp_error' => "<b>Fehler</b>",
            'rp_due_date' => "Gew&uuml;nschter Tag der F&auml;lligkeit: ",
            'rp_individual_rate_calculation' =>  "Individuelle Ratenberechnung*",
            'rp_mouseover_cash_payment_price' => "Summe aller Artikel Ihres Warenkorbs, incl. Versandkosten etc.",
            'rp_mouseover_service_charge' => "Bei Ratenzahlung pro Bestellung anfallende, einmalige Bearbeitungsgeb&uuml;hr",
            'rp_mouseover_effective_rate' => "Gesamtkosten des Kredits als j&auml;hrlicher Prozentsatz",
            'rp_mouseover_debit_rate' => "periodischer Prozentsatz, der auf das in Anspruch genommene Darlehen angewendet wird",
            'rp_mouseover_interest_amount' => "Konkreter Geldbetrag, der sich aus den Zinsen ergibt",
            'rp_mouseover_total_amount' => "Summe der vom K&auml;ufer zu zahlenden Betr&auml;ge aus Warenwert, Vertragsabschlussgeb&uuml;hr und Zinsen",
            'rp_mouseover_duration_time' => "Dauer des Ratenplans (kann durch Sondertilgungen verk&uuml;rzt werden)",
            'rp_mouseover_duration_month' => "Monatlich f&auml;lliger Teilbetrag",
            'rp_mouseover_last_rate' => "Im letzten Monat f&auml;lliger Teilbetrag",
            'rp_calculator' => "Ratenrechner",
            'rp_calculate_rates_now' => "Persönliche Ratenberechnung:",
            'rp_first_month' => "Zum 1. des Monats",
            'rp_second_month' => "Zum 15. des Monats",
            'rp_third_month' => "Zum 28. des Monats",
            'rp_reason_code_translation_603' => "Die Wunschrate entspricht den vorgegebenen Bedingungen.",
            'rp_reason_code_translation_671' => "Die letzte Rate war niedriger als erlaubt. Laufzeit und/oder Rate wurden angepasst.",
            'rp_reason_code_translation_688' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit langer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_689' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit kurzer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_695' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert.",
            'rp_reason_code_translation_696' => "Die Wunschrate ist zu niedrig. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_697' => "F&uuml;r die gew&auml;hlte Ratenh&ouml;he ist keine entsprechende Laufzeit verf&uuml;gbar. Die Ratenh&ouml;he wurde angepasst.",
            'rp_reason_code_translation_698' => "Die Rate war zu niedrig f&uuml;r die maximal verf&uuml;gbare Laufzeit. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_699' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert."
        ],
        'ENG' => [''],
        'NLD' => [''],
    ];
}