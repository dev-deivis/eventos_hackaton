<?php

namespace App\Services;

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Illuminate\Support\Facades\Log;

class BrevoEmailService
{
    private $apiInstance;
    private $enabled;

    public function __construct()
    {
        $this->enabled = env('MAIL_ENABLED', false);
        
        if ($this->enabled) {
            // Configurar API de Brevo
            $config = Configuration::getDefaultConfiguration()->setApiKey(
                'api-key',
                env('BREVO_API_KEY')
            );
            
            $this->apiInstance = new TransactionalEmailsApi(null, $config);
        }
    }

    /**
     * Enviar correo usando API HTTP de Brevo
     */
    public function enviar($destinatario, $asunto, $htmlContent, $textContent = null)
    {
        if (!$this->enabled) {
            Log::info('Correo NO enviado (sistema deshabilitado)', [
                'destinatario' => $destinatario,
                'asunto' => $asunto
            ]);
            return false;
        }

        try {
            $sendSmtpEmail = new SendSmtpEmail([
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'Hackathon Events'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],
                'to' => [[
                    'email' => $destinatario
                ]],
                'subject' => $asunto,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            Log::info('Correo enviado exitosamente via Brevo API', [
                'destinatario' => $destinatario,
                'asunto' => $asunto,
                'messageId' => $result->getMessageId()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error al enviar correo via Brevo API', [
                'destinatario' => $destinatario,
                'asunto' => $asunto,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
