<?php
namespace Waryway\MicroServiceEngine;

class MimeType
{
    /**
     * The 'string' of the returned content-type header? (i.e. application/javascript or text/html)
     * @var string
     */
    private $contentType;

    /**
     * Extension identifier for usage. i.e. .js or .html
     *
     * @var string
     */
    private $extension;

    public function __construct($contentType, $extension)
    {
        $this->contentType = $contentType;
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
?>