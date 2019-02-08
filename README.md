# Oroshi
Boilerplate for Daikon CQRS based PHP7 applications.

```
$ composer --ignore-platform-reqs install
$ docker-compose up -d
# wait a few moments while cluster initialises
$ bin/oroshi migrate:up
$ bin/oroshi fixture:import
```

## Endpoints for testing:

- Webserver:
  - http://localhost
  - http://localhost/testing/article/create
  - http://localhost/testing/article/update
- CouchDB Admin: http://localhost:5984/_utils/
- RabbitMQ Admin: http://localhost:15672 (rabbit/changme)
- Kibana: http://localhost:5601
