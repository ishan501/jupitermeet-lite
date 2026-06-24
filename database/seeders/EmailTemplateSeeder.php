<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_templates')->insert(
            array(
                array(
                    'name' => 'Meeting Invitation',
                    'slug' => 'meeting-invitation',
                    'content' => '<p>Greetings!&nbsp;[USER_NAME] has invited you to attend a virtual meeting</p>

                    <p>&nbsp;</p>

                    <ul>
                        <li><strong>Meeting ID</strong>:&nbsp;[MEETING_ID]</li>
                        <li><strong>Meeting Link</strong>: [MEETING_LINK]</li>
                        <li><strong>Title</strong>: [MEETING_TITLE]</li>
                        <li><strong>Password</strong>: [MEETING_PASSWORD]</li>
                        <li><strong>Date: </strong>[MEETING_DATE]</li>
                        <li><strong>Time</strong>: [MEETING_TIME]</li>
                        <li><strong>Timezone: </strong>[MEETING_TIMEZONE]</li>
                        <li><strong>Description: </strong>[MEETING_DESCRIPTION]</li>
                    </ul>

                    <p>&nbsp;</p>

                    <p>Thank You!</p>'
                ),
                array(
                    'name' => 'Cancel Subscription',
                    'slug' => 'cancel-subscription',
                    'content' => '<p>Subscription cancelled</p>
                    <p>&nbsp;</p>                
                    <p>The subscription was cancelled</p>                
                    <p>&nbsp;</p>                
                    <p>Thank you</p>'
                ),
                array(
                    'name' => 'Payment Status - Success',
                    'slug' => 'payment-status-success',
                    'content' => '<p>Payment completed</p>
                    <p>The payment was successful</p>                
                    <p>Thank You</p>'
                ),
                array(
                    'name' => 'Payment Status - Fail',
                    'slug' => 'payment-status-fail',
                    'content' => '<p>Payment cancelled</p>
                    <p>The payment was cancelled</p>                
                    <p>Thank You</p>'
                ),
                array(
                    'name' => 'Test SMTP',
                    'slug' => 'test-smtp',
                    'content' => '<p>Hi admin, this is just a test email. Your SMTP is working fine</p>'
                ),
                array(
                    'name' => 'Two Factore Auth Code',
                    'slug' => 'two-factor-auth-code',
                    'content' => '<p>Hi,</p>
                    <p>&nbsp;</p>                
                    <p>Your code is : [CODE]</p>                
                    <p>&nbsp;</p>                
                    <p>Thank you</p>'
                ),
                array(
                    'name' => 'User Creation',
                    'slug' => 'user-creation',
                    'content' => '<p>Greetings! You can now host meetings</p>
                    <p>&nbsp;</p>                
                    <ul>
                    <li>
                    <p><strong>Username: </strong>[USER_NAME]</p>
                    </li>
                    <li>
                    <p><strong>Email: </strong>[USER_EMAIL]</p>
                    </li>
                    <li>
                    <p><strong>Password: </strong>[USER_PASSWORD]</p>
                    </li>
                    </ul>                
                    <p>&nbsp;</p>
                    <p>Thank you</p>'
                ),
                array(
                    'name' => 'Welcome',
                    'slug' => 'welcome',
                    'content' => '<p>Hello!&nbsp;[USER_NAME], Welcome to our site.</p>'
                ),
                array(
                    'name' => 'Version Upgrade',
                    'slug' => 'version-upgrade',
                    'content' => '<p>Hello,</p><p>Your application has been successfully upgraded to version [VERSION].</p><p>If you have any questions or require assistance, feel free to contact our support team.</p>'
                ),
                array(
                    'name' => 'Signaling Server is Down',
                    'slug' => 'ping-signaling-server',
                    'content' => '<p>Hello,</p><p>Your Signaling server, [SIGNALING_URL] is currently down.</p><p>Please refer to the "Signaling" section in our official documentation for troubleshooting steps.</p>'
                )
            )
        );
    }
}
