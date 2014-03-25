<?php

namespace Carto\DonneesBundle\Entity\WN;

use Doctrine\ORM\Mapping as ORM;

/**
 * VSynset
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\WN\VSynsetRepository")
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
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="vsynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset")
	* @ORM\JoinTable(name="vantonyms")
	*/
	private $antonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",inversedBy="hyponyms")
	* @ORM\JoinTable(name="vtroponyms")
	*/
	private $troponyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",mappedBy="troponyms")
	*/
	private $hyponyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",inversedBy="holonyms")
	* @ORM\JoinTable(name="ventails")
	*/
	private $entails;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",mappedBy="entails")
	*/
	private $holonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",inversedBy="consequences")
	* @ORM\JoinTable(name="vcauses")
	*/
	private $causes;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\VSynset",mappedBy="causes")
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
     * @param \Carto\DonneesBundle\Entity\WN\Mot $mots
     * @return VSynset
     */
    public function addMot(\Carto\DonneesBundle\Entity\WN\Mot $mots)
    {
        $this->mots[] = $mots;

        return $this;
    }

    /**
     * Remove mots
     *
     * @param \Carto\DonneesBundle\Entity\WN\Mot $mots
     */
    public function removeMot(\Carto\DonneesBundle\Entity\WN\Mot $mots)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $antonyms
     * @return VSynset
     */
    public function addAntonym(\Carto\DonneesBundle\Entity\WN\VSynset $antonyms)
    {
        $this->antonyms[] = $antonyms;

        return $this;
    }

    /**
     * Remove antonyms
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $antonyms
     */
    public function removeAntonym(\Carto\DonneesBundle\Entity\WN\VSynset $antonyms)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $troponyms
     * @return VSynset
     */
    public function addTroponym(\Carto\DonneesBundle\Entity\WN\VSynset $troponyms)
    {
        $this->troponyms[] = $troponyms;

        return $this;
    }

    /**
     * Remove troponyms
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $troponyms
     */
    public function removeTroponym(\Carto\DonneesBundle\Entity\WN\VSynset $troponyms)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $hyponyms
     * @return VSynset
     */
    public function addHyponym(\Carto\DonneesBundle\Entity\WN\VSynset $hyponyms)
    {
        $this->hyponyms[] = $hyponyms;

        return $this;
    }

    /**
     * Remove hyponyms
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $hyponyms
     */
    public function removeHyponym(\Carto\DonneesBundle\Entity\WN\VSynset $hyponyms)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $entails
     * @return VSynset
     */
    public function addEntail(\Carto\DonneesBundle\Entity\WN\VSynset $entails)
    {
        $this->entails[] = $entails;

        return $this;
    }

    /**
     * Remove entails
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $entails
     */
    public function removeEntail(\Carto\DonneesBundle\Entity\WN\VSynset $entails)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $holonyms
     * @return VSynset
     */
    public function addHolonym(\Carto\DonneesBundle\Entity\WN\VSynset $holonyms)
    {
        $this->holonyms[] = $holonyms;

        return $this;
    }

    /**
     * Remove holonyms
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $holonyms
     */
    public function removeHolonym(\Carto\DonneesBundle\Entity\WN\VSynset $holonyms)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $causes
     * @return VSynset
     */
    public function addCause(\Carto\DonneesBundle\Entity\WN\VSynset $causes)
    {
        $this->causes[] = $causes;

        return $this;
    }

    /**
     * Remove causes
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $causes
     */
    public function removeCause(\Carto\DonneesBundle\Entity\WN\VSynset $causes)
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
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $consequences
     * @return VSynset
     */
    public function addConsequence(\Carto\DonneesBundle\Entity\WN\VSynset $consequences)
    {
        $this->consequences[] = $consequences;

        return $this;
    }

    /**
     * Remove consequences
     *
     * @param \Carto\DonneesBundle\Entity\WN\VSynset $consequences
     */
    public function removeConsequence(\Carto\DonneesBundle\Entity\WN\VSynset $consequences)
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
