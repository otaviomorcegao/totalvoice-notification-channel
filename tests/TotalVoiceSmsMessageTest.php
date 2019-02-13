<?php

namespace NotificationChannels\TotalVoice\Test;

use NotificationChannels\TotalVoice\TotalVoiceSmsMessage;

class TotalVoiceSmsMessageTest extends TotalVoiceMessageTest
{
    public function setUp()
    {
        parent::setUp();
        $this->message = new TotalVoiceSmsMessage();
    }
    
    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TotalVoiceSmsMessage('myMessage');
        $this->assertEquals('myMessage', $message->content);
    }
    
    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TotalVoiceSmsMessage::create('myMessage');
        $this->assertEquals('myMessage', $message->content);
    }
    
    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = TotalVoiceSmsMessage::create('myMessage');
        $date = new \DateTime('now');
        $message->provideFeedback(true);
        $message->multipart(false);
        $message->scheledule($date);
        $this->assertEquals(true, $message->provide_feedback);
        $this->assertEquals(false, $message->multi_part);
        $this->assertEquals($date, $message->scheduled_datetime);
    }
}