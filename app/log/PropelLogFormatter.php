<?php
namespace App\Log;

use Illuminate\Http\Request;
use Monolog\Formatter\LineFormatter;
use Illuminate\Support\Facades\Auth;

class PropelLogFormatter
{
    public $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($this->getLogFormatter());
        }
    }
    
    protected function getLogFormatter()
    {
        $authUser = Auth::user();//$this->request->user();
        $authUserID =   '';

        if ($authUser != false){
            $authUserID = '['.$authUser->name.' ('.$authUser->id.')]';
        }
        
        $format = str_replace(
            '[%datetime%] ',
            sprintf('[%%datetime%%] %s ', $authUserID),
            LineFormatter::SIMPLE_FORMAT
            );

        return new LineFormatter($format, 'Y-m-d H:i:s.u', true, true);
    }
}