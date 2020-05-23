# Advanced API system using Yii2

Template for advanced systems using Yii2 technologies, It counts with user administration and control access, multiple language support and API configurations and user access to endpoints including test token access. 

# Installation

*Note: For use next commands you must be in project root folder*

1. Run `composer install` for get vendors files

2. Init the configurations file running the command `php init`, then choose the environment dev or prod and generate the config files

3. Pleas create your database and configure the params in `common/config/main-local.php`

4. Run the migrations using `php yii migrate`

5. Configure your vhost to:

```
<VirtualHost *:80>
    ServerAdmin webfactorycuba@gmail.com
    DocumentRoot "path/to/advanced_api/backend/web"
    ServerName advanced.domain.local
    ServerAlias www.advanced.domain.local

    ErrorLog "logs/advanced-error.log"
    CustomLog "logs/advanced-access.log" common

    Options +FollowSymLinks

</VirtualHost>
```

6. Add to your file `etc/hosts` the entry for that domain
```
127.0.0.1   advanced.domain.local
```
