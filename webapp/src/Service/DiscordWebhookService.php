<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Clarification;
use Psr\Log\LoggerInterface;

class DiscordWebhookService
{
    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly ConfigurationService $config
    ) {}
    
    public function sendClarificationNotification(Clarification $clarification): void
    {
        // Get webhook URL from configuration
        $webhookUrl = $this->config->get('discord_webhook_url');
        
        // Skip if webhook URL is not configured
        if (empty($webhookUrl)) {
            return;
        }
        
        // Build the message
        $contest = $clarification->getContest();
        $problem = $clarification->getProblem();
        $sender = $clarification->getSender();
        $recipient = $clarification->getRecipient();
        
        $message = "**New Clarification**\n";
        $message .= "Contest: " . $contest->getName() . "\n";
        
        if ($problem) {
            $message .= "Problem: " . $problem->getName() . "\n";
        } elseif ($clarification->getCategory()) {
            $message .= "Category: " . $clarification->getCategory() . "\n";
        }
        
        if ($sender) {
            $message .= "From: " . $sender->getEffectiveName() . "\n";
        } else {
            $message .= "From: Jury\n";
        }
        
        if ($recipient) {
            $message .= "To: " . $recipient->getEffectiveName() . "\n";
        } else {
            $message .= "To: Jury\n";
        }
        
        $message .= "\n**Message:**\n" . $clarification->getBody();
        
        // Send to Discord
        $this->sendToDiscord($webhookUrl, $message);
    }
    
    private function sendToDiscord(string $webhookUrl, string $message): void
    {
        $data = [
            "content" => $message,
            "username" => "DOMjudge Clarifications"
        ];
        
        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            $this->logger->error("Discord webhook error: " . curl_error($ch));
        }
        
        curl_close($ch);
    }
}