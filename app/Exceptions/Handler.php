<?php

namespace App\Exceptions;

// preset by Laravel
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// used by function sendMail() to build html mail part
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use App\Mail\ExceptionMail;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $th
     * @return void
     */
    public function report(Throwable $th)
    {
       if ($this->shouldReport($th)) {
            // sends an email
            $this->sendEmail($th);
        }
        parent::report($th);
    }

    /**
     * Send Mail
     * see https://stackoverflow.com/questions/60683968/laravel-7-email-exceptions-broke-after-symfony-5-update
     * not using ExceptionHandler::convertExceptionToResponse()
     * because no informational output, if app.debug == false
     *
     * @return void
     */
    private function sendEmail(Throwable $th)
    {
        try {
            // build text version
            $tracestr = $th->getTraceAsString();
            $tracestr = str_replace( base_path(),"", $tracestr);

            $text  = '** Error: '. $th->getMessage() . PHP_EOL;
            $text .= '** Line:  '. $th->getLine() . PHP_EOL;
            $text .= '** File:  '. str_replace( base_path(),"", $th->getFile() ) . PHP_EOL;
            $text .= '** Trace: '. PHP_EOL . $tracestr;

            // build html version
            // analyse Throwable
            $flat = FlattenException::createFromThrowable($th);
            // must set true, to get full report even if app.debug == false
            $render = new HtmlErrorRenderer(true);
            // get html data
            $style  = $render->getStylesheet();
            $body   = $render->getBody($flat);
            // send mail (Recipient set in mail config file and read by ExceptionMailable)
            \Mail::queue( new ExceptionMailable( $style, $body, $text ) );
        } catch (Throwable $catched) {
            // only log, otherwise it might loop
            \Log::error($catched);
        }
    }
}
