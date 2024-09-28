# Mini-OnlineClass

Project of reviving old native-php project that i have and containerize it.


## Installation

- Compile dockerfile to became docker image

```bash
docker build -t miniclass:<tags> .
```

- Copy the env template for database credential and change the credential to match your database credential.

```bash
cp env-db-template .env-db
```

- Run the database migration from file `miniclass/miniclass.sql` on the database.

- Run the container
```bash
docker run -d -p 9000:9000 --env-file ./.env-db miniclass:<tags>
```