<?php

namespace App\Helpers;

use DB;
use App\Group;

/**
 * 
 * Contains the functions that handle the creation and manipulation of exercises
 * 
 */

class GroupHelper
{
    protected $parent = 'parent_id';
    
    protected static function table($level)
    {
        return 'hus_group_'.$level;
    }
    
    public static function subdivisions($level, $id)
    {
        $me = DB::table(GroupHelper::table($level))->where('id','=', $id)->first();
        return DB::table(GroupHelper::table($level+1))->where('parent_id','=', $me->id)->get();
    }
    
    public static function user($level, $id)
    {
        $column = GroupHelper::table($level).'_id';
        return DB::table('hus_user')->where($column, $id)->first();
    }
    /**
     * Returns all user ids in an array that belong to a specific group at specific level.
     * @param type $level
     * @param type $group_id
     * @return array
     */
    public static function usersForGroup($level, $group_id)
    {
        $users = DB::table('hus_user')->select('id')->where('hus_group_'.$level.'_id', '=', $group_id)->get();
        return array_map(function($x){ return $x->id; }, $users);
    }
    
    public static function forUser($user, $level)
    {
        $group = new Group();
        $group->setTable('hus_group_'.$level);
        $group = $group->get();
        $column = GroupHelper::table($level).'_id';
        return $group->find($user->$column);
    }
    
    public static function all($level)
    {
        if(!GroupHelper::exists($level))
        {
            return array();
        }
        return DB::table(GroupHelper::table($level))->get();
    }
    public static function exists($level)
    {
        return DB::table("INFORMATION_SCHEMA.TABLES")->where("TABLE_NAME", "=", GroupHelper::table($level))->count() > 0;
    }
    
}

