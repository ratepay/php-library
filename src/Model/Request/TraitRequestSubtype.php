<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Model\Request;

trait TraitRequestSubtype
{
    /**
     * Admitted subtypes.
     *
     * @var array
     */
    protected $admittedSubtypes = [];

    /**
     * Sets subtype as needed.
     */
    public function setSubtypeAsRequired()
    {
        $this->subtypeRequired = true;
    }

    /**
     * Returns admitted subtypes.
     *
     * @return array
     */
    public function getAdmittedSubtypes()
    {
        return $this->admittedSubtypes;
    }

    /**
     * Sets admitted subtypes.
     *
     * @param array
     */
    public function setAdmittedSubtypes(array $subtypes)
    {
        $this->admittedSubtypes = $subtypes;
    }
}
