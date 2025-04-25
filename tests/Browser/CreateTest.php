<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group create
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'admin@gmail.com')
                    ->type('password', '1234567890')
                    ->press('Login')
                    ->assertPathIs('/dashboard/recruiter')
                    ->clickLink('List a certificate')
                    ->type('title', 'Workshop UI/UX')
                    ->type('description', 'Workshop UI/UX')
                    ->press('Create Event')
                    ->assertPathIs('/recruiter/events');


        });
    }
}
