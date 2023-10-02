<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\DOMJudgeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RootController
 *
 * @Route("")
 *
 * @package App\Controller
 */
class RootController extends BaseController
{
    protected DOMJudgeService $dj;

    public function __construct(DOMJudgeService $dj)
    {
        $this->dj = $dj;
    }

    /**
     * @Route("", name="root")
     * @Route("", name="public_index")
     */
    public function rootAction(AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->dj->checkrole('jury')) {
                return $this->redirectToRoute('jury_index');
            }
            if ($this->dj->checkrole('team', false)) {
                return $this->redirectToRoute('team_index');
            }
            if ($this->dj->checkrole('balloon')) {
                return $this->redirectToRoute('jury_balloons');
            }
            if ($this->dj->checkrole('clarification_rw')) {
                return $this->redirectToRoute('jury_clarifications');
            }
        }
        return $this->forward(PublicController::class . '::homepageAction');
    }
}
