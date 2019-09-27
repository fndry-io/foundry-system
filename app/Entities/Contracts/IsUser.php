<?php

namespace Foundry\System\Entities\Contracts;

/**
 * Interface IsUser
 *
 * @property string $username
 * @property string $email
 * @property string $display_name
 * @property string $password
 *
 * @package Foundry\System\Entities\Contracts
 */
interface IsUser extends IsEntity, IsFillable
{

}