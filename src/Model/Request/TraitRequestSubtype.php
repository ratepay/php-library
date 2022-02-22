<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
