<?php
// src/Concord/ImportBundle/Controller/TrionDumpController.php
namespace Concord\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TrionDumpController
 * Process a data dump archive from Trion, adding new items, verifying/updating old ones, and so forth
 *
 * @package Concord\ImportBundle\Controller
 */
class TrionDumpController extends Controller {

    public function processAction() {
        $parser = $this->get('TrionDumpParser');
        $parser->setDumpFile($this->get('kernel')->getRootdir().'/cache/RiftData.zip'); //TODO Temporary, should be from an input somewhere.
        $parser->test();
        return new Response('<html><body>Hello '.$parser.'!</body></html>');
    }

}