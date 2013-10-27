<?php

error_reporting(0);
$flag = 0;

function createURL($thicker){

$startDay = 01;
$startMonth = 01;
$startYear = 2002;

$endDay = 31;
$endMonth = 01;
$endYear = 2013;

$a = $startMonth - 1; $b = $startDay; $c = $startYear; $d = $endMonth - 1; $e = $endDay; $f = $endYear;

	return "http://ichart.finance.yahoo.com/table.csv?s=$thicker&a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=d&ignore=.csv";
	
	// return "http://finance.yahoo.com/q/hp?s=$thicker+Historical+Prices" //url for all available data upto day
}

function getCSVFile($url, $outputFile, $companyTic){
global $flag;
	$content = file_get_contents($url);
	if ($content === FALSE){
		$flag = 1;
		echo $companyTic . " " . "not valid!" . "<br />";
		goto a;
	}
	$content = str_replace("Date,Open,High,Low,Close,Volume,Adj Close","", $content);
	$content = trim($content);
	file_put_contents($outputFile, $content);
	echo $companyTic . " " . "sucess" . " | ";
	a:
}

function fileToFile($IDFile, $txtFile, $firmId){
	$file1 = fopen($txtFile,"r");
	$file2 = fopen($IDFile,"w");
	
		while(!feof($file1)){
			$line = fgetcsv($file1);
			$date = $line[0];
			$close = $line[6];
			$content = array ($firmId, $date, $close);
			fputcsv($file2, $content, ";");
		  }

	echo $firmId . " " . "sucess" . "<br />";
	fclose($file1);
	fclose($file2);
	
}


function main(){
global $flag;
	$mainTickerFile = fopen("tickers.txt","r");
	$firmIDFile = fopen("firms.txt","r");
	while((!feof($mainTickerFile)) && (!feof($firmIDFile))){		
		$companyTicker = fgets($mainTickerFile);
		$companyTicker = trim($companyTicker);
		$firmID = fgets($firmIDFile);
		$firmID = trim($firmID);
		
		$fileURL = createURL($companyTicker);
		$companyTxtFile = "txtFiles/".$companyTicker.".csv";
		$companyIDFile = "csvFiles/".$firmID.".csv";
		
		getCSVFile($fileURL, $companyTxtFile, $companyTicker);
		if ($flag === 0){
		fileToFile($companyIDFile, $companyTxtFile, $firmID);
		}
		$flag = 0;
	}
	fclose($mainTickerFile);
	fclose($firmIDFile);

//*** shell command	
//exec('copy C:\wamp\www\StockDownloader\csvFiles\*.csv C:\wamp\www\StockDownloader\csvFiles\firm_data.csv'); 

echo "sueessfully completed!";
}

main();

?>
