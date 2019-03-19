<?php

declare(strict_types=1);

namespace Horat1us\Yii\Tests;

use PHPUnit\Framework\TestCase;
use Horat1us\Yii\UuidBehavior;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Uuid;
use yii\base;
use yii\db;

/**
 * Class UuidBehaviorTest
 * @package Horat1us\Yii\Tests
 */
class UuidBehaviorTest extends TestCase
{
    protected const TEST_UUID = '059b012d-8270-49a4-bf7a-e394fba207ec';

    public function testEvents(): void
    {
        $behavior = new UuidBehavior;
        $behavior->attributes = [
            base\Model::EVENT_AFTER_VALIDATE => 'afterValidateAttribute',
            base\Model::EVENT_BEFORE_VALIDATE => 'beforeValidateAttribute',
        ];

        $events = $behavior->events();
        $this->assertEquals([$behavior, 'generateUuid',], $events[base\Model::EVENT_AFTER_VALIDATE]);
        $this->assertEquals([$behavior, 'generateUuid',], $events[base\Model::EVENT_AFTER_VALIDATE]);
    }

    public function testUnsupportedEvents(): void
    {
        $behavior = new UuidBehavior;

        $this->expectException(base\InvalidArgumentException::class);
        $this->expectExceptionMessage("Event UnsupportedEvent is not supported.");

        /** @noinspection PhpUnhandledExceptionInspection another will be threw before */
        $behavior->generateUuid(new base\Event(['name' => 'UnsupportedEvent']));
    }

    public function testSettingProperty(): void
    {
        $sender = new class
        {
            /** @var string */
            public $uuid;
        };
        $event = new base\Event(['name' => db\ActiveRecord::EVENT_BEFORE_INSERT, 'sender' => $sender,]);
        /** @noinspection PhpUnhandledExceptionInspection */
        $factory = $this->createMock(UuidFactory::class);

        $factory
            ->expects($this->once())
            ->method('uuid4')
            ->willReturn(Uuid::fromString(static::TEST_UUID));

        $behavior = new UuidBehavior([
            'factory' => $factory,
        ]);
        /** @noinspection PhpUnhandledExceptionInspection */
        $behavior->generateUuid($event);

        $this->assertEquals(static::TEST_UUID, $sender->uuid);
    }
}
