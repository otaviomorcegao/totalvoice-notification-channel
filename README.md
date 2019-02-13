Use this repo as a skeleton for your new channel, once you're done please submit a Pull Request on [this repo](https://github.com/laravel-notification-channels/new-channels) with all the files.

Here's the latest documentation on Laravel 5.3+ Notifications System: 

https://laravel.com/docs/master/notifications

# A Boilerplate repo for contributions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/totalvoice.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/totalvoice)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/totalvoice/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/totalvoice)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/totalvoice.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/totalvoice)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/totalvoice/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/totalvoice/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/totalvoice.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/totalvoice)

This package makes it easy to send notifications using [Totalvoice](link to service) with Laravel 5.3.

**Note:** Replace ```TotalVoice``` ```Totalvoice``` ```Rafael``` ```hafael``` ```https://github.com/hafael``` ```villa655321verde@gmail.com``` ```totalvoice``` ```Totalvoice Notification Channels``` ```:style_ci_id``` ```:sensio_labs_id``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [LICENSE.md](LICENSE.md), [composer.json](composer.json) and other files, then delete this line.
**Tip:** Use "Find in Path/Files" in your code editor to find these keywords within the package directory and replace all occurences with your specified term.

This is where your description should go. Add a little code example so build can understand real quick how the package can be used. Try and limit it to a paragraph or two.



## Contents

- [Installation](#installation)
	- [Setting up the Totalvoice service](#setting-up-the-Totalvoice-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/totalvoice
```

Add the service provider (only required on Laravel 5.4 or lower):

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\TotalVoice\TotalVoiceServiceProvider::class,
],
```

### Setting up the Totalvoice service

Add your TotalVoice Access Token to your `config/services.php`:

```php
// config/services.php
...
'totalvoice' => [
    'access_token' => env('TOTALVOICE_ACCESS_TOKEN'),
],
...

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use NotificationChannels\TotalVoice\TotalVoiceSmsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TotalVoiceChannel::class];
    }

    public function toTotalVoice($notifiable)
    {
        return (new TotalVoiceSmsMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

You can also send an TTS (text-to-speech) audio call:

``` php
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use NotificationChannels\TotalVoice\TotalVoiceTtsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TotalVoiceChannel::class];
    }

    public function toTotalVoice($notifiable)
    {
        return (new TotalVoiceTtsMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

Or create a TotalVoice audio call from .mp3 file url:

``` php
use NotificationChannels\TotalVoice\TotalVoiceChannel;
use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TotalVoiceChannel::class];
    }

    public function toTotalVoice($notifiable)
    {
        return (new TotalVoiceAudioMessage())
            ->content("http://foooo.bar/audio.mp3");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForTotalVoice` method to your Notifiable model.

```php
public function routeNotificationForTotalVoice()
{
    return '+5521999999999';
}
```



### Available Message methods

#### TotalVoiceSmsMessage (SMS)

- `provideFeedback(false)`: Wait for recipient feedback.
- `multipart(false)`: Supports SMS with > 160 < 16,000 char. Sends multiple sms up to 160char to the same number.
- `scheledule(new \DateTime())`: date and time to schedule the sms delivery. null as default sends immediately.
- `content('')`: Accepts a string value for the notification body.

#### TotalVoiceTtsMessage (Text-to-speech audio call)

- `provideFeedback(false)`: Wait for recipient feedback.
- `fakeNumber(null)`: Accepts a phone to use as the notification sender.
- `recordAudio(false)`: Save the call.
- `detectCallbox(false)`: Automatically disconnects within 3 seconds if it falls into the mailbox (vivo, claro, tim, oi).
- `speed(0)`: From -10 to 10. When -10=very slow, 0=normal and 10=very fast.
- `voiceType('br-Vitoria')`: language-Character acronym who will speak.
- `content('')`: Accepts a string value for the notification body.

#### TotalVoiceAudioMessage (.mp3 audio call)

- `provideFeedback(false)`: Wait for recipient feedback.
- `fakeNumber('+5521999999999')`: Accepts a phone to use as the notification sender.
- `recordAudio(false)`: Save the call.
- `detectCallbox(false)`: Automatically disconnects within 3 seconds if it falls into the mailbox (vivo, claro, tim, oi).
- `content('http://foooo.bar/audio.mp3')`: Accepts an .mp3 file url for the call.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email villa655321verde@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Rafael](https://github.com/hafael)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
