<?php


namespace Phramework\APISDK;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Fields extends \Phramework\JSONAPI\Fields
{
    /**
     * @return string
     */
    public function toURL(): string
    {
        $parts = [];

        foreach ($this->fields as $key => $value) {
            $parts[] = sprintf(
                'fields[%s]=%s',
                $key,
                implode(
                    ',',
                    array_map(
                        'urlencode',
                        $value
                    )
                )
            );
        }


        return implode(
            '&',
            $parts
        );
    }
}
