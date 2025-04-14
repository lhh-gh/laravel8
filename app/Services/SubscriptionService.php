<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-13
 * Time: 21:58
 */

namespace App\Services;

use App\Models\Student;
use App\Models\User;

class SubscriptionService
{
    public function addCreditCard(User $user, Student $card)

    {

        return true;
    }

    public function removeCreditCard(User $user, Student $card)
    {

        return true;
    }

    public function getCreditCards(User $user)
    {
        return true;
    }

    public function setupSubscription(User $user)
    {
        return true;
    }

    public function isSubscribed(User $user)
    {
        return true;
    }

    public function chargeSubscription(User $user)
    {
        return true;
    }

    public function removeSubscription(User $user)
    {
        return true;
    }
}
