<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Models\UserPermission;

class Helper
{
    public static function permissionCheck($userId, $permissionCode)
    {
        $permissionInfo = UserPermission::with('permissions:id,permission_code')->where(['user_id' => $userId])->get();
        if (count($permissionInfo) > 0) {
            foreach ($permissionInfo as $item) {
                if ($item['permissions']->permission_code == $permissionCode) {
                    $flag = 1;
                    break;
                } else {
                    $flag = 0;
                }
            }
        } else {
            $flag = 0;
        }
        return $flag;
    }
}
