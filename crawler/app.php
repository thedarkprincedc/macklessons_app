<?php
    require_once("./crawlers/macklessons-crawler.php");
    date_default_timezone_set('Europe/Stockholm');
    print("\n--------------------------------------------\n");
    print("*           MackLessons Crawler            *\n");
    print("--------------------------------------------\n");
    function displaystatus($pid, $percent, $text){
        $strLine = "";
        for($i = 0; $i < 10; $i++){
                $strLine .= ($i < round($percent/10, 0, PHP_ROUND_HALF_DOWN)) ? "#" : "-";
        }
        return sprintf("\033[{$pid};1H\033[2K[%s] %s%% %s\r", $strLine, $percent, $text);    
    } 
    
    $command = (!empty($argv[1]))?$argv[1]:null;
    $action = (!empty($_REQUEST["action"]))?$_REQUEST["action"]:$command;
    $bCommandLine = (!(empty($command)))?true:false;

    try{ 
        $crawler = new MacklessonsCrawler(); 
        $crawler->setURL(_MACKLESSONSRADIO_URL_);  
        $crawler->addContentTypeReceiveRule("#text/html#"); 
        $crawler->addURLFollowRule("#(index\/page\/[0-9]+)$# i");
        $crawler->enableCookieHandling(true);
        $crawler->go();
        $crawler->downloadFiles();
    }
    catch(Exception $e){
        print("Error: ". $e->getMessage() . "\n");
    }
    print("\n\n");
?>