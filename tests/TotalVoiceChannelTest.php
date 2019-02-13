<?php

namespace NotificationChannels\TotalVoice\Test;

use Mockery;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\TotalVoice\TotalVoice;
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\TotalVoice\TotalVoiceSmsMessage;
use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;

class TotalVoiceChannelTest extends MockeryTestCase
{
    /** @var TotalVoiceChannel */
    protected $channel;

    /** @var TotalVoice */
    protected $totalvoice;

    /** @var Dispatcher */
    protected $dispatcher;

    public function setUp()
    {
        parent::setUp();
        $this->totalvoice = Mockery::mock(TotalVoice::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->channel = new TotalVoiceChannel($this->totalvoice, $this->dispatcher);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_receiver()
    {
        $notifiable = new Notifiable();
        $notification = Mockery::mock(Notification::class);
        $this->dispatcher->shouldReceive('fire')
            ->with(Mockery::type(NotificationFailed::class));
        $result = $this->channel->send($notifiable, $notification);
        $this->assertNull($result);
    }

    /** @test */
    public function it_will_send_a_sms_message_to_the_result_of_the_route_method_of_the_notifiable()
    {
        $notifiable = new NotifiableWithMethod();
        $notification = Mockery::mock(Notification::class);
        $message = new TotalVoiceSmsMessage('Message text');
        $notification->shouldReceive('toTotalVoice')->andReturn($message);
        $this->totalvoice->shouldReceive('sendMessage')
            ->with($message, '+1111111111', false);
        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_make_a_call_to_the_phone_number_attribute_of_the_notifiable()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);
        $message = new TotalVoiceAudioMessage('http://foooo.bar/audio.mp3');
        $notification->shouldReceive('toTotalVoice')->andReturn($message);
        $this->totalvoice->shouldReceive('sendMessage')
            ->with($message, '+22222222222', false);
        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_convert_a_string_to_a_sms_message()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toTotalVoice')->andReturn('Message text');
        $this->totalvoice->shouldReceive('sendMessage')
            ->with(Mockery::type(TotalVoiceSmsMessage::class), Mockery::any(), false);
        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toTotalVoice')->andReturn(-1);
        $this->dispatcher->shouldReceive('fire')
            ->with(Mockery::type(NotificationFailed::class));
        $this->channel->send($notifiable, $notification);
    }
}

class Notifiable
{
    public $phone_number = null;

    public function routeNotificationFor()
    {}
}

class NotifiableWithMethod
{
    public function routeNotificationFor()
    {
        return '+1111111111';
    }
}

class NotifiableWithAttribute
{
    public $phone_number = '+22222222222';

    public function routeNotificationFor()
    {}
}