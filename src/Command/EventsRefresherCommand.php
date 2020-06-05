<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EventsRefresherCommand extends Command
{
    protected static $defaultName = 'app:events:refresh';
    
    private $client;
    
    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
        
    }
    
    protected function configure()
    {
        $this
            ->setDescription('This command refresh events from API');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = $this->client->request('POST', 'http://127.0.0.1:8000/api/login_check',[
            'json' => [
                'username' => 'user1@mail.ru',
                'password' => "qwerty"
            ]
        ]);
        
        try {
            $jwt = $request->getContent();
            $jwt = json_decode($jwt, true);
            $jwt = $jwt['token'];
            
        } catch (\Exception $e) {
            dd($e);
        }
        
        $request = $this->client->request('POST', 'http://127.0.0.1:8000/api//json-rpc',[
            'json' => [
                [                    
                    "jsonrpc" => "2.0",
                    "method" => "events",
                    "params" => [],
                    "id" => 1
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);
        
        try {
            $events = $request->getContent();
            $events = json_decode($events, true);
            $events = array_shift($events)['result'];
        } catch (\Exception $e) {
            dd($e);
        }
        
        if(!$events)
            $output->writeln('No new events');
        
        if(isset($events['canceled_ticket'])){
            // Ставим задание на обновление статусов рейсов
        }
        
        if(isset($events['canceled_flight'])){
            // ставим задания на отправку писем
        }
    }
}
