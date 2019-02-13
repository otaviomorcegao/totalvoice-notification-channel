<?php

namespace NotificationChannels\TotalVoice\Test;

use NotificationChannels\TotalVoice\TotalVoiceTtsMessage;

class TotalVoiceTtsMessageTest extends TotalVoiceMessageTest
{
    public function setUp()
    {
        parent::setUp();
        $this->message = new TotalVoiceTtsMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TotalVoiceTtsMessage('myMessage');
        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TotalVoiceTtsMessage::create('myMessage');
        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = TotalVoiceTtsMessage::create('myMessage');
        $message->provideFeedback(true);
        $message->speed(2);
        $message->voiceType('br-Vitoria');
        $this->assertEquals(true, $message->provide_feedback);
        $this->assertEquals(2, $message->speed);
        $this->assertEquals('br-Vitoria', $message->voice_type);
    }
    
}