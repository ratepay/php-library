<?php

namespace RatePAY\Model\Request\SubModel;

class Constants {

    /*
     *  All available RatePAY payment methods
     */
    const RATEPAY_PAYMENT_METHODS = [
        "INVOICE",
        "INSTALLMENT",
        "ELV",
        "PREPAYMENT"
    ];

    /**
     * Library system name default
     */
    const LIBRARY_SYSTEM_NAME = "RP PHP LIB";

    /**
     * Library version
     */
    const LIBRARY_VERSION = "0.9.3.1";

    /**
     * Supported RatePAY API version
     */
    const RATEPAY_API_VERSION = "1.8";

    /**
     * CustomerAllowCreditInquiry
     */
    const CUSTOMER_ALLOW_CREDIT_INQUIRY = "yes";

    /**
     * DebitPayTypes
     */
    const DEBIT_PAY_TYPES = [
        2 => "DIRECT-DEBIT",
        28 => "BANK-TRANSFER"
    ];
}