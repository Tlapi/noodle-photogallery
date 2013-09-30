<?php
namespace NoodlePhotogallery\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;
use Noodle\Entity\Base;

/**
 * A movie
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\Reflection")
 * @Annotation\Name("Test")
 * @ORM\Entity(repositoryClass="\Noodle\Repository\Base")
 * @ORM\Table(name="noodle_photogallery")
 * @property integer $id
 * @property string $name
 */
class Photogallery extends Base
{

	/**
	* @ORM\Column(type="integer", nullable=true);
	* @Annotation\Type("Noodle\Form\Element\Picture")
	* @Annotation\Options({"label":"Picture"})
	* @Annotation\Required(false)
	*/
	private $picture;

	/**
	* Magic getter to expose protected properties.
	*
	* @param DateTime $property
	* @return mixed
	*/
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	* Magic setter to save protected properties.
	*
	* @param string $property
	* @param mixed $value
	*/
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

}
