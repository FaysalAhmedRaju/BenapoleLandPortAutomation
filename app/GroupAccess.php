<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAccess extends Model
{

    const MENU_ID = 'menu_id';
    const ROLE_ID = 'role_id';
    const VIEW = 'view';
    const ADD = 'add';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const PORT_ID = 'port_id';

    public $timestamps = false;

    protected $fillable = [
        self::MENU_ID,
        self::ROLE_ID,
        self::VIEW,
        self::ADD,
        self::EDIT,
        self::DELETE
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class,self::ROLE_ID);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class,self::MENU_ID);
    }
}
