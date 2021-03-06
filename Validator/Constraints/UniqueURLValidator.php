<?php

/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Networking\InitCmsBundle\Validator\Constraints;

use Networking\InitCmsBundle\Entity\Page;
use Networking\InitCmsBundle\Entity\MenuItem;
use Networking\InitCmsBundle\Entity\MenuItemRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

/**
 * @author net working AG <info@networking.ch>
 */
class UniqueURLValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->request = $container->get('request');
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $page = null;
        $params = array(':path' => $value);

        /** @var $page Page */
        $page = $this->context->getRoot()->getViewData();

        /** @var $repository MenuItemRepository */
        $repository = $this->em
                ->getRepository('NetworkingInitCmsBundle:MenuItem');

        $qb = $repository->createQueryBuilder('m');

        $qb->where('m.path = :path');

        if ($page->getId()) {
            $qb->andWhere('m.page != :page');
            $params[':page'] = $page;
        }
        $qb->setParameters($params);

        try {
            $menuItem = $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $menuItem = NULL;
        }

        if (!is_null($menuItem)) {
            $this->context->addViolation($constraint->message, array('{{ value }}' => $value));

            return false;
        }

        return true;
    }
}
