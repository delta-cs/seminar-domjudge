<?php declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\DataFixtures\Test\EnableSelfregisterFixture;
use App\Tests\Unit\BaseTestCase;

class RegisterControllerTest extends BaseTestCase
{
    public function testRegisterNotAllowed(): void
    {
        $this->verifyPageResponse('GET', '/public', 200);
        self::assertSelectorNotExists('a.btn:contains("Register")');

        $this->verifyPageResponse('GET', '/register', 403);
        self::assertSelectorExists(':contains("Registration not enabled")');

        $this->verifyPageResponse('GET', '/login', 200);
        self::assertSelectorNotExists('a:contains("Register now")');
    }

    public function testRegisterAllowed(): void
    {
        $this->loadFixture(EnableSelfregisterFixture::class);

        // The fork's own landing page at /public carries the registration
        // button, labelled in Czech, so match the link target not the wording.
        $this->verifyPageResponse('GET', '/public', 200);
        self::assertSelectorExists('a.btn[href="/register"]');

        $this->verifyPageResponse('GET', '/register', 200);
        self::assertSelectorExists('a:contains("Login")');

        $this->verifyPageResponse('GET', '/login', 200);
        self::assertSelectorExists('a:contains("Register now")');
    }
}
