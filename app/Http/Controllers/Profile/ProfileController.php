<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Profile
 */
class ProfileController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {}

    public function info(Request $request): ProfileInfoResource
    {
        return ProfileInfoResource::make(
            $request->user()
        );
    }
}
