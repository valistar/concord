<?php
// src/Concord/ImportBundle/Command/QuestFinderCommand.php
namespace Concord\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QuestFinderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:qf');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '400M');
        $offset = 0;
        $limit = 80000;
        $zoneids = array(
            'Draumheim' => 'z0000012E087E78E1',
            'Goboro Reef' => 'z0000012D6EEBB377',
            'Tarken Glacier' => 'z0000012F14279B5A',
            'Pelladane' => 'z1C938C07F41C83CC',
            'Ashora' => 'z2F1E4708BEC6A608',
            'Kingsward' => 'z4D8820D7EF52685C',
            'Ardent Domain' => 'z563CB77E4A32233F',
            'City Core' => 'z754553DD46F46371',
            'Eastern Holdings' => 'z48530386ED2EA5AD',
            'Cape Jule' => 'z698CB7B72B3D69E9',
            'Seratos' => 'z59124F7DD7F15825',
            'Morban' => 'z39095BA75AD7DC03',
            'Steppes of Infinity' => 'z2F9C9E1FF91F9293',
            'Tempest Bay' => 'z11173F9D259DAADE',
            'Sanctum' => 'z487C9102D2EA79BE',
            'Silverwood' => 'z0000000CB7B53FD7',
            'Moonshade Highlands' => 'z0000001804F56C61',
            'Gloamwood' => 'z0000001B2BB9E10E',
            'Iron Pine Peak' => 'z00000016EB9ECBA5',
            'Scarlet Gorge' => 'z019595DB11E70F58',
            'Stillmoor' => 'z0000001A4AF8CD7A',
            'Scarwood Reach' => 'z000000142C649218',
            'Shimmersand' => 'z000000069C1F0227',
            'Droughtlands' => 'z1416248E485F6684',
            'Ember Isle' => 'z76C88A5A51A38D90',
            'Meridian' => 'z6BA3E574E9564149',
            'Freemarch' => 'z00000013CAF21BE3',
            'Stonefield' => 'z585230E5F68EA919',
            'The Dendrome' => 'z10D7E74AB6D7B293',
        );
        $zonenames = array_flip($zoneids);
        $qfdata = array();
        $repo = $this->getContainer()->get('doctrine')->getManager()->getRepository('ConcordBrowseBundle:Quest');
        $query = $repo->createQueryBuilder('q')
            //->where("q.zone = 'Goboro Reef'")
            ->andWhere("(q.scope NOT LIKE '%Instant Adventure%' OR q.scope IS NULL)")
            ->andWhere("q.repeatable = 'Never'")
            ->setFirstResult($offset)
            ->setMaxResults($limit)->getQuery();
        //$output->writeln($query->getSQL());
        $quests = $query->getResult();
        //$em2 = $this->getDoctrine()->getManager();
        //$t = serialize($quests);
        //$quest = unserialize($t);
        foreach($quests as $quest) {
            $zone = $quest->getZone();
            if(array_key_exists($zone, $zoneids)) $zone = $zoneids[$zone];
            $faction = $quest->getFaction();
            if(!$faction) $faction = 'Both';
            //TODO Other faction cases
            $id = substr($quest->getAddonId(), 0, 9);
            if(!array_key_exists($zone, $qfdata)) {
                $qfdata[$zone] = array('Total' => 0, 'Carnages' => 0, 'Both' => array(), 'Guardian' => array(), 'Defiant' => array());
            }
            $qfdata[$zone]['Total']++;
            if(in_array('Carnage', $quest->getType())) $qfdata[$zone]['Carnages']++;
            //$output->writeln(print_r($quest->getType(), true));
            $qfdata[$zone][$faction][$id] = array('QuestId' => $quest->getId(), 'AddonId' => $quest->getAddonId(), 'NPCStart' => '', 'ItemStart' => '');


        }
        $out = '';
        foreach($qfdata as $zoneid => $zone) {
            $out .= "\t[\"{$zoneid}\"] = {";
            if(array_key_exists($zoneid, $zonenames)) $out .= " -- ".$zonenames[$zoneid];
            $out .= "\n\t\tTotal = {$zone['Total']},\n\t\tCarnages = {$zone['Carnages']},\n\t\tBoth = {\n";
            foreach($zone['Both'] as $id => $quest) {
                $out .= "\t\t\t$id = {\n\t\t\t\tQuestId = \"{$quest['QuestId']}\",\n\t\t\t\tAddonId = \"{$quest['AddonId']}\",\n\t\t\t\tNPCStart = \"{$quest['NPCStart']}\",\n\t\t\t\tItemStart = \"{$quest['ItemStart']}\",\n\t\t\t\t},\n";
            }
            $out .= "\t\t},\n\t\tGuardian = {\n";
            foreach($zone['Guardian'] as $id => $quest) {
                $out .= "\t\t\t$id = {\n\t\t\t\tQuestId = \"{$quest['QuestId']}\",\n\t\t\t\tAddonId = \"{$quest['AddonId']}\",\n\t\t\t\tNPCStart = \"{$quest['NPCStart']}\",\n\t\t\t\tItemStart = \"{$quest['ItemStart']}\",\n\t\t\t\t},\n";
            }
            $out .= "\t\t},\n\t\tDefiant = {\n";
            foreach($zone['Defiant'] as $id => $quest) {
                $out .= "\t\t\t$id = {\n\t\t\t\tQuestId = \"{$quest['QuestId']}\",\n\t\t\t\tAddonId = \"{$quest['AddonId']}\",\n\t\t\t\tNPCStart = \"{$quest['NPCStart']}\",\n\t\t\t\tItemStart = \"{$quest['ItemStart']}\",\n\t\t\t\t},\n";
            }
            $out .= "\t\t},\n\t},";
        }
        $output->writeln($out);
    }
}