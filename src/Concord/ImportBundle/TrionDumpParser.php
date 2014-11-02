<?php

namespace Concord\ImportBundle;

use Symfony\Component\Config\Definition\Exception\Exception;

class TrionDumpParser {
    private $_extract_path;
    private $_extracted_inputs;
    private $_dump_file;
    private $_xml_files;
    private $_current_file;

    public function __construct($logger) {
        $this->_extract_path = '/var/www/rift/tmp/riftdump'.time();
        $this->_logger = $logger;
    }

    public function __toString() {
        return $this->_dump_file;
    }

    //Take our dump file and build an array of xml files from it. We're going to be trusting of our input.
    private function _findXMLFiles() {
        $this->_xml_files = $this->_extracted_inputs = array();
        if(!$this->_dump_file || !is_readable($this->_dump_file)) return;
        //Look for XML files. Search directories, expand archives, and examine files. We could make this more broad. For now we're just going to grab the "data" files.
        $recursive_xml_search = function($input) use (&$recursive_xml_search) {
            $skip_dirs = array('.', '..', 'addon', 'assets', 'gdc', 'Souls');
            if(is_dir($input)) {
                if($dir = opendir($input)) {
                    while (false !== ($entry = readdir($dir))) {
                        if(!in_array($entry, $skip_dirs)) $recursive_xml_search($input.DIRECTORY_SEPARATOR.$entry);
                    }
                    closedir($dir);
                }
            }
            elseif(substr($input, strlen($input) - 4) == '.zip') {
                if(in_array($input, $this->_extracted_inputs)) return;
                $this->_extracted_inputs[] = $input;
                $archive = new \ZipArchive;
                if($input == $this->_dump_file) {
                    $destination = $this->_extract_path;
                    var_dump(mkdir($destination));
                }
                else $destination = dirname($input);
                $this->_logger->info("Trying to put $input at $destination");
                if($archive->open($input) === TRUE && $archive->extractTo($destination) === TRUE) {
                    $this->_logger->info("Put $input at $destination");
                    $recursive_xml_search($destination);
                    $archive->close();
                }
            }
            elseif(substr($input, strlen($input) - 4) == '.xml') $this->_xml_files[] = $input;

        };
        $recursive_xml_search($this->_dump_file);
    }

    private function _parseXMLFiles() {
        /*
         * ArtifactCollection =>
         * array(
         *  'Id'   => blablabla
         *  'Name' => array(
         *          English => $engname
         *          French => $frname,
         *          German => $dename,
         *  )
         * )
         */
        $myparse = function($xmlr) use(&$myparse) {
            $node = array();
            while($xmlr->read()) {
                if($xmlr->depth == 0) continue; //Skip the root tag.
                if($xmlr->nodeType == \XMLReader::ELEMENT) {
                    if($xmlr->isEmptyElement) {
                        var_dump($xmlr->name);
                        continue;
                    } //Skip self closing tags. With these dumps, this should only be <deprecated/> tags
                    $data = $myparse($xmlr);
                    if(!array_key_exists($xmlr->name, $node)) { //This node doesnt exist in our record. Easy, add it.
                        $node[$xmlr->name] = $data;
                    }
                    else { //This node does exist, duplicate tags oh noes. Whelp, lets see what to do about it
                        if(is_array($node[$xmlr->name])) $node[$xmlr->name][] = $data; //Append to array
                        else $node[$xmlr->name] = array($node[$xmlr->name], $data); //Make it an array. This might not handle all cases right.
                    }
                    if($xmlr->depth == 1) { //If we've gotten this far, we're just finished processing an entire record (e.g. one <Quest>)
                        $this->_processElement($node);
                        $node = array(); //Empty the node, so we dont try and add more Quests to it

                        //Pass $node off to data processor here, its a complete element.
                        //Its possible we want to manually do one of the things below to clear memory, not clear
                        //$before = round(memory_get_usage()/1024);
                        //unset($node);
                        //gc_collect_cycles();
                        //$node = null;
                        //gc_collect_cycles();
                        //$node = array();
                        //$after = round(memory_get_usage()/1024);
                        //$this->_logger->info('Before '.$before);
                        //$this->_logger->info('After '.$after);
                        //$this->_logger->info('Process '.round(memory_get_usage()/1024));
                        //if(memory_get_usage() > 12000000) die;
                        //$node = null;
                        //unset($node);
                        //gc_collect_cycles();
                        //$node = array();
                        //echo "Process ".print_r($node, true);
                        //echo "<br/>";
                    }

                }
                elseif($xmlr->nodeType == \XMLReader::END_ELEMENT) {
                    return isset($value) ? $value : $node;

                }
                elseif($xmlr->nodeType == \XMLReader::TEXT) {
                    $value = $xmlr->value;
                }
            }
        };
        //echo phpinfo(); die;
        ignore_user_abort(true);
        foreach($this->_xml_files as $xml_file) {
            $this->_current_file = str_replace($this->_extract_path, '', $xml_file);
            $xml = new \XMLReader();
            $xml->open($xml_file);
            $myparse($xml);
            $xml->close();
        }
        unset($this->_current_file);
    }

    private function _processElement($node) {
        //print_r($node); echo "<br/><br/>";
        $tmp = array_keys($node);
        $key = array_pop($tmp);
        $node = array_pop($node);
        //print_r($node); die;
        $record = array('type' => $key, 'record' => json_encode($node));
        $this->_logger->info(json_encode($node));
        //print_r($record); die;
        global $db;
        //$inserted = $db->query("INSERT INTO `discoveries` (`type`, `record`, `authenticity`) VALUES ('{$record['type']}', '{$record['record']}', 100)");
        die;

        //$this->_logger->info('Process '.memory_get_usage());
        //print_r($element); echo "<br/><br/>";
    }

    public function test() {
        //echo phpinfo(); die;
        global $db;
        $db = new \PDO('mysql:dbname=rift;host;127.0.0.1', 'rift', 'dZFgGH4jTdtDBSZ');
        $ab = 0;
        set_time_limit(0);
        $this->_logger->info('Start');
        //$this->_findXMLFiles();
        //return;
        //$original_limit = ini_get('memory_limit');
        ini_set('memory_limit', '768M');
        ///var/www/rift/tmp/riftdump1412289801/data/Achievements.xml
        $this->_xml_files[] = '/var/www/rift/tmp/riftdump1412289801/data/Quests.xml';
        global $columns;
        $columns = [];
        //$this->_xml_files[] = '/var/www/rift/tmp/riftdump1412289801/data/Items.xml';
        $this->_parseXMLFiles();
        print_r($columns);
        //echo 'mem'; die;
        //echo memory_get_usage()."b<br/>";
        $this->_logger->info('End');
        ////print_r($this->_output);
        //ini_set('memory_limit', $original_limit);
        return;
    }

    /**
     * Set the source for our xml files. It should be an archive, a directory, or an xml file.
     * @param string $dump_file
     */
    public function setDumpFile($dump_file) {
        if(is_readable($dump_file)) $this->_dump_file = $dump_file;
    }

    /**
     * @return string
     */
    public function getDumpFile() {
        return $this->_dump_file;
    }
}