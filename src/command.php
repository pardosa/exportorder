<?php namespace App;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use OzdemirBurak\JsonCsv\File\Json;
use Symfony\Component\Filesystem\Filesystem;

use App\Entity\Order;

class Command extends SymfonyCommand
{
    
    public function __construct()
    {
        parent::__construct();
    }
	
	protected function convertJSON(InputInterface $input, OutputInterface $output)
    {
		// $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465))
		// ->setUsername('sipipin123@gmail.com')
		// ->setPassword('Merdeka123!')
		// ;
		// $mailer = new \Swift_Mailer($transport);
		// $message = (new \Swift_Message('Wonderful Subject'))
		// 	->setFrom(['john@doe.com' => 'John Doe'])
		// 	->setTo(['sipipin123@gmail.com', '' => ''])
		// 	->setBody('Here is the message itself')
		// 	;
		
		// $result = $mailer->send($message);

		var_dump($this->getContainer());die;

		// Download JSON File
		$this->getJSONFile();
		
		// JSON to CSV
		$jsonFile = __DIR__ . '\orders.json';
		$this->jsonToCsv($jsonFile, __DIR__ . '/' . $input->getArgument('outputfile'));	
		
    }
	
	private function getJSONFile()
    {
		$headers = array('Accept' => 'application/json');
				
		$response = \Unirest\Request::get('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl',$headers);
				
		$fs = new Filesystem();
		try {
			$fs->dumpFile(__DIR__ . '/orders.json', $response->raw_body);
		}
		catch(IOException $e) {
			
		}
    }
	
	function jsonToCsv ($jsonFile, $csvFilePath = false) {

		$jsonFile = fopen($jsonFile, 'r');

		$firstLineKeys = false;
		$f = fopen($csvFilePath,'w+');
		while(($json = fgets($jsonFile)) !== false) {
			$array = json_decode($json, true);

			//Exclude order with 0 items ()
			if (count($array["items"] > 0)){
				$order = new Order($array["order_id"], $array["order_date"], $array["discounts"], $array["shipping_price"], $array["items"]);
				$order->setCustomer($array["customer"]);
				
				if (!$firstLineKeys)
				{
					$firstLineKeys = ["order_id", "order_datetime", "total_order_value", "average_unit_price", "distinct_unit_count", "total_units_count", "customer_state"];
					fputcsv($f, $firstLineKeys);
					$firstLineKeys = true; 
				}
				
				$arraycsv = [];
				$arraycsv[] = $order->getOrderId();
				$arraycsv[] = $order->getOrderDate();
				$arraycsv[] = $order->getTotalOrderValue();
				$arraycsv[] = $order->getAvarageUnitPrice();
				$arraycsv[] = $order->countDistinctUnit();
				$arraycsv[] = $order->getTotalUnit();
				$arraycsv[] = $order->getCustomer()->getShippingState();

				fputcsv($f, $arraycsv);
			}
			
	 	}
	 	fclose($f);
	}
}