<?php

namespace DigitalCoreHub\LaravelElevenLabs\Facades;

use DigitalCoreHub\LaravelElevenLabs\Services\TTS\TtsService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static TtsService tts()
 *
 * @see \DigitalCoreHub\LaravelElevenLabs\Services\TTS\TtsService
 */
class ElevenLabs extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'elevenlabs';
    }

    /**
     * Get the TTS service instance.
     */
    public static function tts(): TtsService
    {
        return app(TtsService::class);
    }
}

