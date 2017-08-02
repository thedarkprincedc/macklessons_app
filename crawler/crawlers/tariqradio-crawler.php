<?php
    require_once("vendor/autoload.php");
    require_once("constants.php");
    require_once("mongo-database.php");
    class TariqradioCrawler extends PHPCrawler {
        function _construct(){
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
        }
        function setURL($url){

        }
        function getEpisodeList(){

        }
        function clearTemporaryFiles(){

        }
        function buildDownloadList(){

        }
        function handleDocumentInfo(PHPCrawlerDocumentInfo $PageInfo){

        }
        function curl_handler_recv($res, $data){
        
        }
        function downloadFiles(){
        }
    } 
?>