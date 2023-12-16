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


### Access to PHP Bash
You can use a ```make be-bash``` command.
To exit interactive mode you need to send keyboard sequence:
<kbd>ctrl</kbd> + <kbd>p</kbd> and after <kbd>ctrl</kbd> + <kbd>q</kbd>. 