<?php
/**
	* Modèle des synsets de type adjectif
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
 * Modèle des synsets de type adjectif
 *
 * rappel : un synset est un ensemble de mots synonymes les uns des autres.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Carto\DonneesBundle\Entity\WN\ASynsetRepository")
 */
class ASynset
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
	 * @var string
	 *
	 * @ORM\Column(name="definition", type="text")
	 */
	private $definition;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\Mot",mappedBy="asynsets")
	*/
	private $mots;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\NSynset",mappedBy="hasAttribute")
	*/
	private $isAttributeOf;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\ASynset")
	* @ORM\JoinTable(name="aantonyms")
	*/
	private $antonyms;

	/**
	* @ORM\ManyToMany(targetEntity="Carto\DonneesBundle\Entity\WN\ASynset")
	* @ORM\JoinTable(name="asimilars")
	*/
	private $similars;

	public function __construct($wnid,$def)
	{
	$this -> setDefinition($def);
	$this -> setWnid($wnid);
	}


	public function getType()
	{
		return 'A';
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
	 * @return ASynset
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
	 * @return ASynset
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
	 * @return ASynset
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
	 * Add isAttributeOf
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $isAttributeOf
	 * @return ASynset
	 */
	public function addIsAttributeOf(\Carto\DonneesBundle\Entity\WN\NSynset $isAttributeOf)
	{
		$this->isAttributeOf[] = $isAttributeOf;

		return $this;
	}

	/**
	 * Remove isAttributeOf
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\NSynset $isAttributeOf
	 */
	public function removeIsAttributeOf(\Carto\DonneesBundle\Entity\WN\NSynset $isAttributeOf)
	{
		$this->isAttributeOf->removeElement($isAttributeOf);
	}

	/**
	 * Get isAttributeOf
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getIsAttributeOf()
	{
		return $this->isAttributeOf;
	}

	/**
	 * Add antonyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $antonyms
	 * @return ASynset
	 */
	public function addAntonym(\Carto\DonneesBundle\Entity\WN\ASynset $antonyms)
	{
		$this->antonyms[] = $antonyms;

		return $this;
	}

	/**
	 * Remove antonyms
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $antonyms
	 */
	public function removeAntonym(\Carto\DonneesBundle\Entity\WN\ASynset $antonyms)
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
	 * Add similars
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $similars
	 * @return ASynset
	 */
	public function addSimilar(\Carto\DonneesBundle\Entity\WN\ASynset $similars)
	{
		$this->similars[] = $similars;

		return $this;
	}

	/**
	 * Remove similars
	 *
	 * @param \Carto\DonneesBundle\Entity\WN\ASynset $similars
	 */
	public function removeSimilar(\Carto\DonneesBundle\Entity\WN\ASynset $similars)
	{
		$this->similars->removeElement($similars);
	}

	/**
	 * Get similars
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getSimilars()
	{
		return $this->similars;
	}
}
