<?php

namespace Concord\BrowseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quest
 */
class Quest
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $addonId;

    /**
     * @var string
     */
    private $nameEN;

    /**
     * @var string
     */
    private $nameFR;

    /**
     * @var string
     */
    private $nameDE;

    /**
     * @var integer
     */
    private $level = 0;

    /**
     * @var string
     */
    private $zone;

    /**
     * @var array
     */
    private $scene;

    /**
     * @var array
     */
    private $scope;

    /**
     * @var array
     */
    private $type;

    /**
     * @var array
     */
    private $events;

    /**
     * @var boolean
     */
    private $canShare = false;

    /**
     * @var string
     */
    private $repeatable = 'No';

    /**
     * @var array
     */
    private $rewards;

    /**
     * @var array
     */
    private $repeatRewards;

    /**
     * @var integer
     */
    private $secondsToComplete = 0;

    /**
     * @var array
     */
    private $shortDescription;

    /**
     * @var array
     */
    private $longDescription;

    /**
     * @var array
     */
    private $denouement;

    /**
     * @var array
     */
    private $objectives;

    /**
     * @var array
     */
    private $objectivesCompleteText;

    /**
     * @var string
     */
    private $faction;

    /**
     * @var integer
     */
    private $guildLevel;

    /**
     * @var array
     */
    private $firstCompletedBy;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $givers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $completers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireNone;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireOnNone;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireAny;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireOnAny;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireAll;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $requireOnAll;

    /**
     * Constructor
     */
    public function __construct($id)
    {
        $this->givers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->completers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireNone = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireOnNone = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireAny = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireOnAny = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireAll = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requireOnAll = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setId($id);
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Quest
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set addonId
     *
     * @param string $addonId
     * @return Quest
     */
    public function setAddonId($addonId)
    {
        $this->addonId = $addonId;

        return $this;
    }

    /**
     * Get addonId
     *
     * @return string 
     */
    public function getAddonId()
    {
        return $this->addonId;
    }

    /**
     * Set nameEN
     *
     * @param string $nameEN
     * @return Quest
     */
    public function setNameEN($nameEN)
    {
        $this->nameEN = $nameEN;

        return $this;
    }

    /**
     * Get nameEN
     *
     * @return string 
     */
    public function getNameEN()
    {
        return $this->nameEN;
    }

    /**
     * Set nameFR
     *
     * @param string $nameFR
     * @return Quest
     */
    public function setNameFR($nameFR)
    {
        $this->nameFR = $nameFR;

        return $this;
    }

    /**
     * Get nameFR
     *
     * @return string 
     */
    public function getNameFR()
    {
        return $this->nameFR;
    }

    /**
     * Set nameDE
     *
     * @param string $nameDE
     * @return Quest
     */
    public function setNameDE($nameDE)
    {
        $this->nameDE = $nameDE;

        return $this;
    }

    /**
     * Get nameDE
     *
     * @return string 
     */
    public function getNameDE()
    {
        return $this->nameDE;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Quest
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return Quest
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set scene
     *
     * @param array $scene
     * @return Quest
     */
    public function setScene($scene)
    {
        if(!is_array($scene)) $scene = array($scene);
        $this->scene = $scene;

        return $this;
    }

    /**
     * Get scene
     *
     * @return array 
     */
    public function getScene()
    {
        return $this->scene;
    }

    /**
     * Set scope
     *
     * @param array $scope
     * @return Quest
     */
    public function setScope($scope)
    {
        if(!is_array($scope)) $scope = array($scope);
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set type
     *
     * @param array $type
     * @return Quest
     */
    public function setType($type)
    {
        if(!is_array($type)) $type = array($type);
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return array 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set events
     *
     * @param array $events
     * @return Quest
     */
    public function setEvents($events)
    {
        if(!is_array($events)) $events = array($events);
        $this->events = $events;

        return $this;
    }

    /**
     * Get events
     *
     * @return array 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set canShare
     *
     * @param boolean $canShare
     * @return Quest
     */
    public function setCanShare($canShare)
    {
        $this->canShare = $canShare;

        return $this;
    }

    /**
     * Get canShare
     *
     * @return boolean 
     */
    public function getCanShare()
    {
        return $this->canShare;
    }

    /**
     * Set repeatable
     *
     * @param string $repeatable
     * @return Quest
     */
    public function setRepeatable($repeatable)
    {
        $this->repeatable = $repeatable;

        return $this;
    }

    /**
     * Get repeatable
     *
     * @return string 
     */
    public function getRepeatable()
    {
        return $this->repeatable;
    }

    /**
     * Set rewards
     *
     * @param array $rewards
     * @return Quest
     */
    public function setRewards($rewards)
    {
        if(!is_array($rewards)) $rewards = array($rewards);
        $this->rewards = $rewards;

        return $this;
    }

    /**
     * Get rewards
     *
     * @return array 
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * Set repeatRewards
     *
     * @param array $repeatRewards
     * @return Quest
     */
    public function setRepeatRewards($repeatRewards)
    {
        if(!is_array($repeatRewards)) $repeatRewards = array($repeatRewards);
        $this->repeatRewards = $repeatRewards;

        return $this;
    }

    /**
     * Get repeatRewards
     *
     * @return array 
     */
    public function getRepeatRewards()
    {
        return $this->repeatRewards;
    }

    /**
     * Set secondsToComplete
     *
     * @param integer $secondsToComplete
     * @return Quest
     */
    public function setSecondsToComplete($secondsToComplete)
    {
        $this->secondsToComplete = $secondsToComplete;

        return $this;
    }

    /**
     * Get secondsToComplete
     *
     * @return integer 
     */
    public function getSecondsToComplete()
    {
        return $this->secondsToComplete;
    }

    /**
     * Set shortDescription
     *
     * @param array $shortDescription
     * @return Quest
     */
    public function setShortDescription($shortDescription)
    {
        if(!is_array($shortDescription)) $shortDescription = array($shortDescription);
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return array 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param array $longDescription
     * @return Quest
     */
    public function setLongDescription($longDescription)
    {
        if(!is_array($longDescription)) $longDescription = array($longDescription);
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * Get longDescription
     *
     * @return array 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set denouement
     *
     * @param array $denouement
     * @return Quest
     */
    public function setDenouement($denouement)
    {
        if(!is_array($denouement)) $denouement = array($denouement);
        $this->denouement = $denouement;

        return $this;
    }

    /**
     * Get denouement
     *
     * @return array 
     */
    public function getDenouement()
    {
        return $this->denouement;
    }

    /**
     * Set objectives
     *
     * @param array $objectives
     * @return Quest
     */
    public function setObjectives($objectives)
    {
        if(!is_array($objectives)) $objectives = array($objectives);
        $this->objectives = $objectives;

        return $this;
    }

    /**
     * Get objectives
     *
     * @return array 
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Set objectivesCompleteText
     *
     * @param array $objectivesCompleteText
     * @return Quest
     */
    public function setObjectivesCompleteText($objectivesCompleteText)
    {
        if(!is_array($objectivesCompleteText)) $objectivesCompleteText = array($objectivesCompleteText);
        $this->objectivesCompleteText = $objectivesCompleteText;

        return $this;
    }

    /**
     * Get objectivesCompleteText
     *
     * @return array 
     */
    public function getObjectivesCompleteText()
    {
        return $this->objectivesCompleteText;
    }

    /**
     * Set faction
     *
     * @param string $faction
     * @return Quest
     */
    public function setFaction($faction)
    {
        $this->faction = $faction;

        return $this;
    }

    /**
     * Get faction
     *
     * @return string 
     */
    public function getFaction()
    {
        return $this->faction;
    }

    /**
     * Set guildLevel
     *
     * @param integer $guildLevel
     * @return Quest
     */
    public function setGuildLevel($guildLevel)
    {
        $this->guildLevel = $guildLevel;

        return $this;
    }

    /**
     * Get guildLevel
     *
     * @return integer 
     */
    public function getGuildLevel()
    {
        return $this->guildLevel;
    }

    /**
     * Set firstCompletedBy
     *
     * @param array $firstCompletedBy
     * @return Quest
     */
    public function setFirstCompletedBy($firstCompletedBy)
    {
        if(!is_array($firstCompletedBy)) $firstCompletedBy = array($firstCompletedBy);
        $this->firstCompletedBy = $firstCompletedBy;

        return $this;
    }

    /**
     * Get firstCompletedBy
     *
     * @return array 
     */
    public function getFirstCompletedBy()
    {
        return $this->firstCompletedBy;
    }

    /**
     * Add givers
     *
     * @param \Concord\BrowseBundle\Entity\NPC $givers
     * @return Quest
     */
    public function addGiver(\Concord\BrowseBundle\Entity\NPC $givers)
    {
        $this->givers[] = $givers;

        return $this;
    }

    /**
     * Remove givers
     *
     * @param \Concord\BrowseBundle\Entity\NPC $givers
     */
    public function removeGiver(\Concord\BrowseBundle\Entity\NPC $givers)
    {
        $this->givers->removeElement($givers);
    }

    /**
     * Get givers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGivers()
    {
        return $this->givers;
    }

    /**
     * Add completers
     *
     * @param \Concord\BrowseBundle\Entity\NPC $completers
     * @return Quest
     */
    public function addCompleter(\Concord\BrowseBundle\Entity\NPC $completers)
    {
        $this->completers[] = $completers;

        return $this;
    }

    /**
     * Remove completers
     *
     * @param \Concord\BrowseBundle\Entity\NPC $completers
     */
    public function removeCompleter(\Concord\BrowseBundle\Entity\NPC $completers)
    {
        $this->completers->removeElement($completers);
    }

    /**
     * Get completers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompleters()
    {
        return $this->completers;
    }

    /**
     * Add requireNone
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireNone
     * @return Quest
     */
    public function addRequireNone(\Concord\BrowseBundle\Entity\Quest $requireNone)
    {
        $this->requireNone[] = $requireNone;

        return $this;
    }

    /**
     * Remove requireNone
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireNone
     */
    public function removeRequireNone(\Concord\BrowseBundle\Entity\Quest $requireNone)
    {
        $this->requireNone->removeElement($requireNone);
    }

    /**
     * Get requireNone
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireNone()
    {
        return $this->requireNone;
    }

    /**
     * Add requireOnNone
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnNone
     * @return Quest
     */
    public function addRequireOnNone(\Concord\BrowseBundle\Entity\Quest $requireOnNone)
    {
        $this->requireOnNone[] = $requireOnNone;

        return $this;
    }

    /**
     * Remove requireOnNone
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnNone
     */
    public function removeRequireOnNone(\Concord\BrowseBundle\Entity\Quest $requireOnNone)
    {
        $this->requireOnNone->removeElement($requireOnNone);
    }

    /**
     * Get requireOnNone
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireOnNone()
    {
        return $this->requireOnNone;
    }

    /**
     * Add requireAny
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireAny
     * @return Quest
     */
    public function addRequireAny(\Concord\BrowseBundle\Entity\Quest $requireAny)
    {
        $this->requireAny[] = $requireAny;

        return $this;
    }

    /**
     * Remove requireAny
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireAny
     */
    public function removeRequireAny(\Concord\BrowseBundle\Entity\Quest $requireAny)
    {
        $this->requireAny->removeElement($requireAny);
    }

    /**
     * Get requireAny
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireAny()
    {
        return $this->requireAny;
    }

    /**
     * Add requireOnAny
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnAny
     * @return Quest
     */
    public function addRequireOnAny(\Concord\BrowseBundle\Entity\Quest $requireOnAny)
    {
        $this->requireOnAny[] = $requireOnAny;

        return $this;
    }

    /**
     * Remove requireOnAny
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnAny
     */
    public function removeRequireOnAny(\Concord\BrowseBundle\Entity\Quest $requireOnAny)
    {
        $this->requireOnAny->removeElement($requireOnAny);
    }

    /**
     * Get requireOnAny
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireOnAny()
    {
        return $this->requireOnAny;
    }

    /**
     * Add requireAll
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireAll
     * @return Quest
     */
    public function addRequireAll(\Concord\BrowseBundle\Entity\Quest $requireAll)
    {
        $this->requireAll[] = $requireAll;

        return $this;
    }

    /**
     * Remove requireAll
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireAll
     */
    public function removeRequireAll(\Concord\BrowseBundle\Entity\Quest $requireAll)
    {
        $this->requireAll->removeElement($requireAll);
    }

    /**
     * Get requireAll
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireAll()
    {
        return $this->requireAll;
    }

    /**
     * Add requireOnAll
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnAll
     * @return Quest
     */
    public function addRequireOnAll(\Concord\BrowseBundle\Entity\Quest $requireOnAll)
    {
        $this->requireOnAll[] = $requireOnAll;

        return $this;
    }

    /**
     * Remove requireOnAll
     *
     * @param \Concord\BrowseBundle\Entity\Quest $requireOnAll
     */
    public function removeRequireOnAll(\Concord\BrowseBundle\Entity\Quest $requireOnAll)
    {
        $this->requireOnAll->removeElement($requireOnAll);
    }

    /**
     * Get requireOnAll
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequireOnAll()
    {
        return $this->requireOnAll;
    }
}
