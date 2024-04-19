<?php

namespace App\Http\Controllers;

use Dotunj\LaraTwilio\Facades\LaraTwilio;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Client\Credentials\Basic;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $apiKey = $request->input('api_key');
        $apiSecret = $request->input('api_secret');
        $senderId = $request->input('sender_id');
        $recipients = $request->input('recipients');
        $message = $request->input('message');
        $channel = $request->input('channel');

        // Assurez-vous que $recipients est un tableau
        if (!is_array($recipients)) {
            $recipients = explode(',', $recipients); // Convertit la chaîne en tableau, en supposant que les destinataires sont séparés par des virgules
        }

        if ($channel == 'twilio') {
            config(['laratwilio.account_sid' => $apiKey]);
            config(['laratwilio.auth_token' => $apiSecret]);
            config(['laratwilio.sms_from' => $senderId]);

            foreach ($recipients as $recipient) {
                $sendSms = LaraTwilio::notify($recipient, $message);
                // Vérifiez si le message a été envoyé avec succès et affichez un message
                if ($sendSms['status'] == 'success') {
                    Log::info("Message envoyé avec succès à $recipient via Twilio.");
                } else {
                    Log::error("Échec de l'envoi du message à $recipient via Twilio.");
                }
            }
        } elseif ($channel == 'nexmo') {
            try {
                $client = new \Vonage\Client(new \Vonage\Client\Credentials\Basic($apiKey, $apiSecret));
            } catch (\Exception $e) {
                Log::error("Erreur lors de la création du client Nexmo : " . $e->getMessage());
                return;
            }

            foreach ($recipients as $recipient) {
                $response = $client->message()->send([
                    'to' => $recipient,
                    'from' => $senderId,
                    'text' => $message,
                ]);

                // Vérifiez si le message a été envoyé avec succès et affichez un message
                if ($response->getStatus() == 0) { // Correction du statut de succès pour Nexmo
                    Log::info("Message envoyé avec succès à $recipient via Nexmo.");
                } else {
                    Log::error("Échec de l'envoi du message à $recipient via Nexmo.");
                }
            }
        } else {
            Log::error("Canal d'envoi invalide.");
        }
    }

    public function showForm()
    {
        return view('sender');
    }
}
