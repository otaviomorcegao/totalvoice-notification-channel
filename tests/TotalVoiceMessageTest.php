<?php

namespace NotificationChannels\TotalVoice\Test;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\TotalVoice\TotalVoiceMessage;


abstract class TotalVoiceMessageTest extends MockeryTestCase
{
    /** @var TotalVoiceMessage */
    protected $message;

    /** @test */
    abstract public function it_can_accept_a_message_when_constructing_a_message();

    /** @test */
    abstract public function it_provides_a_create_method();

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('myMessage');
        $this->assertEquals('myMessage', $this->message->content);
    }

    /** @test */
    public function it_can_set_the_provide_feedback()
    {
        $this->message->provideFeedback(true);
        $this->assertEquals(true, $this->message->provide_feedback);
    }

    /** @test */
    public function it_can_return_the_provide_feedback_using_getter()
    {
        $this->message->provideFeedback(true);
        $this->assertEquals(true, $this->message->getProvideFeedback());
    }
}
