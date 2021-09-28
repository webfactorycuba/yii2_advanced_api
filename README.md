# Advanced API system using Yii2

Template for advanced systems using Yii2 technologies, It counts with user administration and access control, multiple language support and API configurations and user access to endpoints including test access token. 

# Installation

### Pre-requirement
Be sure to have installed globally the asset plugin:
```
    composer global require "fxp/composer-asset-plugin:^1.4.1"
```

### Follow next steps

*Note: For use next commands you must be in project root folder*

* Run `composer install` or `composer update` for get vendors files

* Init the configurations file running the command `php init`, then choose the environment dev or prod and generate the config files

* Please create your database and configure the params in `common/config/main-local.php`

* Run the migrations using `php yii migrate`

* Configure your vhost to:
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

* Add to your file `etc/hosts` the entry for that domain 
``` 
127.0.0.1   advanced.domain.local 
```

* Please change the configuration of constants in `common/models/ConfigServerConstants.php`
