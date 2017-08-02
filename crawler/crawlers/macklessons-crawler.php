<?php
require_once("vendor/autoload.php");
require_once("constants.php");
require_once("mongo-database.php");
class MacklessonsCrawler extends PHPCrawler 
{ 
    public $dataObject;
    public $detailArray;
    public $processed;
    public $exceptions = array();
    public $host_name;
    public $fp;
    public $episodeModel;
    function __construct() {
        if(!is_dir("./tmp")){
            print("Create the tmp directory");
            mkdir("./tmp");                
        }else{
            $this->clearTemporaryFiles();
        }
        if(!is_dir("./download")){
            print("Create the download directory");
            mkdir("./download");                
        }
        $this->episodeModel = new EpisodeModel();
        parent::__construct(); 
    }
    function setURL($url){
        print("host url->{$url}");
        $this->host_name = $url;
        parent::setURL($url);
    }
    function getEpisodeList(){
        return $this->dataObject;
    }
    function clearTemporaryFiles(){
        foreach (glob("./tmp"."/*.*") as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }
    }
    function buildDownloadList(){
        $downloadObj = new stdClass();
        $downloadObj->list = [];
        $downloadObj->dest = [];
        array_walk_recursive($this->dataObject->list, 
            function($a) use (&$downloadObj) { 
                if($a->imageurl !== ""){
                    $imageSplit = explode(".", $a->imageurl);
                    $downloadObj->list[] = $a->imageurl;
                    $downloadObj->dest[] = _DOWNLOAD_LOCATION_.$a->title.".".end($imageSplit);
                    $downloadObj->final[] = "./download/".$a->title.".".end($imageSplit);
                    $downloadObj->metadata[]= $a;
                }
                if($a->audiourl !== ""){
                     $audioSplit = explode(".", $a->audiourl);
                    $downloadObj->list[] = $a->audiourl;
                    $downloadObj->dest[] =  _DOWNLOAD_LOCATION_.$a->title.".".end($audioSplit);
                     $downloadObj->final[] = "./download/".$a->title.".".end($audioSplit);
                     $downloadObj->metadata[]= $a;
                }
        });
        return $downloadObj;
    }
    function handleDocumentInfo(PHPCrawlerDocumentInfo $PageInfo) 
    { 
        echo "\n".$PageInfo->url." ";
        echo "Progress :      ";  // 5 characters of padding at the end
        $html = new SimpleHtmlDom\simple_html_dom();
        $html->load($PageInfo->content);
        $total = count($html->find('.post'));
        foreach($html->find('.post') as $index => $article) {
            
            $tempArr = new stdClass();
            $tempArr->title = str_replace(["Episiode","Episode#"], "Episode #",str_replace(["&quot;","09&#039;","&#039;"],"",trim($article->find("h2 > a", 0)->title)));                            
            $tempArr->date =  trim($article->find("small", 0)->plaintext); 
            $tempArr->date = date('Y-m-d H:i:s',strtotime($tempArr->date ." UTC"));
            $tempArr->imageurl = "";
            $tempArr->audiourl = "";
            if($im = $article->find("div > img", 0)){
                $tempArr->imageurl = $this->host_name . "/" . $im->src;
            }
            if($ia = $article->find(".postcontent > a", 0)){
                $tempArr->audiourl = $ia->href;
            }
            if($iaa = $article->find(".postcontent > a", 1)){
                $tempArr->audiourl == $iaa->href;
            }
            $tempArr->hash = md5($tempArr->title + $tempArr->date);  
            $this->dataObject->list[] = $tempArr;
            echo "\033[5D";      // Move 5 characters backward
            echo str_pad(round((($index+1)/$total)*100), 3, ' ', STR_PAD_LEFT) . " %";    // Output is always 5 characters long  
        }  
    } 

    function curl_handler_recv($res, $data){
        $len = fwrite($this->fp, $bytes);
        echo $len;
        // if you want, you can use any progress printing here
        return $len;
    }
    function downloadFiles(){
        $downloadList = $this->buildDownloadList();
        $list = array_chunk($downloadList->list, count($downloadList->list)/_NUMBER_OF_PROCESSES_+1);
        $dest = array_chunk($downloadList->dest, count($downloadList->dest)/_NUMBER_OF_PROCESSES_+1);
        $final = array_chunk($downloadList->final, count($downloadList->final)/_NUMBER_OF_PROCESSES_+1);
        $metadata = array_chunk($downloadList->metadata, count($downloadList->metadata)/_NUMBER_OF_PROCESSES_+1);
        $process = function($pid, &$data, &$data2, &$data3, &$meta){
            $total = count($data);
            $percent = 0;
            
            foreach($data as $index => $value){ 
                $percent = round( ((($index+1)/$total)*100), 0, PHP_ROUND_HALF_DOWN);
                print(displaystatus($pid, $percent, $value));
                if(!is_readable($data3[$index])){
                    if(!@copy($value, $data2[$index])){
                        //printf("Error Copying - %s\n", $audioLoc);
                    }else{
                        rename($data2[$index], $data3[$index]);
                        $this->episodeModel->addEpisode($meta[$index]);
                    }
                }
            }
        };
        print("\033[2J");
        for($i = 1; $i <= _NUMBER_OF_PROCESSES_; ++$i){
            $pid = pcntl_fork();           
            if (!$pid) { 
                sleep(1);               
                $process($i, $list[$i-1], $dest[$i-1], $final[$i-1], $metadata[$i-1]);  
                exit($i); 
            } 
        }
        while (pcntl_waitpid(0, $status) != -1) { 
            $status = pcntl_wexitstatus($status); 
            //echo "Child $status completed\n"; 
        }
        print("\n\n");
    }
} 
?>