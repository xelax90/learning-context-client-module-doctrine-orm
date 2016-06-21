# Learning Context Client Module Doctrine ORM

This module provides a Doctrine token storage for the Learning Context Client. The tokens will be stored in the `lc_accesstoken` 
and `lc_refreshtoken` tables. 

For more information about the Learning Context Client see https://github.com/xelax90/learning-context-client

For more information about the Learning Context Client Module see https://github.com/xelax90/learning-context-client-module

## Setup

* Install this module with composer

 ```
 composer require xelax90/learning-context-client-module-doctrine-orm
 ```

* Add `LearningContextClientModuleDoctrineORM` to your modules array in `config/application.config.php` AFTER `LearningContextClientModule`

 ```php
  $config = array(
    'modules' => array(
      // ...
      'LearningContextClientModule',
      'LearningContextClientModuleDoctrineORM',
      // ...
    ),
    // ...
 );
 ```

