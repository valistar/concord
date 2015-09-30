<?php

namespace Concord\ImportBundle;

use Concord\BrowseBundle\Entity;
use Symfony\Component\Config\Definition\Exception\Exception;

class TrionDumpParser {
    private $_extract_path;
    private $_extracted_inputs;
    private $_dump_file;
    private $_xml_files;
    private $_current_file;

    public function __construct($logger, $em) {
        $this->_extract_path = '/var/www/rift/tmp/riftdump'.time();
        $this->_logger = $logger;
        $this->_em = $em;
        //var_dump($em);
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
            //TODO I'm getting arrays for Zone. Why? Always seems to be the same zone twice.
            //TODO I'm getting arrays for Faction, always with Order of Mathos. Why? Also a few Guardian/Defiant.
            //TODO Givers can sometimes be items, but are sitll marked as 'npcs'
            //TODO What about complters that are popups?
            $node = array();
            while($xmlr->read()) {
                if($xmlr->depth == 0) continue; //Skip the root tag.
                if($xmlr->nodeType == \XMLReader::ELEMENT) {
                    if($xmlr->isEmptyElement) continue; //Skip self closing tags. With these dumps, this should only be <deprecated/> tags
                    $data = $myparse($xmlr);
                    if(is_array($data) && count($data) == 0) $data = null; //Empty tags should be null.
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
        global $db;
        $record = array('type' => $key, 'record' => json_encode($node));
        global $columns;
        //$columns = array_unique(array_merge($columns, array_keys($node)));
        //var_dump($node);
        //$this->_logger->info(json_encode($node));
        //print_r($record); die;
        $stmt = $db->prepare("INSERT INTO `discoveries` (`type`, `record`, `authenticity`) VALUES ('{$record['type']}', :record, 100)");
        $inserted = $stmt->execute(array(':record' => $record['record']));
        //die;

        //$this->_logger->info('Process '.memory_get_usage());
        //print_r($element); echo "<br/><br/>";
    }

    public function test() {
        global $columns;
        $columns = [];
        global $db;
        $db = new \PDO('mysql:dbname=rift;host;127.0.0.1', 'rift', 'dZFgGH4jTdtDBSZ');
        set_time_limit(0);
        $this->_logger->info('Start');
        //$this->_findXMLFiles();
        ini_set('memory_limit', '400M');
        ///var/www/rift/tmp/riftdump1412289801/data/Achievements.xml
        $this->_xml_files[] = '/var/www/rift/tmp/riftdump1412289801/data/Quests.xml';
        $this->_xml_files[] = '/var/www/rift/tmp/riftdump1412289801/data/NPCs.xml';
        //$this->_xml_files[] = '/var/www/rift/tmp/riftdump1412289801/data/Items.xml';
        //$this->_parseXMLFiles();
        //print_r($columns);
        $this->processDiscoveries();
        $this->_logger->info('End');
        return;
    }

    function processDiscoveries() {
        global $db;
        $count = 0;
        $discoveries = $db->query('SELECT * FROM `discoveries` WHERE processed = 0');
        $repo['Quest'] = $this->_em->getRepository('ConcordBrowseBundle:Quest');
        $repo['NPC'] = $this->_em->getRepository('ConcordBrowseBundle:NPC');
        $processed = [];
        foreach($discoveries as $data) {
            $discovery = json_decode($data['record'], true);
            //print_r($discovery);
            switch($data['type']) {
                case 'Quest':
                    $quest = $repo['Quest']->find($discovery['QuestId']);
                    if(!$quest) $quest = new \Concord\BrowseBundle\Entity\Quest($discovery['QuestId']); //Hmm. Why do I need this? Why doesnt 'use' work?
                    $this->_em->persist($quest);

                    if(!array_key_exists('AddonId', $discovery)) {
                        $discovery['AddonId'] = '';
                    }
                    if(!array_key_exists('Name', $discovery) || !$discovery['Name']['English']) {
                        $discovery['Name'] = array('English' => '', 'French' => '', 'German' => '');
                    }
                    if(array_key_exists('Level', $discovery)) {
                        $quest->setLevel($discovery['Level']);
                    }
                    if(array_key_exists('Zone', $discovery)) {
                        if(is_array($discovery['Zone'])) {
                            $discovery['Zone'] = array_pop($discovery['Zone']); //Same zone twice.
                        }
                        $quest->setZone($discovery['Zone']);
                    }
                    if(!array_key_exists('Scene', $discovery)) {
                        $discovery['Scene'] = array();
                    }
                    if(array_key_exists('Scope', $discovery)) {
                        $quest->setScope($discovery['Scope']);
                    }
                    if(!array_key_exists('Type', $discovery)) {
                        $discovery['Type'] = array();
                    }
                    if(!array_key_exists('Events', $discovery)) {
                        $discovery['Events'] = array();
                    }
                    if(array_key_exists('CanShare', $discovery)) {
                        $quest->setCanShare($discovery['CanShare']);
                    }
                    if(!array_key_exists('ShortDescription', $discovery)) {
                        $discovery['ShortDescription'] = array();
                    }
                    if(!array_key_exists('Objectives', $discovery)) {
                        $discovery['Objectives'] = array();
                    }
                    if(array_key_exists('Repeatable', $discovery)) {
                        $quest->setRepeatable($discovery['Repeatable']);
                    }
                    if(!array_key_exists('Rewards', $discovery)) {
                        $discovery['Rewards'] = array();
                    }
                    if(!array_key_exists('RepeatRewards', $discovery)) {
                        $discovery['RepeatRewards'] = array();
                    }
                    if(!array_key_exists('FirstCompletedBy', $discovery)) {
                        $discovery['FirstCompletedBy'] = array();
                    }
                    if(array_key_exists('SecondsToComplete', $discovery)) {
                        $quest->setSecondsToComplete($discovery['SecondsToComplete']);
                    }
                    if(!array_key_exists('LongDescription', $discovery)) {
                        $discovery['LongDescription'] = array();
                    }
                    if(!array_key_exists('Denouement', $discovery)) {
                        $discovery['Denouement'] = array();
                    }
                    if(!array_key_exists('ObjectivesCompleteText', $discovery)) {
                        $discovery['ObjectivesCompleteText'] = array();
                    }
                    if(array_key_exists('Faction', $discovery)) {
                        if(is_array($discovery['Faction'])) {
                            if(in_array('Defiant', $discovery['Faction']) && in_array('Guardian', $discovery['Faction'])) $discovery['Faction'] = null; //Both, same as null.
                            else $discovery['Faction'] = array_pop($discovery['Faction']); //X + Order of Mathos, we dont care about Mathos.
                        }
                        $quest->setFaction($discovery['Faction']);
                    }
                    if(array_key_exists('GuildLevel', $discovery)) {
                        $quest->setGuildLevel($discovery['GuildLevel']);
                    }

                    $associations = false;
                    $associated = false;
                    $givers = array();
                    $completers = array();
                    $require = array();
                    if(array_key_exists('Givers', $discovery) && count($discovery['Givers'])) {
                        $associations = true;
                        if(is_array($discovery['Givers']['NPCId'])) {
                            foreach($discovery['Givers']['NPCId'] as $npc) {
                                $givers[] = $npc;
                            }
                        }
                        else {
                            $givers[] = $discovery['Givers']['NPCId'];
                        }
                    }
                    if(array_key_exists('Completers', $discovery) && count($discovery['Completers'])) {
                        $associations = true;
                        if(is_array($discovery['Completers']['NPCId'])) {
                            foreach($discovery['Completers']['NPCId'] as $npc) {
                                $completers[] = $npc;
                            }
                        }
                        else {
                            $completers[] = $discovery['Completers']['NPCId'];
                        }
                    }
                    if(array_key_exists('RequireOnNone', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireOnNone']['QuestId'])) {
                            foreach($discovery['RequireOnNone']['QuestId'] as $required) {
                                $require['RequireOnNone'][] = $required;
                            }
                        }
                        else {
                            $require['RequireOnNone'][] = $discovery['RequireOnNone']['QuestId'];
                        }
                    }
                    if(array_key_exists('RequireAll', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireAll']['QuestId'])) {
                            foreach($discovery['RequireAll']['QuestId'] as $required) {
                                $require['RequireAll'][] = $required;
                            }
                        }
                        else {
                            $require['RequireAll'][] = $discovery['RequireAll']['QuestId'];
                        }
                    }
                    if(array_key_exists('RequireOnAll', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireOnAll']['QuestId'])) {
                            foreach($discovery['RequireOnAll']['QuestId'] as $required) {
                                $require['RequireOnAll'][] = $required;
                            }
                        }
                        else {
                            $require['RequireOnAll'][] = $discovery['RequireOnAll']['QuestId'];
                        }
                    }
                    if(array_key_exists('RequireNone', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireNone']['QuestId'])) {
                            foreach($discovery['RequireNone']['QuestId'] as $required) {
                                $require['RequireNone'][] = $required;
                            }
                        }
                        else {
                            $require['RequireNone'][] = $discovery['RequireNone']['QuestId'];
                        }
                    }
                    if(array_key_exists('RequireAny', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireAny']['QuestId'])) {
                            foreach($discovery['RequireAny']['QuestId'] as $required) {
                                $require['RequireAny'][] = $required;
                            }
                        }
                        else {
                            $require['RequireAny'][] = $discovery['RequireAny']['QuestId'];
                        }
                    }
                    if(array_key_exists('RequireOnAny', $discovery)) {
                        $associations = true;
                        if(is_array($discovery['RequireOnAny']['QuestId'])) {
                            foreach($discovery['RequireOnAny']['QuestId'] as $required) {
                                $require['RequireOnAny'][] = $required;
                            }
                        }
                        else {
                            $require['RequireOnAny'][] = $discovery['RequireOnAny']['QuestId'];
                        }
                    }
                    $quest->setAddonId($discovery['AddonId']);
                    $quest->setNameEN($discovery['Name']['English']);
                    $quest->setNameFR($discovery['Name']['French']);
                    $quest->setNameDE($discovery['Name']['German']);
                    $quest->setScene($discovery['Scene']);
                    $quest->setType($discovery['Type']);
                    $quest->setEvents($discovery['Events']);
                    $quest->setRewards($discovery['Rewards']);
                    $quest->setRepeatRewards($discovery['RepeatRewards']);
                    $quest->setShortDescription($discovery['ShortDescription']);
                    $quest->setLongDescription($discovery['LongDescription']);
                    $quest->setDenouement($discovery['Denouement']);
                    $quest->setObjectives($discovery['Objectives']);
                    $quest->setObjectivesCompleteText($discovery['ObjectivesCompleteText']);
                    $quest->setFirstCompletedBy($discovery['FirstCompletedBy']);
                    if($associations) {
                        $associated = true;
                        foreach($givers as $giver) {
                            //TODO Check out item drop givers and world item givers.
                            $npc = $repo['NPC']->find($giver);
                            if($npc) {
                                $quest->addGiver($npc);
                            }
                            else {
                                $associated = false;
                            }
                        }
                        foreach($completers as $completer) {
                            //TODO Check out item drop completers and world item completers.
                            $npc = $repo['NPC']->find($completer);
                            if($npc) {
                                $quest->addGiver($npc);
                            }
                            else {
                                $associated = false;
                            }
                        }
                        foreach($require as $category => $ids) {
                            foreach($ids as $id) {
                                $require = $repo['Quest']->find($id);
                                if($require) {
                                    $adder = 'add'.$category;
                                    $quest->$adder($require);
                                }
                                else {
                                    $associated = false;
                                }
                            }
                        }
                    }
                    if(!$associations || $associated) {
                        $processed[] = $data['id'];
                    }
                    break;
                case 'NPC':
                    $npc = $repo['NPC']->find($discovery['Id']);
                    if(!$npc) $npc = new \Concord\BrowseBundle\Entity\NPC($discovery['Id']); //Hmm. Why do I need this? Why doesnt 'use' work?
                    $this->_em->persist($npc);

                    if(!array_key_exists('PrimaryName', $discovery) || !$discovery['PrimaryName']['English']) {
                        $discovery['PrimaryName'] = array('English' => '', 'French' => '', 'German' => '');
                    }

                    $npc->setNameEN($discovery['PrimaryName']['English']);
                    $npc->setNameDE($discovery['PrimaryName']['German']);
                    $npc->setNameFR($discovery['PrimaryName']['French']);
                    $npc->setTitle();
                    $npc->setLevel();
                    $npc->setZone();
                    $npc->setScene();
                    $npc->setCategory();
                    $npc->setPortrait();
                    $npc->setType();
                    $npc->setPlane();
                    $npc->setRelationships();
                    $npc->setNotorietyRewards();
                    $npc->setSells();
                    $npc->setSkillTrainer();
                    $npc->setRoleTrainer();
                    $npc->setAddonId($discovery['AddonType']);
                    $npc->setFirstCompletedBy();

                    break;
            }
            if($count % 20 == 0) {
                $this->_em->flush();
                $this->_em->clear();
                if(count($processed)) {
                    $processed = implode(', ', $processed);
                    $db->query("UPDATE `discoveries` SET `processed` = 1 WHERE id IN ($processed)");
                    $processed = [];
                }

            }
        }
        $this->_em->flush();
        $this->_em->clear();
        if(count($processed)) {
            $processed = implode(', ', $processed);
            $db->query("UPDATE `discoveries` SET `processed` = 1 WHERE id IN ($processed)");
            $processed = [];
        }
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