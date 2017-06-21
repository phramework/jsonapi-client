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
