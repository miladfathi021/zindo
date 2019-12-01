<?php

namespace Tests\Unit;

use App\User;
use App\Zindo\User_Settings\ProfileStatusManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_can_changes_his_profile_status_to_private()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $john->setting->private();

        $this->assertEquals($john->setting->status, ProfileStatusManager::STATUS_PRIVATE);
    }

    /** @test **/
    public function a_user_can_changes_his_profile_status_to_public()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $john->setting->private();

        $john->setting->public();

        $this->assertEquals($john->setting->status, ProfileStatusManager::STATUS_PUBLIC);
    }

    /** @test **/
    public function it_can_check_if_the_profile_is_private()
    {
        $this->withoutExceptionHandling();

        $john = $this->signIn();

        $john->setting->private();

        $this->assertTrue($john->setting->isPrivate());
    }
}
