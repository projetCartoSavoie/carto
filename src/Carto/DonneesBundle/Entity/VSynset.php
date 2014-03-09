<?php

namespace Carto\DonneesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VSynset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\VSynsetRepository")
 */
class VSynset
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="wnid", type="string", length=10)
     */
    private $wnid;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="text")
     */
    private $definition;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\Mot",mappedBy="vsynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset")
	* @ORM\JoinTable(name="vantonyms")
	*/
	private $antonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",inversedBy="hyponyms")
	* @ORM\JoinTable(name="vtroponyms")
	*/
	private $troponyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",mappedBy="troponyms")
	*/
	private $hyponyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",inversedBy="holonyms")
	* @ORM\JoinTable(name="ventails")
	*/
	private $entails;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",mappedBy="entails")
	*/
	private $holonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",inversedBy="consequences")
	* @ORM\JoinTable(name="vcauses")
	*/
	private $causes;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\VSynset",mappedBy="causes")
	*/
	private $consequences;

	public function __construct($wnid,$def)
	{
		$this -> setDefinition($def);
		$this -> setWnid($wnid);
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
     * Set wnid
     *
     * @param string $wnid
     * @return VSynset
     */
    public function setWnid($wnid)
    {
        $this->wnid = $wnid;

        return $this;
    }

    /**
     * Get wnid
     *
     * @return string 
     */
    public function getWnid()
    {
        return $this->wnid;
    }

    /**
     * Set definition
     *
     * @param string $definition
     * @return VSynset
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return string 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Add mots
     *
     * @param \Carto\DonneesBundle\Entity\Mot $mots
     * @return VSynset
     */
    public function addMot(\Carto\DonneesBundle\Entity\Mot $mots)
    {
        $this->mots[] = $mots;

        return $this;
    }

    /**
     * Remove mots
     *
     * @param \Carto\DonneesBundle\Entity\Mot $mots
     */
    public function removeMot(\Carto\DonneesBundle\Entity\Mot $mots)
    {
        $this->mots->removeElement($mots);
    }

    /**
     * Get mots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMots()
    {
        return $this->mots;
    }

    /**
     * Add antonyms
     *
     * @param \Carto\DonneesBundle\Entity\VSynset $antonyms
     * @return VSynset
     */
    public function addAntonym(\Carto\DonneesBundle\Entity\VSynset $antonyms)
    {
        $this->antonyms[] = $antonyms;

        return $this;
    }

    /**
     * Remove antonyms
     *
     * @param \Carto\DonneesBundle\Entity\VSynset $antonyms
     */
    public function removeAntonym(\Carto\DonneesBundle\Entity\VSynset $antonyms)
    {
        $this->antonyms->removeElement($antonyms);
    }

    /**
     * Get antonyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAntonyms()
    {
        return $this->antonyms;
    }

    /**
     * Add troponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $troponyms
     * @return VSynset
     */
    public function addTroponym(\Carto\DonneesBundle\Entity\NSynset $troponyms)
    {
        $this->troponyms[] = $troponyms;

        return $this;
    }

    /**
     * Remove troponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $troponyms
     */
    public function removeTroponym(\Carto\DonneesBundle\Entity\NSynset $troponyms)
    {
        $this->troponyms->removeElement($troponyms);
    }

    /**
     * Get troponyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTroponyms()
    {
        return $this->troponyms;
    }

    /**
     * Add hyponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hyponyms
     * @return VSynset
     */
    public function addHyponym(\Carto\DonneesBundle\Entity\NSynset $hyponyms)
    {
        $this->hyponyms[] = $hyponyms;

        return $this;
    }

    /**
     * Remove hyponyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $hyponyms
     */
    public function removeHyponym(\Carto\DonneesBundle\Entity\NSynset $hyponyms)
    {
        $this->hyponyms->removeElement($hyponyms);
    }

    /**
     * Get hyponyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHyponyms()
    {
        return $this->hyponyms;
    }

    /**
     * Add entails
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $entails
     * @return VSynset
     */
    public function addEntail(\Carto\DonneesBundle\Entity\NSynset $entails)
    {
        $this->entails[] = $entails;

        return $this;
    }

    /**
     * Remove entails
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $entails
     */
    public function removeEntail(\Carto\DonneesBundle\Entity\NSynset $entails)
    {
        $this->entails->removeElement($entails);
    }

    /**
     * Get entails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntails()
    {
        return $this->entails;
    }

    /**
     * Add holonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $holonyms
     * @return VSynset
     */
    public function addHolonym(\Carto\DonneesBundle\Entity\NSynset $holonyms)
    {
        $this->holonyms[] = $holonyms;

        return $this;
    }

    /**
     * Remove holonyms
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $holonyms
     */
    public function removeHolonym(\Carto\DonneesBundle\Entity\NSynset $holonyms)
    {
        $this->holonyms->removeElement($holonyms);
    }

    /**
     * Get holonyms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHolonyms()
    {
        return $this->holonyms;
    }

    /**
     * Add causes
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $causes
     * @return VSynset
     */
    public function addCause(\Carto\DonneesBundle\Entity\NSynset $causes)
    {
        $this->causes[] = $causes;

        return $this;
    }

    /**
     * Remove causes
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $causes
     */
    public function removeCause(\Carto\DonneesBundle\Entity\NSynset $causes)
    {
        $this->causes->removeElement($causes);
    }

    /**
     * Get causes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCauses()
    {
        return $this->causes;
    }

    /**
     * Add consequences
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $consequences
     * @return VSynset
     */
    public function addConsequence(\Carto\DonneesBundle\Entity\NSynset $consequences)
    {
        $this->consequences[] = $consequences;

        return $this;
    }

    /**
     * Remove consequences
     *
     * @param \Carto\DonneesBundle\Entity\NSynset $consequences
     */
    public function removeConsequence(\Carto\DonneesBundle\Entity\NSynset $consequences)
    {
        $this->consequences->removeElement($consequences);
    }

    /**
     * Get consequences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConsequences()
    {
        return $this->consequences;
    }
}
