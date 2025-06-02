<?php

namespace App\Mail;

use App\Models\MessageTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str; // Import Str

class GeneralMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public MessageTemplate $templateModel; // Renamed for clarity
    public array $data;
    public string $processedContent;
    public string $processedSubject;

    /**
     * Create a new message instance.
     */
    public function __construct(MessageTemplate $templateModel, array $data = [])
    {
        $this->templateModel = $templateModel;
        $this->data = $data;

        // Pre-process subject and content here
        $this->processedSubject = $this->applyVariables($this->templateModel->subject ?? 'Notification', $this->data);
        $this->processedContent = $this->applyVariables($this->templateModel->content, $this->data, true); // true to allow HTML
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->processedSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.generic_html_email', // Point to the new generic Blade view
            with: [
                'htmlContent' => $this->processedContent, // Pass the processed HTML to the view
            ],
        );
    }

    /**
     * Apply dynamic variables to the content.
     *
     * @param string|null $content
     * @param array $dataVariables
     * @param bool $allowHtml
     * @return string
     */
    protected function applyVariables(?string $content, array $dataVariables, bool $allowHtml = false): string
    {
        if (is_null($content)) {
            return '';
        }

        foreach ($dataVariables as $key => $value) {
            // Ensure value is scalar or stringable before replacing
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $placeholderValue = $allowHtml ? (string) $value : e((string) $value);
                $content = Str::replace('{{' . $key . '}}', $placeholderValue, $content);
                $content = Str::replace('{{ ' . $key . ' }}', $placeholderValue, $content); // Also with spaces
            }
        }
        return $content;
    }
}
