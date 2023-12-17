# Football team recruitment task



## Requirements to run project:
* Docker
* Docker Compose v3
* Makefile


# How to run a project

To run a project container you need to clone a repository. After this you need
to run a ```make init```. 
Everything will be done automatically.

### Basic urls
```
api.localhost/api - basic endpoint
app.localhost/ - frontend
localhost:8080 - phpmyadmin
```

### DB Connection
```
Connection:
localhost:3306

MYSQL:
      username: root
      password: example
      MYSQL_ROOT_PASSWORD: example
      MYSQL_DATABASE: card_game_recruitment
```

## Useful commands

### Access to PHP Bash
You can use a ```make be-bash``` command.
To exit interactive mode you need to send keyboard sequence:
<kbd>ctrl</kbd> + <kbd>p</kbd> and after <kbd>ctrl</kbd> + <kbd>q</kbd>. 

### Run environment
You can use a ```make up``` or ```make stop``` command to run or stop containers.

### Laravel Pint
Type a ```make pint``` to run Laravel Pint

### Laravel IDE-HELPER
Type a ```make ide-helper``` to run IDE-HELPER

### Larastan
Type a ```make larastan``` to run Larastan

### PHPUnit
Type a ```make test``` to run PHPUnit tests


# What to improve, if I Will get more time

- Add OpenAPI Docs,
- Add logs for all actions,
- Maybe refactor to use a Repository Pattern
- Handle draw - now if round has tie all player losing game