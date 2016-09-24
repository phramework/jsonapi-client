<?php
declare(strict_types=1);

namespace Phramework\JSONAPI\Client;


use Phramework\JSONAPI\Client\Directive\IncludeRelationship;
use Phramework\JSONAPI\Client\Directive\Page;

trait BaseEndpoint
{
    protected $resourceType;

    /**
     * @var Endpoint
     */
    protected $endpoint;

    public function setUp()
    {
        $this->resourceType = 'article';

        $this->endpoint = (new Endpoint($this->resourceType))
            ->setUrl('http://localhost:8005/' . $this->resourceType);
    }

    /**
     * @return string
     */
    public function get()
    {
        $this->setUp();

        $collection = $this->endpoint->get(
            new IncludeRelationship('author'),
            new Page(2)
        );

        $this->assertSame(
            200,
            $collection->getStatusCode()
        );

        $this->assertInternalType(
            'array',
            $collection->getData()
        );

        $data = $collection->getData();

        $this->assertLessThanOrEqual(
            2,
            count($data)
        );

        $id = $collection->getData()[0]->id;
        $id = $collection[0]->id;

        $this->assertInternalType(
            'string',
            $id
        );

        $this->assertSame(
            $collection->getData()[0]->id,
            $collection[0]->id,
            'Expect both notations to return same id'
        );

        return $id;
    }
}
