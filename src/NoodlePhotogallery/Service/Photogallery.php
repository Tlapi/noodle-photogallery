<?php
namespace NoodlePhotogallery\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class Photogallery implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function __construct()
	{
		// construct
	}

	public function getName(){
		return 'Photogallery';
	}
	
	public function getIcon(){
		return 'icon-camera';
	}
	
	public function init(){
		if(isset($_GET['save'])){
			if(isset($_POST['newfile'])){
				foreach($_POST['newfile'] as $pic){
					$newPhoto = new \Application\Entity\Photogallery();
					$newPhoto->picture = $pic;
					$this->getEntityManager()->persist($newPhoto);
				}
				$this->getEntityManager()->flush();
			}
			if(isset($_POST['removeFiles'])){
				$photos = $this->getEntityManager()->getRepository('\NoodlePhotogallery\Entity\Photogallery');
				foreach($_POST['removeFiles'] as $pic){
					$photo = $photos->find($pic);
					$this->getEntityManager()->remove($photo);
				}
				$this->getEntityManager()->flush();
			}
		}
	}
	
	public function render(){
		$renderer = new PhpRenderer();
		$resolver = new Resolver\AggregateResolver();
		$renderer->setResolver($resolver);
		
		$stack = new Resolver\TemplatePathStack(array(
				'script_paths' => array(
						__DIR__ . '/view'
				)
		));
		
		$resolver->attach($stack);
		
		$fileBank = $this->getServiceLocator()->get('FileBank');
		$thumbnailer = $this->getServiceLocator()->get('thumbnailerService');
		$photos = $this->getEntityManager()->getRepository('\NoodlePhotogallery\Entity\Photogallery');
		$photosList = $photos->findAll();
		$thumbsList = array();
		foreach($photosList as $photo){
			$file = $fileBank->getFileById($photo->picture);
			$thumbsList[$photo->getId()] = $thumbnailer->getThumbnailUrl($file, 100, 100, true);
		}
		
		$model = new ViewModel(array('photos' => $thumbsList));
		$model->setTemplate('test');
		return $renderer->render($model);
	}

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}

}