# Yii2 UUID Behavior
[![Build Status](https://travis-ci.org/Horat1us/yii2-uuid-behavior.svg?branch=master)](https://travis-ci.org/Horat1us/yii2-uuid-behavior)
[![codecov](https://codecov.io/gh/Horat1us/yii2-uuid-behavior/branch/master/graph/badge.svg)](https://codecov.io/gh/Horat1us/yii2-uuid-behavior)

Behavior to generate UUID values (mostly, for ActiveRecord primary key).

## Installation
Using [packagist.org](https://packagist.org/packages/horat1us/yii2-uuid-behavior):
```bash
composer require wearesho-team/yii2-uuid-behavior:^1.0
```

## Usage
To generate UUID for primary key follow example:
```php
<?php

namespace App;

use Horat1us\Yii\UuidBehavior;
use yii\db;

/**
* Class Record
 * @package App
 * 
 * @property string $uuid // primary key in database, without default value and auto-increment
 */
class Record extends db\ActiveRecord
{
    public function behaviors(): array {
        return [
            'uuid' => [
                'class' => UuidBehavior::class,    
            ],    
        ];
    }
}
```
in followed example uuid in active record will be filled with random UUIDv4 before inserting.

## License
[MIT](./LICENSE)
