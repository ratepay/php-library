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

use RatePAY\Model\Request\SubModel\Content;

trait TraitRequestContent
{
    /**
     * @var Content
     */
    private $content;

    /**
     * Sets the value for $content.
     *
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the value of $content.
     *
     * @param Content $content‚
     */
    public function setContent(Content $content)
    {
        $this->content = $content;
    }

    /**
     * Returns all values as Array.
     *
     * @return array
     *
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            [
                'content' => $this->getContent()->toArray(),
            ]
        );
    }
}
