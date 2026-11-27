<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller\Team;

use App\Entity\Language;
use App\Entity\Problem;
use App\Tests\Unit\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

class EditorControllerTest extends BaseTestCase
{
    protected array $roles = ['team'];

    protected function setUp(): void
    {
        parent::setUp();
        // The fork pins the contest in the URL, so the editor first redirects to add ?cid=.
        $this->client->followRedirects();

        // The editor needs a starting point: a previous submission or a language template.
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->getRepository(Language::class)->find('c')
            ->setDefaultEditorTemplateFilename('main.c')
            ->setDefaultEditorTemplateContent("int main(void) {\n    return 0;\n}\n");
        $em->flush();
    }

    /**
     * The editor opens for a problem teams may submit to.
     */
    public function testEditorOpensForSubmittableProblem(): void
    {
        $probId = $this->helloProblem()->getProbid();
        $this->logIn();

        $crawler = $this->client->request('GET', "/team/editor/{$probId}/c");

        static::assertResponseIsSuccessful();
        static::assertStringContainsString("/team/editor/{$probId}/c", $this->client->getRequest()->getPathInfo());
        static::assertSelectorExists('#teamEditorForm');
        static::assertSelectorTextContains('body', 'Hello World');
        static::assertStringContainsString('main.c', $crawler->html());
    }

    /**
     * A problem with allowSubmit = 0 is hidden from teams, so the editor must not open it
     * either: it redirects to the problem list like a problem outside the contest.
     */
    public function testEditorRejectsProblemNotAllowedForSubmission(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $problem = $this->helloProblem();
        $problem->getContestProblems()->first()->setAllowSubmit(false);
        $em->flush();
        $probId = $problem->getProbid();

        $this->logIn();
        $this->client->request('GET', "/team/editor/{$probId}/c");

        static::assertResponseIsSuccessful();
        static::assertSame('/team/problems', $this->client->getRequest()->getPathInfo());
        static::assertSelectorNotExists('#teamEditorForm');
        static::assertSelectorTextContains('.alert-warning', 'not part of the current contest');
    }

    private function helloProblem(): Problem
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        return $em->getRepository(Problem::class)->findOneBy(['externalid' => 'hello']);
    }
}
