<?php

namespace Concord\BrowseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NPC
 */
class NPC
{
    /**
     * @var integer
     */
    private $id;

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
     * @var array
     */
    private $title;

    /**
     * @var array
     */
    private $level;

    /**
     * @var array
     */
    private $zone;

    /**
     * @var array
     */
    private $scene;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $portrait;

    /**
     * @var array
     */
    private $type;

    /**
     * @var string
     */
    private $plane;

    /**
     * @var array
     */
    private $relationships;

    /**
     * @var array
     */
    private $notorietyRewards;

    /**
     * @var array
     */
    private $sells;

    /**
     * @var array
     */
    private $skillTrainer;

    /**
     * @var array
     */
    private $roleTrainer;

    /**
     * @var string
     */
    private $addonId;

    /**
     * @var array
     */
    private $firstCompletedBy;


    /**
     * Set id
     *
     * @param integer $id
     * @return NPC
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
     * Set nameEN
     *
     * @param string $nameEN
     * @return NPC
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
     * @return NPC
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
     * @return NPC
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
     * Set title
     *
     * @param array $title
     * @return NPC
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return array 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set level
     *
     * @param array $level
     * @return NPC
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return array 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set zone
     *
     * @param array $zone
     * @return NPC
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return array 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set scene
     *
     * @param array $scene
     * @return NPC
     */
    public function setScene($scene)
    {
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
     * Set category
     *
     * @param string $category
     * @return NPC
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set portrait
     *
     * @param string $portrait
     * @return NPC
     */
    public function setPortrait($portrait)
    {
        $this->portrait = $portrait;

        return $this;
    }

    /**
     * Get portrait
     *
     * @return string 
     */
    public function getPortrait()
    {
        return $this->portrait;
    }

    /**
     * Set type
     *
     * @param array $type
     * @return NPC
     */
    public function setType($type)
    {
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
     * Set plane
     *
     * @param string $plane
     * @return NPC
     */
    public function setPlane($plane)
    {
        $this->plane = $plane;

        return $this;
    }

    /**
     * Get plane
     *
     * @return string 
     */
    public function getPlane()
    {
        return $this->plane;
    }

    /**
     * Set relationships
     *
     * @param array $relationships
     * @return NPC
     */
    public function setRelationships($relationships)
    {
        $this->relationships = $relationships;

        return $this;
    }

    /**
     * Get relationships
     *
     * @return array 
     */
    public function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * Set notorietyRewards
     *
     * @param array $notorietyRewards
     * @return NPC
     */
    public function setNotorietyRewards($notorietyRewards)
    {
        $this->notorietyRewards = $notorietyRewards;

        return $this;
    }

    /**
     * Get notorietyRewards
     *
     * @return array 
     */
    public function getNotorietyRewards()
    {
        return $this->notorietyRewards;
    }

    /**
     * Set sells
     *
     * @param array $sells
     * @return NPC
     */
    public function setSells($sells)
    {
        $this->sells = $sells;

        return $this;
    }

    /**
     * Get sells
     *
     * @return array 
     */
    public function getSells()
    {
        return $this->sells;
    }

    /**
     * Set skillTrainer
     *
     * @param array $skillTrainer
     * @return NPC
     */
    public function setSkillTrainer($skillTrainer)
    {
        $this->skillTrainer = $skillTrainer;

        return $this;
    }

    /**
     * Get skillTrainer
     *
     * @return array 
     */
    public function getSkillTrainer()
    {
        return $this->skillTrainer;
    }

    /**
     * Set roleTrainer
     *
     * @param array $roleTrainer
     * @return NPC
     */
    public function setRoleTrainer($roleTrainer)
    {
        $this->roleTrainer = $roleTrainer;

        return $this;
    }

    /**
     * Get roleTrainer
     *
     * @return array 
     */
    public function getRoleTrainer()
    {
        return $this->roleTrainer;
    }

    /**
     * Set addonId
     *
     * @param string $addonId
     * @return NPC
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
     * Set firstCompletedBy
     *
     * @param array $firstCompletedBy
     * @return NPC
     */
    public function setFirstCompletedBy($firstCompletedBy)
    {
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
}
