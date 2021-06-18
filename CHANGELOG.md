## 2.6.6
### Fixed
- TimeoutException behaviour

## 2.6.5
### Changed
- Support guzzle 7

## 2.6.4
### Fixed
- Response to allow empty response when Status Code is 204
### Changed
- Minimum PHP version to ^7.3

## 2.6.3
### Added
- JSON validator in Response to throw JsonException when http response is not valid JSON 

## 2.6.2
### Fixed
- Guzzle dependency constraint

## 2.6.1
### Fixed
- JSONAPIResource::getIncluded to return [] when no included resources provided
- Collection::getIncluded to return [] when no included resources provided

## 2.6.0
### Added
- ConnectException
- NetworkException
- TimeoutException
- Endpoint default request timeout in 60 seconds
- Endpoint::withTimeout

## 2.5.0
### Added
- AbstractEndpointWithPostWithId class
- Post::postWithId

## 2.4.1
### Fixed
- Restore FilterAttribute and FilterJSONAttribute classes

# 2.4.0
- Require phramework/jsonapi only as require-dev

# 2.3.0
- Change method visibility Endpoint\Post::withPayload to protected

# 2.2.2
- Fix filter url when using filter attribute multiple times
- Add unit test for FilterTest->getUrl

# 2.2.1
- Fix RelationshipsData->append behaviour with to-many relationships, to store data in jsonapi normalized specification form

## 2.2.0
- Fix Filter->getURL, use client filter attribute classes

## 2.1.0
- Add id parameter for patch, put and delete methods 

## 2.0.0
- Add AbstractEndpoint, to allow extending Endpoint class 
