<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateUserPluginRequest;
use App\Http\Requests\API\CreateUserRequest;
use App\Http\Requests\API\UpdateUserRequest;
use App\Jobs\SendUserCreationEmail;
use App\Models\ApiToken;
use App\Models\Plugin;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{


}