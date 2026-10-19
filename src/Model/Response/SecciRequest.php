<?php

/*
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Model\Response;

class SecciRequest extends AbstractResponse
{
    /**
     * Validates response.
     */
    public function validateResponse()
    {
        if ($this->getStatusCode() == 'OK' && $this->getResultCode() == 504) {
            if (!isset($this->getResponse()->content->{'secci-result'})) {
                return;
            }
            $secciResult = $this->getResponse()->content->{'secci-result'};

            $attestationToken = $this->getNodeValue($secciResult, 'attestation-token');
            $documentId = $this->getNodeValue($secciResult, 'document-id');
            if ($attestationToken === null || $documentId === null) {
                return;
            }

            $document = null;
            $contentType = null;
            if (isset($secciResult->{'document'})) {
                $document = $this->getNodeValue($secciResult, 'document');
                $contentType = $this->getNodeValue($secciResult, 'content-type');
                // Document is base64 encoded and must be decodable
                if ($document === null || $contentType === null || base64_decode($document, true) === false) {
                    return;
                }
            }

            $this->setResult(['attestationToken' => $attestationToken]);
            $this->setResult(['documentId' => $documentId]);
            if ($document !== null) {
                $this->setResult(['document' => $document]);
                $this->setResult(['contentType' => $contentType]);
            }
            $this->setSuccessful();
        }
    }

    /**
     * Returns the trimmed value of a child node or null if the node is missing or empty.
     *
     * @param \SimpleXMLElement $parent
     * @param string            $name
     *
     * @return string|null
     */
    private function getNodeValue($parent, $name)
    {
        if (!isset($parent->{$name})) {
            return null;
        }

        $value = trim((string) $parent->{$name});

        return ($value !== '') ? $value : null;
    }

    /**
     * Returns attestation token.
     *
     * @return string|null
     */
    public function getAttestationToken()
    {
        return (key_exists('attestationToken', $this->result)) ? $this->result['attestationToken'] : null;
    }

    /**
     * Returns document id.
     *
     * @return string|null
     */
    public function getDocumentId()
    {
        return (key_exists('documentId', $this->result)) ? $this->result['documentId'] : null;
    }

    /**
     * Returns document.
     *
     * @return string|null
     */
    public function getDocument()
    {
        // Document variable comes as base64 encoded string!
        if (!key_exists('document', $this->result)) {
            return null;
        }

        $document = base64_decode($this->result['document'], true);

        return ($document !== false) ? $document : null;
    }

    /**
     * Returns content type of the document.
     *
     * @return string|null
     */
    public function getContentType()
    {
        return (key_exists('contentType', $this->result)) ? $this->result['contentType'] : null;
    }
}
