<?php

namespace NotificationChannels\TotalVoice\Test;

use Mockery;
use Illuminate\Contracts\Events\Dispatcher;
use TotalVoice\Client as TotalVoiceService;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\TotalVoice\TotalVoice;
use NotificationChannels\TotalVoice\TotalVoiceConfig;
use NotificationChannels\TotalVoice\TotalVoiceMessage;
use NotificationChannels\TotalVoice\TotalVoiceSmsMessage;
use NotificationChannels\TotalVoice\TotalVoiceTtsMessage;
use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;
use NotificationChannels\TotalVoice\Exceptions\CouldNotSendNotification;

class TotalVoiceTest extends MockeryTestCase
{
    /** @var TotalVoice */
    protected $totalvoice;

    /** @var TotalVoiceService */
    protected $totalVoiceService;

    /** @var Dispatcher */
    protected $dispatcher;

    /**
     * @var TotalVoiceConfig
     */
    protected $config;

    public function setUp()
    {
        parent::setUp();
        $this->totalVoiceService = Mockery::mock(new TotalVoiceService('access_token'));
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->config = Mockery::mock(TotalVoiceConfig::class);
        $this->totalVoiceService->messages = Mockery::mock($this->totalVoiceService->sms);
        $this->totalVoiceService->calls = Mockery::mock($this->totalVoiceService->audio);
        $this->totalvoice = new TotalVoice($this->totalVoiceService, $this->config);
    }

    /** @test */
    public function it_can_send_a_sms_message_to_totalvoice()
    {
        $message = new TotalVoiceSmsMessage('Message text');
        $message->provideFeedback(false);
        $message->multipart(false);
        $message->scheledule(new \DateTime('now'));

        $this->config->shouldReceive('getAccessToken')
            ->andReturn(null);

        $this->totalVoiceService->messages->shouldReceive('enviar')
            ->with('+1111111111', 'Message text')
            ->andReturn(true);

        $this->totalvoice->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_tts_message_to_totalvoice()
    {
        $message = new TotalVoiceTtsMessage('Message text');
        $message->speed(0);
        $message->voiceType('br-Vitoria');
        $message->provideFeedback(true);

        $this->config->shouldReceive('getAccessToken')
            ->andReturn(null);

        $this->totalVoiceService->calls->shouldReceive('enviar')
            ->with('+1111111111', 'Message text', [
                'velocidade' => 0,
                'resposta_usuario' => true,
                'tipo_voz' => 'bt-Vitoria',
                'bina' => '+2222222222',
                'gravar_audio' => false,
                'detecta_caixa' => false,
            ])
            ->andReturn(true);

        $this->totalvoice->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_call_to_totalvoice()
    {
        $message = new TotalVoiceAudioMessage('http://foooo.bar/audio.mp3');
        $message->provideFeedback(true);
        $message->fakeNumber('+2222222222');
        $message->recordAudio(false);
        $message->detectCallbox(true);
        $this->totalVoiceService->calls->shouldReceive('enviar')
            ->with('+1111111111', 'http://foooo.bar/audio.mp3', true, '+2222222222', true)
            ->andReturn(true);
        $this->totalvoice->sendAudioMessage($message, '+1111111111');
    }

    /** @test */
    public function it_will_throw_an_exception_in_case_of_an_unrecognized_message_object()
    {
        $this->setExpectedException(
            CouldNotSendNotification::class,
            'Notification was not sent. Message object class'
        );

        $this->totalvoice->sendMessage(new InvalidMessage(), null);
    }
}

class InvalidMessage extends TotalVoiceMessage
{
}