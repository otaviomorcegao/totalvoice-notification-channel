<?php

namespace NotificationChannels\TotalVoice\Test;

use Mockery;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use TotalVoice\Client as TotalVoiceService;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\TotalVoice\TotalVoice;
use NotificationChannels\TotalVoice\TotalVoiceConfig;
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use NotificationChannels\TotalVoice\TotalVoiceSmsMessage;
use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;

class IntegrationTest extends MockeryTestCase
{
    /** @var TotalVoiceService */
    protected $totalVoiceService;

    /** @var Notification */
    protected $notification;

    /** @var Dispatcher */
    protected $events;

    public function setUp()
    {
        parent::setUp();
        $this->client = new TotalVoiceService('my-access-token');
        $this->totalVoiceService = Mockery::mock($this->client);
        $this->totalVoiceService->messages = Mockery::mock($this->client->sms)->shouldAllowMockingProtectedMethods();
        $this->totalVoiceService->calls = Mockery::mock($this->client->audio);
        $this->events = Mockery::mock(Dispatcher::class);
        $this->notification = Mockery::mock(Notification::class);
    }

    /** @test */
    public function it_can_send_a_sms_message()
    {
        $message = TotalVoiceSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTotalVoice')->andReturn($message);
        $config = new TotalVoiceConfig([
            'access_token' => 'ahsdahHau828ASjnsna',
        ]);
        $totalvoice = new TotalVoice($this->totalVoiceService, $config);
        $channel = new TotalVoiceChannel($totalvoice, $this->events);
        $this->smsMessageWillBeSentToTotalVoiceWith('+22222222222', 'Message text');
        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    /** @test */
    public function it_can_make_a_call()
    {
        $message = TotalVoiceAudioMessage::create('http://foooo.bar/audio.mp3');
        $this->notification->shouldReceive('toTotalVoice')->andReturn($message);
        $config = new TotalVoiceConfig([
            'access_token' => 'ahsdahHau828ASjnsna',
        ]);
        $totalvoice = new TotalVoice($this->totalVoiceService, $config);
        $channel = new TotalVoiceChannel($totalvoice, $this->events);
        $this->callWillBeSentToTotalVoiceWith('+22222222222', 'http://foooo.bar/audio.mp3');
        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    protected function smsMessageWillBeSentToTotalVoiceWith(...$args)
    {
        $this->totalVoiceService->messages->shouldReceive('enviar')
            ->with(...$args)
            ->andReturn(true);
    }

    protected function callWillBeSentToTotalVoiceWith(...$args)
    {
        $this->totalVoiceService->calls->shouldReceive('enviar')
            ->with(...$args)
            ->andReturn(true);
    }
}