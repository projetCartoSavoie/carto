<?php

namespace Carto\DonneesBundle\Entity\Humour;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\Humour\ObjetRepository")
 */
class Objet
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
	 * @ORM\Column(name="titre", type="string", length=255)
	 */
	private $titre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", length=255)
	 */
	private $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text")
	 */
	private $description;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text")
	 */
	private $image;

	/**
	* liste des triplets dont cet objet est le sujet 
	*
	* @ORM\OneToMany(targetEntity="Carto\DonneesBundle\Entity\Humour\Triplet",mappedBy="sujet")
	*/
	private $triplets;

	/**
	* liste des triplets dont cet objet est l'objet 
	*
	* @ORM\OneToMany(targetEntity="Carto\DonneesBundle\Entity\Humour\Triplet",mappedBy="objet")
	*/
	private $otriplets;

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
	 * Set titre
	 *
	 * @param string $titre
	 * @return Objet
	 */
	public function setTitre($titre)
	{
		$this->titre = $titre;

		return $this;
	}

	/**
	 * Get titre
	 *
	 * @return string 
	 */
	public function getTitre()
	{
		return $this->titre;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Objet
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription()
	{
		return $this->description;
	}
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->triplets = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Add triplets
	 *
	 * @param \Carto\DonneesBundle\Entity\Humour\Triplet $triplets
	 * @return Objet
	 */
	public function addTriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $triplets)
	{
		$this->triplets[] = $triplets;

		return $this;
	}

	/**
	 * Remove triplets
	 *
	 * @param \Carto\DonneesBundle\Entity\Humour\Triplet $triplets
	 */
	public function removeTriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $triplets)
	{
		$this->triplets->removeElement($triplets);
	}

	/**
	 * Get triplets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTriplets()
	{
		return $this->triplets;
	}

	/**
	 * Add otriplets
	 *
	 * @param \Carto\DonneesBundle\Entity\Humour\Triplet $otriplets
	 * @return Objet
	 */
	public function addOtriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $otriplets)
	{
		$this->otriplets[] = $otriplets;

		return $this;
	}

	/**
	 * Remove otriplets
	 *
	 * @param \Carto\DonneesBundle\Entity\Humour\Triplet $otriplets
	 */
	public function removeOtriplet(\Carto\DonneesBundle\Entity\Humour\Triplet $otriplets)
	{
		$this->otriplets->removeElement($otriplets);
	}

	/**
	 * Get otriplets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getOtriplets()
	{
		return $this->otriplets;
	}

	/**
	 * Get otriplets
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getAlltriplets()
	{
		return array_merge($this->getOtriplets() -> toArray(), $this -> getTriplets() -> toArray());
	}

}
