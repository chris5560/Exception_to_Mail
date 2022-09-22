<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExceptionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * various Queue parameters
     * The timeout value should always be at least several seconds shorter
     * than your retry_after config/queue.php configuration value.
     */
    public $timeout = 60;       // seconds before timing out.
    public $tries   = 10;       // number of times to be attempted.
    public $maxExceptions = 2;  // number of unhandled exceptions to allow.
    public $backoff = 180;      // number of seconds to wait before retrying.

    /**
     * handover and other variables (must be public)
     */
    public $style;
    public $body;
    public $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $style, $body, $text )
    {
        // only handover parameters here, otherwise not queueable
        $this->style = $style;
        $this->body  = $body;
        $this->text  = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // set mailer and priority
        $this->mailer('admin');
        $this->priority(1); // highest
        // read config
        $config = config( 'mail.mailers.'. $this->mailer );
        // mail TO addresses 
        $this->to( $config['to']['address'], $config['to']['name'] );

        // subject and message
        $srv_cfg = gethostname();
        $this->subject( '['. $srv_cfg .'] Internal Server Error' );
        $this->text('mails.contentOnly')->with([
            'content' => $this->text
        ]);
        $this->view('mails.html.exception')->with([
            'style' => $this->style,
            'body'  => $this->body,
        ]);
        return $this;
    }

    /**
     * The mail send failed to process.
     *
     * @param  \Throwable $th
     * @return void
     */
    public function failed( \Throwable $th )
    {
        \Log::error( $th );
    }
}
