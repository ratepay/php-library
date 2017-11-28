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
            'rp_debit_rate' => "Sollzinssatz p.a. (gebunden)",
            'rp_duration_time' => "Laufzeit",
            'rp_monthly_installment_sg' => " monatliche Rate",
            'rp_monthly_installment_pl' => " monatliche Raten",
            'rp_each' => " je",
            'rp_duration_month' => " monatliche Raten &agrave;",
            'rp_last_rate' => "zzgl. einer Abschlussrate &agrave;",
            'rp_months' => "Monate",
            'rp_showInstallmentPlanDetails' => "Ratenberechnung anzeigen",
            'rp_hideInstallmentPlanDetails' => "Ratenberechnung verbergen",
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
            'rp_personal_calculation' => "Pers&ouml;nliche Ratenberechnung",
            'rp_reason_code_translation_603' => "Die Wunschrate entspricht den vorgegebenen Bedingungen.",
            'rp_reason_code_translation_671' => "Die letzte Rate war niedriger als erlaubt. Laufzeit und/oder Rate wurden angepasst.",
            'rp_reason_code_translation_688' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit langer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_689' => "Die Rate war niedriger als f&uuml;r Ratenpl&auml;ne mit kurzer Laufzeit erlaubt. Die Laufzeit wurde angepasst.",
            'rp_reason_code_translation_695' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert.",
            'rp_reason_code_translation_696' => "Die Wunschrate ist zu niedrig. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_697' => "F&uuml;r die gew&auml;hlte Ratenh&ouml;he ist keine entsprechende Laufzeit verf&uuml;gbar. Die Ratenh&ouml;he wurde angepasst.",
            'rp_reason_code_translation_698' => "Die Rate war zu niedrig f&uuml;r die maximal verf&uuml;gbare Laufzeit. Die Rate wurde erh&ouml;ht.",
            'rp_reason_code_translation_699' => "Die Rate ist zu hoch f&uuml;r die minimal verf&uuml;gbare Laufzeit. Die Rate wurde verringert.",
            ### SEPA part
            'rp_switch_payment_type_bank_transfer' => "Ich m&ouml;chte die Ratenzahlungen selbst vornehmen und nicht per Lastschrift begleichen",
            'rp_switch_payment_type_direct_debit' => "Ich m&ouml;chte die Ratenzahlungen bequem per Lastschrift begleichen",
            'rp_address' => "RatePAY GmbH, Franklinstra&szlig;e 28-29, 10587 Berlin",
            'wcd_address' => "Wirecard Bank AG, Einsteinring 35, 85609 Aschheim",
            'rp_creditor' => "Gl&auml;ubiger-ID",
            'rp_creditor_id' => "DE39RPY00000568463",
            'wcd_creditor_id' => "DE49ZZZ00000002773",
            'rp_mandate' => "Mandatsreferenz",
            'rp_mandate_ref' => "(wird nach Kaufabschluss &uuml;bermittelt)",
            'rp_insert_bank_data' => "Bitte geben Sie Ihre Bankdaten ein",
            'rp_sepa_account_information' => "IBAN Kontodaten",
            'rp_classic_account_information' => "Klassische Kontodaten",
            'rp_account_holder' => "Kontoinhaber",
            'rp_iban' => "IBAN", // "IBAN oder klassische Kontonummer"
            'rp_account_number' => "Kontonummer",
            'rp_bank_code' => "Bankleitzahl",
            'rp_sepa_link' => "Einwilligungserkl&auml;rung zum SEPA-Mandat lesen",
            'rp_sepa_terms_block_1' => "Ich willige hiermit in die Weiterleitung meiner Daten an ",
            'rp_sepa_terms_block_2' => "gem&auml;&szlig; ",
            'rp_sepa_terms_block_3' => "ein und erm&auml;chtige diese, mit diesem Kaufvertrag in Zusammenhang stehende Zahlungen von meinem o.a. Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von RatePAY GmbH auf mein Konto gezogenen Lastschriften einzul&ouml;sen.",
            'rp_data_privacy_policy' => "RatePAY-Datenschutzerkl&auml;rung ",
            'rp_data_privacy_policy_url' => "https://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis-dach",
            'rp_sepa_notice_block_1' => "Hinweis:",
            'rp_sepa_notice_block_2' => "Nach Zustandekommen des Vertrags wird mir die Mandatsreferenz von RatePAY mitgeteilt.",
            'rp_sepa_notice_block_3' => "Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen. Es gelten dabei die mit meinem Kreditinstitut vereinbarten Bedingungen.",
            'wcd_sepa_notice_block' => "Bitte geben Sie ihre Bankverbindung f&uuml;r den monatlichen Einzug zum jeweils 2. des Kalendermonats an. Liegt dieser auf einem Sonn- oder Feiertag, so erfolgt der Einzug am darauffolgenden Werktag:",
            'wcd_sepa_terms_please_note' => "Hinweis",
            'wcd_sepa_terms_block_1' => "Ich erm&auml;chtige die Wirecard Bank AG von meinem Konto mittels Lastschrift einzuziehen. Zugleich weise ich mein Kreditinstitut an, die von der Wirecard Bank AG auf mein Konto gezogenen Lastschriften einzul&ouml;sen.",
            'wcd_sepa_terms_block_2' => "Ich kann innerhalb von acht Wochen, beginnend mit dem Belastungsdatum, die Erstattung des belasteten Betrages verlangen.",
            'wcd_sepa_terms_block_3' => "Es gelten dabei die mit dem Kreditinstitut vereinbarten Bedingungen.",
        ],
        //'EN' => [''],
        //'NL' => ['']
    ];
}
