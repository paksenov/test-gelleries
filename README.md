```
    docker-compose up -d --build
    docker-compose run --rm php php create-fake.php
    docker-compose exec db sh -c 'mysql -u root -proot < /var/www/data/create-scheme.sql'
    docker-compose exec db sh -c 'mysql -u root -proot < /var/www/data/insert-gallery.sql'
    docker-compose exec db sh -c 'mysql -u root -proot < /var/www/data/insert-image.sql'
    docker-compose exec db sh -c 'mysql -u root -proot < /var/www/data/add-indexes.sql'
```

```
    docker-compose run --rm php php get-galleries.php
```

```
    docker-compose down
```