<?php

namespace NotificationChannels\TotalVoice\Test;

use Mockery;
use ArrayAccess;
use TotalVoice\Client as TotalVoiceService;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\TotalVoice\TotalVoice;
use Illuminate\Contracts\Foundation\Application;
use NotificationChannels\TotalVoice\TotalVoiceConfig;
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use NotificationChannels\TotalVoice\TotalVoiceServiceProvider as TotalVoiceProvider;

class TotalVoiceProviderTest extends MockeryTestCase
{
    /** @var TotalVoiceProvider */
    protected $provider;

    /** @var App */
    protected $app;

    public function setUp()
    {
        parent::setUp();

        $this->app = Mockery::mock(App::class);
        $this->provider = new TotalVoiceProvider($this->app);
    }

    /** @test */
    public function it_gives_an_instantiated_totalvoice_object_when_the_channel_asks_for_it()
    {
        $configArray = [
            'access_token' => 'token',
        ];

        $this->app->shouldReceive('offsetGet')
            ->with('config')
            ->andReturn([
                'services.totalvoice' => $configArray,
            ]);

        $totalvoice = Mockery::mock(TotalVoiceService::class);
        $config = Mockery::mock(TotalVoiceConfig::class, $configArray);

        $this->app->shouldReceive('make')->with(TotalVoiceConfig::class)->andReturn($config);
        $this->app->shouldReceive('make')->with(TotalVoiceService::class)->andReturn($totalvoice);

        $this->app->shouldReceive('when')->with(TotalVoiceChannel::class)->once()->andReturn($this->app);
        $this->app->shouldReceive('needs')->with(TotalVoice::class)->once()->andReturn($this->app);
        $this->app->shouldReceive('give')->with(Mockery::on(function ($totalvoice) {
            return $totalvoice() instanceof TotalVoice;
        }))->once();

        $this->app->shouldReceive('bind')->with(TotalVoiceService::class, Mockery::on(function ($totalvoice) {
            return  $totalvoice() instanceof TotalVoiceService;
        }))->once()->andReturn($this->app);

        $this->provider->boot();
    }

    /** @test */
    public function it_gives_an_instantiated_totalvoice_object_when_the_channel_asks_for_it_with_new_config()
    {
        $configArray = [
            'access_token' => 'access_token',
        ];

        $this->app->shouldReceive('offsetGet')
            ->with('config')
            ->andReturn([
                'services.totalvoice' => $configArray,
            ]);

        $totalvoice = Mockery::mock(TotalVoiceService::class);
        $config = Mockery::mock(TotalVoiceConfig::class, $configArray);

        $this->app->shouldReceive('make')->with(TotalVoiceConfig::class)->andReturn($config);
        $this->app->shouldReceive('make')->with(TotalVoiceService::class)->andReturn($totalvoice);

        $this->app->shouldReceive('when')->with(TotalVoiceChannel::class)->once()->andReturn($this->app);
        $this->app->shouldReceive('needs')->with(TotalVoice::class)->once()->andReturn($this->app);
        $this->app->shouldReceive('give')->with(Mockery::on(function ($totalvoice) {
            return $totalvoice() instanceof TotalVoice;
        }))->once();

        $this->app->shouldReceive('bind')->with(TotalVoiceService::class, Mockery::on(function ($totalvoice) {
            return  $totalvoice() instanceof TotalVoiceService;
        }))->once()->andReturn($this->app);

        $this->provider->boot();
    }
}

interface App extends Application, ArrayAccess
{
}
