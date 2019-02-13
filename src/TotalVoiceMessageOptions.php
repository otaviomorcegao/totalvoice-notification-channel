<?php

namespace NotificationChannels\TotalVoice;

trait TotalVoiceMessageOptions
{
    /**
     * @var null|string
     */
    public $fake_number = null;

    /**
     * @var null|bool
     */
    public $record_audio = null;

    /**
     * @var null|bool
     */
    public $detect_callbox = null;

    /**
     * Define o número de telefone que aparecerá no identificador 
     * de quem receber a chamada, formato DDD + Número exemplo: 4832830151
     *
     * @param string $voice
     * @return $this
     */
    public function fakeNumber($fake_number)
    {
        $this->fake_number = $fake_number;
        return $this;
    }

    /**
     * 
     * Define se vai gravar a chamada
     *
     * @param boolean $record_audio
     * @return $this
     */
    public function recordAudio($record_audio)
    {
        $this->record_audio = $record_audio;
        return $this;
    }

    /**
     * Define se vai desconectar em caso de cair na caixa postal
     * (vivo, claro, tim e oi)
     *
     * @param boolean $detect_callbox
     * @return $this
     */
    public function detectCallbox($detect_callbox)
    {
        $this->detect_callbox = $detect_callbox;
        return $this;
    }
}