<?php

namespace NotificationChannels\TotalVoice\Test;

use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;
use NotificationChannels\TotalVoice\TotalVoiceTtsMessage;

class TotalVoiceAudioMessageTest extends TotalVoiceMessageTest
{
    
    public function setUp()
    {
        parent::setUp();
        $this->message = new TotalVoiceAudioMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TotalVoiceAudioMessage('http://foooo.bar/audio.mp3');
        $this->assertEquals('http://foooo.bar/audio.mp3', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TotalVoiceTtsMessage::create('http://foooo.bar/audio.mp3');
        $this->assertEquals('http://foooo.bar/audio.mp3', $message->content);
    }

    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = TotalVoiceTtsMessage::create('http://foooo.bar/audio.mp3');
        
        $message->provideFeedback(true);
        $message->fakeNumber('+22222222222');
        $message->recordAudio(false);
        $message->detectCallbox(true);

        $this->assertEquals(true, $message->provide_feedback);
        $this->assertEquals('+22222222222', $message->fake_number);
        $this->assertEquals(false, $message->record_audio);
        $this->assertEquals(true, $message->detect_callbox);
    }
    
}