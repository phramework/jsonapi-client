# jsonapi-client
JSONAPI client, helps creating SDK to consume your JSON RESTful APIs

## Usage
Require package using composer
```bash
composer require phramework/jsonapi-client
```

### GET a collection

```php
<?php
/**
 * Define an API endpoint
 */
$endpoint = (new Endpoint('article'))
    ->setUrl('http://localhost:8005/article/');

/**
 * Get a collection of all `article`s, including their author
 */
$response = $endpoint->get(
    new IncludeRelationship('author')
);

/*
 * Display article collection
 */
print_r($articles = $response->getData());
```

## License
Copyright 2016-2017 Xenofon Spafaridis

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

```
http://www.apache.org/licenses/LICENSE-2.0
```

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.