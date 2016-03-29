<?php


namespace Phramework\APISDK;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Sort extends \Phramework\JSONAPI\Sort
{
    /**
     * @return string
     */
    public function toURL(): string
    {
        return sprintf(
            'sort=%s%s',
            ($this->ascending ? '' : '-'),
            $this->attribute
        );
    }
}
