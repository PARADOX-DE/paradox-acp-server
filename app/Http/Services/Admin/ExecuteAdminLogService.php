<?php

namespace App\Http\Services\Admin;

use App\Enumerations\ACPActionType;
use App\Http\Exceptions\Auth\InvalidAuthCredentialsException;
use App\Http\Exceptions\Player\InvalidPlayerException;
use App\Models\ACPAction;
use App\Models\LogAcp;
use App\Models\Player;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExecuteAdminLogService
 * @package App\Http\Services\Admin
 */
class ExecuteAdminLogService
{
    /**
     * ExecuteAdminLogService constructor
     *
     * @return void
     */
    public function __construct() { }

    public function execute(int $adminId, int $targetId, string $type, string $description = '')
    {
        LogAcp::query()->create([
            'admin_id' => $adminId,
            'target_id' => $targetId,
            'type' => $type,
            'description' => $description,
        ]);
    }
}
