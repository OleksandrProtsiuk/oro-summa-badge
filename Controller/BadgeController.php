<?php

namespace Summa\Bundle\BadgeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\FormBundle\Model\UpdateHandler;
use Oro\Bundle\SecurityBundle\Attribute\AclAncestor;
use Summa\Bundle\BadgeBundle\Form\Handler\BadgeHandler;
use Summa\Bundle\BadgeBundle\Form\Type\BadgeType;
use Summa\Bundle\BadgeBundle\Entity\Badge;

class BadgeController extends AbstractController
{

    /**
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/', name: 'summa_badge_index')]
    #[Template('SummaBadgeBundle:Badge:index.html.twig')]
    #[AclAncestor('summa_badge_view')]
    public function indexAction()
    {
        return [
            'entity_class' => Badge::class
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/create', name: 'summa_badge_create')]
    #[Template('SummaBadgeBundle:Badge:update.html.twig')]
    #[AclAncestor('summa_badge_create')]
    public function createAction(Request $request)
    {
        return $this->update(new Badge(), $request);
    }

    /**
     *
     * @param Badge $badge
     * @param Request $request
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/update/{id}', name: 'summa_badge_update', requirements: ['id' => '\d+'])]
    #[Template('SummaBadgeBundle:Badge:update.html.twig')]
    #[AclAncestor('summa_badge_update')]
    public function updateAction(Badge $badge, Request $request)
    {
        return $this->update($badge, $request);
    }

    /**
     * @param Badge $badge
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/view/{id}', name: 'summa_badge_view', requirements: ['id' => '\d+'])]
    #[AclAncestor('summa_badge_view')]
    #[Template('SummaBadgeBundle:Badge:view.html.twig')]
    public function viewAction(Badge $badge)
    {
        return [
            'entity'    =>  $badge
        ];
    }

    /**
     * @param Badge $badge
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/info/{id}', name: 'summa_badge_info', requirements: ['id' => '\d+'])]
    #[AclAncestor('summa_badge_view')]
    #[Template('SummaBadgeBundle:Badge/widget:info.html.twig')]
    public function infoAction(Badge $badge)
    {
        return [
            'entity' => $badge
        ];

    }

    /**
     *
     *
     * @param Badge $entity
     * @return array
     */
    #[\Symfony\Component\Routing\Attribute\Route(path: '/delete/{id}', name: 'summa_badge_delete', requirements: ['id' => '\d+'])]
    #[AclAncestor('summa_badge_delete')]
    #[Template]
    public function deleteAction(int $id)
    {
        return true;
    }

    /**
     * @param Badge $badge
     * @param Request $request
     * @return array|RedirectResponse
     */
    protected function update(Badge $badge, Request $request)
    {
        return $this->get(\Oro\Bundle\FormBundle\Model\UpdateHandlerFacade::class)->handleUpdate(
            $badge,
            $this->createForm(BadgeType::class, $badge),
            function (Badge $badge) {
                return [
                    'route' => 'summa_badge_view',
                    'parameters' => ['id' => $badge->getId()]
                ];
            },
            function (Badge $badge) {
                return [
                    'route' => 'summa_badge_index',
                    'parameters' => []
                ];
            },
            $this->get(TranslatorInterface::class)->trans('summa.badge.controller.saved.message')
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                TranslatorInterface::class,
                \Oro\Bundle\FormBundle\Model\UpdateHandlerFacade::class
            ]
        );
    }
}
