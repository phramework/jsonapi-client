<?php


namespace Phramework\APISDK;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Page extends \Phramework\JSONAPI\Page
{
    /**
     * @return string
     */
    public function toURL(): string
    {
        $parts = [];

        //append limit
        if ($this->limit) {
            $parts[] = sprintf(
                'page[limit]=%s',
                $this->limit
            );
        }

        //append offset
        if ($this->offset) {
            $parts[] = sprintf(
                'page[offset]=%s',
                $this->offset
            );
        }


        return implode(
            '&',
            $parts
        );
    }
}
