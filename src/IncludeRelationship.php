<?php


namespace Phramework\APISDK;


class IncludeRelationship
{
    protected $types;

    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return string
     */
    public function toURL(): string
    {
        return sprintf(
            'include=%s',
            implode(
                ',',
                array_map(
                    'urlencode',
                    $this->types
                )
            )
        );
    }
}
