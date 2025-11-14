# Laravel ElevenLabs

[![Latest Version](https://img.shields.io/packagist/v/digitalcorehub/laravel-elevenlabs.svg?style=flat-square)](https://packagist.org/packages/digitalcorehub/laravel-elevenlabs)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalcorehub/laravel-elevenlabs.svg?style=flat-square)](https://packagist.org/packages/digitalcorehub/laravel-elevenlabs)
[![License](https://img.shields.io/packagist/l/digitalcorehub/laravel-elevenlabs.svg?style=flat-square)](https://packagist.org/packages/digitalcorehub/laravel-elevenlabs)

A modern, fluent Laravel package for integrating with the ElevenLabs Text-to-Speech API. This package provides a clean and intuitive interface for converting text to speech in your Laravel applications.

## 📋 Requirements

- PHP 8.2 or higher
- Laravel 12.0 or higher

## 🚀 Installation

You can install the package via Composer:

```bash
composer require digitalcorehub/laravel-elevenlabs
```

## ⚙️ Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=elevenlabs-config
```

This will create a `config/elevenlabs.php` file in your config directory.

### Environment Variables

Add the following to your `.env` file:

```env
ELEVENLABS_API_KEY=your_api_key_here
ELEVENLABS_DEFAULT_VOICE=nova
ELEVENLABS_DEFAULT_FORMAT=mp3_44100_128
ELEVENLABS_BASE_URL=https://api.elevenlabs.io/v1
ELEVENLABS_TIMEOUT=30
```

### Configuration Options

- **api_key**: Your ElevenLabs API key (required)
- **base_url**: The base URL for the ElevenLabs API (default: `https://api.elevenlabs.io/v1`)
- **default_voice**: The default voice ID to use (default: `nova`)
- **default_format**: The default audio format (default: `mp3_44100_128`)
- **timeout**: Request timeout in seconds (default: `30`)

## 📖 Usage

### Basic Usage

The package provides a fluent API for generating text-to-speech audio:

```php
use DigitalCoreHub\LaravelElevenLabs\Facades\ElevenLabs;

// Generate audio and save to storage
ElevenLabs::tts()
    ->voice('nova')
    ->text('Hello from Laravel')
    ->format('mp3_44100_128')
    ->save('voices/hello.mp3');
```

### Using Defaults

If you've configured default voice and format, you can omit them:

```php
ElevenLabs::tts()
    ->text('Hello from Laravel')
    ->save('voices/hello.mp3');
```

### Getting Audio File Object

Instead of saving directly, you can get an `AudioFile` object:

```php
$audioFile = ElevenLabs::tts()
    ->voice('nova')
    ->text('Hello from Laravel')
    ->format('mp3_44100_128')
    ->generate();

// Access the content
$content = $audioFile->getContent();

// Get the format
$format = $audioFile->getFormat();

// Save to a different location
$audioFile->save('custom/path/audio.mp3', 's3');
```

### Custom Voice Settings

You can customize voice settings (stability, similarity_boost, etc.):

```php
ElevenLabs::tts()
    ->voice('nova')
    ->text('Hello from Laravel')
    ->voiceSettings([
        'stability' => 0.7,
        'similarity_boost' => 0.8,
    ])
    ->save('voices/hello.mp3');
```

### Available Voices

Common voice IDs include:
- `nova`
- `rachel`
- `domi`
- `bella`
- `antoni`
- `elli`
- `josh`
- `arnold`
- `adam`
- `sam`

### Supported Formats

- `mp3_44100_128`
- `mp3_44100_192`
- `mp3_44100_256`
- `pcm_16000`
- `pcm_22050`
- `pcm_24000`
- `pcm_44100`
- `ulaw_8000`

### Using Different Storage Disks

```php
ElevenLabs::tts()
    ->text('Hello from Laravel')
    ->save('voices/hello.mp3', 's3'); // Save to S3
```

## 🔄 Queue Usage

You can easily queue TTS generation jobs:

```php
use Illuminate\Support\Facades\Queue;

Queue::push(function () {
    ElevenLabs::tts()
        ->text('This will be processed in the background')
        ->save('voices/queued.mp3');
});
```

Or create a dedicated job:

```php
namespace App\Jobs;

use DigitalCoreHub\LaravelElevenLabs\Facades\ElevenLabs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTtsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $text,
        public string $outputPath,
        public ?string $voice = null
    ) {}

    public function handle(): void
    {
        $tts = ElevenLabs::tts()->text($this->text);

        if ($this->voice) {
            $tts->voice($this->voice);
        }

        $tts->save($this->outputPath);
    }
}
```

## 🧪 Testing

The package includes a `FakeTtsProvider` for testing purposes. You can use it in your tests:

```php
use DigitalCoreHub\LaravelElevenLabs\Tests\Fake\FakeTtsProvider;
use DigitalCoreHub\LaravelElevenLabs\Http\Endpoints\TtsEndpoint;

// In your test setup
$this->app->singleton(TtsEndpoint::class, function ($app) {
    $client = $app->make(ElevenLabsClient::class);
    return new FakeTtsProvider($client);
});
```

## 🛣️ Roadmap

### v0.1 - Text-to-Speech (TTS) ✅
- [x] Fluent API for TTS generation
- [x] Support for multiple audio formats
- [x] Custom voice settings
- [x] Storage integration
- [x] Configuration management
- [x] Comprehensive test coverage

## 📝 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For issues, questions, or contributions, please open an issue on GitHub.

---

Made with ❤️ by [DigitalCoreHub](https://digitalcorehub.com)
