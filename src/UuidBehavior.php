<?php

declare(strict_types=1);

namespace Horat1us\Yii;

use Ramsey\Uuid;
use yii\base;
use yii\db;
use yii\di;

/**
 * Class UuidBehavior
 * @package Horat1us\Yii
 */
class UuidBehavior extends base\Behavior
{
    /** @var string|array|Uuid\UuidFactoryInterface reference */
    public $factory = Uuid\UuidFactory::class;

    /** @var string[] */
    public $attributes = [
        db\ActiveRecord::EVENT_BEFORE_INSERT => 'uuid',
    ];

    public function events(): array
    {
        $keys = array_keys($this->attributes);
        return array_combine($keys, array_fill(0, count($keys), [$this, 'generateUuid',]));
    }

    /**
     * @param base\Event $event
     * @throws \Exception
     */
    public function generateUuid(base\Event $event): void
    {
        if (!array_key_exists($event->name, $this->attributes)) {
            throw new base\InvalidArgumentException("Event {$event->name} is not supported.");
        }

        /** @var Uuid\UuidFactoryInterface $factory */
        $factory = di\Instance::ensure($this->factory, Uuid\UuidFactoryInterface::class);

        $attribute = $this->attributes[$event->name];
        $value = $factory->uuid4()->toString();

        $event->sender->{$attribute} = $value;
    }
}
