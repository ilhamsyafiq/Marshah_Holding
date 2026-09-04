<?php
/**
 * Lightweight Mailer (dependency-free)
 *
 * Uses PHP's built-in mail() to notify the admin of a new catalog lead.
 * No PHPMailer / composer / vendor dependency. Fails silently.
 */

require_once __DIR__ . '/config.php';

/**
 * Notify the admin that a new catalog lead was captured.
 *
 * @param array $lead { id, nama, telefon, email, source, status, created_at }
 * @return bool  True if mail() accepted the message, false otherwise.
 */
function sendLeadNotification(array $lead): bool
{
    try {
        $to      = ADMIN_EMAIL;
        $subject = 'Lead Katalog Baharu - Marshah';

        $nama    = $lead['nama'] ?? '-';
        $telefon = $lead['telefon'] ?? '-';
        $email   = $lead['email'] ?? '-';
        $source  = $lead['source'] ?? 'katalog';
        $created = $lead['created_at'] ?? date('Y-m-d H:i:s');

        $body  = "Lead katalog baharu diterima di laman web Marshah Holding.\n\n";
        $body .= "Nama    : {$nama}\n";
        $body .= "Telefon : {$telefon}\n";
        $body .= "Emel    : {$email}\n";
        $body .= "Sumber  : {$source}\n";
        $body .= "Tarikh  : {$created}\n\n";
        $body .= "Sila hubungi pelanggan ini secepat mungkin.\n";
        $body .= "-- \nMarshah Holding Sdn Bhd\n";

        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'no-reply@marshahholding.com';
        $fromName  = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Marshah Holding';

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $headers .= "Reply-To: {$email}\r\n";
        }
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        // mail() may be unavailable on a stock XAMPP install — must not fatal.
        $sent = @mail($to, $subject, $body, $headers);
        return (bool) $sent;
    } catch (\Throwable $e) {
        error_log('Lead notification failed: ' . $e->getMessage());
        return false;
    }
}
