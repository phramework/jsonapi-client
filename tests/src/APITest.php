<?php

namespace Phramework\APISDK;

use Phramework\JSONAPI\FilterAttribute;
use Phramework\Models\Operator;

/**
 * Class APITest
 * @coversDefaultClass \Phramework\APISDK\API
 */
class APITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::get
     */
    public function testGet()
    {
        $url = API::get(
            new Page(1, 10),
            new Filter(
                [1, 2, 3],
                null,
                [
                    new FilterAttribute('language', Operator::OPERATOR_EQUAL, 'en')
                ]
            ),
            new Sort(null, 'created', false),
            new Fields((object) [
                'user' => ['name', 'email']
            ]),
            new IncludeRelationship('project', 'group')
        );

        var_dump($url);
    }
}
