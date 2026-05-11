# Link Compactor

Link Compactor is a very simple url / link shortener written in PHP, without any dependencies. It supports MySQL, PostgreSQL and SQLite, and shows the short link destination instead of redirecting, so users can decide if they continue or not.

## Installation

### Docker

1. Save the docker compose file, and change it according to your needs. You can use both [configuration](#configuration-variables) and [environment variables](#environment-variables). Look at the [sample MySQL compose file](#sample-docker-compose-file-mysql) for an example. 
2. Run `docker compose up -d` to start the container.
3. Run `docker compose exec linkcompactor php create_table.php` to create the table in the database.

The image used in the docker compose files is based on the [serversideup fpm-nginx image](https://serversideup.net/open-source/docker-php/docs/image-variations/fpm-nginx), so it also [supports the same environment variables](https://serversideup.net/open-source/docker-php/docs/image-variations/fpm-nginx#environment-variables). 

#### Sample docker compose file (SQLite)

```yaml copy
services:
  linkcompactor:
    image: ghcr.io/danielhn/link-compactor
    container_name: linkcompactor
    restart: unless-stopped
    volumes:
         - ./database:/var/www/html/database
    post_start:
      - command: chown -R www-data /var/www/html/database
        user: root
    ports:
      - '8568:8080'
```

#### Sample docker compose file (MySQL)

```yaml copy
services:
  linkcompactor:
    image: ghcr.io/danielhn/link-compactor
    container_name: linkcompactor
    environment:
      DATABASE_TYPE: "mysql"
      MYSQL_DATABASE_HOST: "mysql"
      MYSQL_DATABASE_PORT: "3306"
      MYSQL_DATABASE_NAME: "linkcompactor"
      MYSQL_DATABASE_USER: "root"
      MYSQL_DATABASE_PASSWORD: "root"
    restart: unless-stopped
    ports:
      - '8568:8080'
    depends_on:
      - mysql

  mysql:
    image: mysql:latest
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: "linkcompactor"
      MYSQL_ROOT_PASSWORD: "root"
    volumes:
      - mysql_data:/var/lib/mysql

volumes:
  mysql_data:
```

#### Sample docker compose file (PostgreSQL)

```yaml copy
services:
  linkcompactor:
    image: ghcr.io/danielhn/link-compactor
    container_name: linkcompactor
    environment:
      DATABASE_TYPE: "pgsql"
      PGSQL_DATABASE_HOST: "pgsql"
      PGSQL_DATABASE_NAME: "linkcompactor"
      PGSQL_DATABASE_USER: "postgres"
      PGSQL_DATABASE_PASSWORD: "postgres"
    restart: unless-stopped
    ports:
      - '8568:8080'
    depends_on:
      - pgsql

  pgsql:
    image: postgres:latest
    restart: unless-stopped
    environment:
      POSTGRES_DATABASE: "linkcompactor"
      POSTGRES_PASSWORD: "postgres"
    volumes:
      - pgsql_data:/var/lib/postgresql/data

volumes:
  pgsql_data:
```

### Manual

1. Download and copy the files to your server. Make sure you have the public directory as the root of your site.
2. Copy or rename the `env.example.php` file to `env.php`.
3. Adjust `env.php` and `config.php` according to your needs. You can also use environmental variables instead of these files.
4. Run `php create_table.php` in the directory to create the table in the database.
5. You'll need to point to `index.php` as the 404 HTTP error handler in your server.
6. Check that you have installed and enabled `pdo_sqlite`, `pdo_mysql` or `pdo_pgsql` extension, depending on the database you want to use.

## Environment variables

- `DATABASE_TYPE`: The database type to use. Can be `sqlite`, `mysql` or `pgsql`. By default, is set to `sqlite`.
- `SQLITE_DATABASE_PATH`: The path to the SQLite database file. By default, is set to `__DIR__ . '/database/database.
sqlite'`. If you change it, and you want to use docker, you also need to change the path from the command in the docker compose file to the database.
- `MYSQL_DATABASE_HOST`: The host of the MySQL database. By default, is set to `localhost`.
- `MYSQL_DATABASE_PORT`: The port of the MySQL database. By default, is set to `3306`.
- `MYSQL_DATABASE_NAME`: The name of the MySQL database. By default, is set to `linkcompactor`.
- `MYSQL_DATABASE_USER`: The user of the MySQL database. By default, is set to `root`.
- `MYSQL_DATABASE_PASSWORD`: The password of the MySQL database. By default, is set to `root`.
- `MYSQL_DATABASE_CHARSET`: The charset used for the MySQL database connection. By default, is set to `utf8mb4`.
- `PGSQL_DATABASE_HOST`: The host of the PostgreSQL database. By default, is set to `localhost`.
- `PGSQL_DATABASE_PORT`: The port of the PostgreSQL database. By default, is set to `5432`.
- `PGSQL_DATABASE_NAME`: The name of the PostgreSQL database. By default, is set to `linkcompactor`.
- `PGSQL_DATABASE_USER`: The user of the PostgreSQL database. By default, is set to `postgres`.
- `PGSQL_DATABASE_PASSWORD`: The password of the PostgreSQL database. By default, is set to `postgres`.

## Configuration variables

- `SHORT_LINK_ID_LENGTH`: The short link id. By default, is set to `8`. **Do not** change it if there is any link 
  already stored in the database, because old links wouldn't pass the validation, as they would be smaller or larger 
  than the new length used.
- `SHORT_LINK_USE_HTTPS`: If set to true, it will prepend `https://` to the short links. If set to false, it will omit the `s`. By default, is set to `false`.
- `SHORT_LINK_PORT`: If set to anything other than `80` or `443`, it will add the port to the short links. By default, is set to `8568`. It needs to be changed if using another port in a docker container.  
- `SHORT_LINK_DOMAIN`: The domain used for the short links. By default, is set to `localhost`.
- `URL_MAX_LENGTH`: The maximum length that an input url can be. This affects validation in client side, server side,
  and the maximum value that can be stored in the database. By default, is set to `2048`.
- `DATABASE_TABLE_NAME`: The name of the database table used for storing links. By default, is set to `linkcompactor`.

## License

Link Compactor is released under the [AGPLv3 license](LICENSE).