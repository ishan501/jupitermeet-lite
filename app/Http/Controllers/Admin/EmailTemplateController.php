<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmailTemplatesRequest;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $emailTemplates = EmailTemplate::orderBy('id', 'DESC')->paginate(config('app.pagination'));

        return view('admin.email-template.index', [
            'pageTitle' => __('Email Template'),
            'emailTemplates' => $emailTemplates,
        ]);
    }

    public function edit($id)
    {
        $emailTemplate = EmailTemplate::findOrFail($id);

        //set variables based on email template slug
        if ($emailTemplate->slug == "meeting-invitation") {
            $variables = ['MEETING_ID', 'MEETING_LINK', 'MEETING_TITLE', 'MEETING_PASSWORD', 'MEETING_DATE', 'MEETING_TIME', 'MEETING_TIMEZONE', 'MEETING_DESCRIPTION'];
        } elseif ($emailTemplate->slug == "two-factor-auth-code") {
            $variables = ['CODE'];
        } elseif ($emailTemplate->slug == "user-creation") {
            $variables = ['USER_NAME', 'USER_EMAIL', 'USER_PASSWORD'];
        } elseif ($emailTemplate->slug == "welcome") {
            $variables = ['USER_NAME'];
        } elseif ($emailTemplate->slug == "version-upgrade") {
            $variables = ['VERSION'];
        } elseif ($emailTemplate->slug == "ping-signaling-server") {
            $variables = ['SIGNALING_URL'];
        } else {
            $variables = [];
        }

        return view('admin.email-template.edit', [
            'pageTitle' => __('Update'),
            'emailTemplate' => $emailTemplate,
            'variables' => $variables,
        ]);
    }

    public function update(UpdateEmailTemplatesRequest $request, $id)
    {
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->name = $request->name;
        $emailTemplate->content = $request->content;
        $emailTemplate->save();

        return redirect()->route('admin.email-template')->with('message', __('Email Template updated Successfully.'));
    }
}