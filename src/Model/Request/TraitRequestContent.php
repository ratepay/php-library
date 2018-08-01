<?php

namespace RatePAY\Model\Request;

use RatePAY\Model\Request\SubModel\Content;

trait TraitRequestContent
{
    /**
     * @var Content
     */
    private $content;

    /**
     * Sets the value for $content
     *
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the value of $content
     *
     * @param Content $content‚
     */
    public function setContent(Content $content)
    {
        $this->content = $content;
    }


    /**
     * Returns all values as Array
     *
     * @return array
     * @throws \RatePAY\Exception\ModelException
     * @throws \RatePAY\Exception\RuleSetException
     */
    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            [
                'content' => $this->getContent()->toArray()
            ]
        );
    }

}
