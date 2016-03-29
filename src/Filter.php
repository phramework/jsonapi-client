<?php


namespace Phramework\APISDK;
use Phramework\JSONAPI\FilterAttribute;
use Phramework\JSONAPI\FilterJSONAttribute;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Filter extends \Phramework\JSONAPI\Filter
{

    /**
     * @param string $type
     * @return string
     */
    public function toURL(string $type): string
    {
        $parts = [];

        //append primary filter
        if ($this->primary) {
            $parts[] = sprintf(
                'filter[%s]=%s',
                urlencode($type),
                implode(',', $this->primary)
            );
        }

        foreach ($this->relationships as $key => $value) {
            $parts[] = sprintf(
                'filter[%s]=%s',
                urlencode($key),
                implode(',', $value)
            );
        }

        foreach ($this->attributes as $attribute) {
            switch (get_class($attribute)) {
                case FilterAttribute::class:
                    $parts[] = sprintf(
                        'filter[%s]=%s%s',
                        urlencode($attribute->attribute),
                        urlencode($attribute->operator),
                        urlencode($attribute->operand)
                    );
                    break;
                case FilterJSONAttribute::class:
                    $parts[] = sprintf(
                        'filter[%s.%s]=%s%s',
                        urlencode($attribute->attribute),
                        urlencode($attribute->key),
                        urlencode($attribute->operator),
                        urlencode($attribute->operand)
                    );
                    break;
            }
        }


        return implode(
            '&',
            $parts
        );
    }
}
