<?php

namespace App\LifeToolsApi\Order\Controllers;

use App\Infrastructure\Controller;
use App\Infrastructure\Lib\SafeParam;
use App\LifeToolsApi\Order\Filters\OrderFilter;
use App\LifeToolsApi\Order\Repositories\OrderRepository;
use App\LifeToolsApi\Order\Resources\OrderDetailResource;
use App\LifeToolsApi\Order\Resources\OrderResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class OrderController extends Controller
{

    private OrderRepository $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderFilter $filter
     * @return AnonymousResourceCollection
     */
    public function getOrders(OrderFilter $filter): AnonymousResourceCollection
    {
        $search = SafeParam::get('search');
        $orderData = $this->orderRepository->getAllOrdersWithFiltersPaginated($filter, $search);

        return OrderResource::collection($orderData);
    }

    /**
     * @param int $id
     * @return OrderDetailResource
     * @throws Exception
     */
    public function getOrderDetail(int $id): OrderDetailResource
    {
        $order = $this->orderRepository->getOrderDetail($id);

        return new OrderDetailResource($order);
    }

    /**
     * @return Collection
     */
    public function getBundleOrders(): Collection
    {
        return DB::table('bundle_orders')->get();
    }
}
