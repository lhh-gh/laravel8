<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-12
 * Time: 20:31
 */

namespace App\Http\Controllers;

use App\Services\SubscriptionService;

class TestController
{
    //从服务容器中解析服务
    public function index()
    {
        $user = request()->user();
        $subscriptionService = resolve(SubscriptionService::class);

        return view('billing.index', [
            'creditCards' => $subscriptionService->getCreditCards($user),
            'isSubscribed' => $subscriptionService->isSubscribed($user),
        ]);
    }

    //  依赖注入方式
    public function index2(SubscriptionService $subscriptionService)
    {
        $user = request()->user();

        return view('billing.index', [
            'creditCards' => $subscriptionService->getCreditCards($user),
            'isSubscribed' => $subscriptionService->isSubscribed($user),
        ]);
    }

    public function index3()
    {
        $user = request()->user();
        $subscriptionService = new SubscriptionService();

        return view('billing.index', [
            'creditCards' => $subscriptionService->getCreditCards($user),
            'isSubscribed' => $subscriptionService->isSubscribed($user),
        ]);
    }

}
