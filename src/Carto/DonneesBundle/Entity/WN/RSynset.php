<?php
/**
	* Modèle des synsets de type adverbe
	*
	* rappel : un synset est un ensemble de mots synonymes les uns des autres.
	*
	* @author Rémy Cluze <Remy.Cluze@etu.univ-savoie.fr>
	* @author Anthony Di Lisio <Anthony.Di-Lisio@etu.univ-savoie.fr>
	* @author Juliana Leclaire <Juliana.Leclaire@etu.univ-savoie.fr>
	* @author Rémi Mollard <Remi.Mollard@etu.univ-savoie.fr>
	* @author Céline de Roland <Celine.de-Roland@etu.univ-savoie.fr>
	*
	* @version 1.0
	*/
namespace Carto\DonneesBundle\Entity\WN;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modèle des synsets de type adverbe
 *
 * rappel : un synset est un ensemble de mots synonymes les uns des autres.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\WN\RSynsetRepository")
 */
class RSynset
{
	/**
	 * identification dans la base de données
	 *
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * identification dans la base wordnet
	 *
	 * utile uniquement lors de l'import de la base wordnet
	 *
	 * @var string
	 *
	 * @ORM\Column(name="wnid", type="string", length=10)
	 */
	private $wnid;

	/**
	 * Définition du concept représenté par le synset
	 *
	 * @var string
	 *
	 * @ORM\Column(name="definition", type="text")
	 */
	private $definition;

	/**
	 * Liste des mots appartenant à ce synset
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="rsynsets")
	 */
	private $mots;

	/**
	 * Liste des antonymes de ce synset
	 *
	 * @var \Doctrine\Common\Collections\Collection 
	 *
	 * @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\RSynset")
	 * @ORM\JoinTable(name="rantonyms")
	 */
	private $antonyms;

	/**
	 * Constructeur
	 *
	 * @param string $wnid : identificateur du synset dans les fichiers textes téléchargés de WordNet
	 * @param string $def : définition du concept représenté par le synset
	 */
	public function __construct($wnid,$def)
	{
		$this -> setDefinition($def);
		$this -> setWnid($wnid);
	}

	/**
	 * Quel est le type du synset ? 
	 *
	 * @return string : 'R' pour adverbe
	 */
	public function getType()
	{
		return 'R';
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
	 * @return RSynset
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
	 * @return RSynset
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
	 * @return RSynset
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
	 * @param \Carto\DonneesBundle\Entity\WN\RSynset $antonyms
	 * @return RSynset
	 */
	public function addAntonym(\Carto\DonneesBundle\Entity\WN\RSynset $antonyms)
	{
		$this->antonyms[] = $antonyms;

		return $this;
	}

	/**
	 * Remove antonyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\RSynset $antonyms
	 */
	public function removeAntonym(\Carto\DonneesBundle\Entity\WN\RSynset $antonyms)
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
}
