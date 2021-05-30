<?php

declare(strict_types=1);

namespace App\Services\Banner;

use App\Http\Requests\Admin\Banner\RejectRequest;
use App\Http\Requests\Cabinet\Banner\CreateRequest;
use App\Http\Requests\Cabinet\Banner\EditRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Elasticsearch\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис управления баннерной системой
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class BannerService
{
    /** @var CalculatorService  */
    private $calculator;
    /** @var Client  */
    private $client;

    /**
     * BannerService constructor.
     *
     * @param CalculatorService $calculator
     * @param Client $client
     */
    public function __construct(CalculatorService $calculator, Client $client)
    {
        $this->calculator = $calculator;
        $this->client = $client;
    }

    /**
     * Создание баннера
     *
     * @param User          $user
     * @param Category      $category
     * @param Region|null   $region
     * @param CreateRequest $request
     *
     * @return Banner
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create(User $user, Category $category, ?Region $region, CreateRequest $request): Banner
    {
        /** @var Banner $banner */
        $banner = Banner::make([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
            'status' => Banner::STATUS_DRAFT,
        ]);

        $banner->user()->associate($user);
        $banner->category()->associate($category);
        $banner->region()->associate($region);

        $banner->saveOrFail();

        return $banner;
    }

    /**
     * Редактирование баннера от имени администратора
     *
     * @param integer     $bannerId
     * @param EditRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function edit(int $bannerId, EditRequest $request)
    {
        $banner = $this->getBanner($bannerId);

        // -- Удалим старое изображение баннера, если получено новое
        if ($request->file('file')) {
            if (!Storage::disk('public')->delete($banner->file)) {
                throw new \DomainException('Unable to delete old image banner!');
            }
        }

        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'file' => $request->file('file') ? $request->file('file')->store('banners', 'public') : $banner->file,
        ]);
    }

    /**
     * Модерация баннера
     *
     * @param int $bannerId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function moderate(int $bannerId)
    {
        $banner = $this->getBanner($bannerId);

        $banner->moderate();
    }

    /**
     * Отказ в модерации баннера
     *
     * @param integer       $bannerId
     * @param RejectRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function reject(int $bannerId, RejectRequest $request)
    {
        $banner = $this->getBanner($bannerId);

        $banner->reject($request['reason']);
    }

    /**
     * Оплата баннера
     *
     * @param integer $bannerId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function pay(int $bannerId)
    {
        $banner = $this->getBanner($bannerId);

        $banner->pay(Carbon::now());
    }

    /**
     * Счет на оплату баннера
     *
     * @param integer $bannerId
     *
     * @return Banner
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function order(int $bannerId): Banner
    {
        $banner = $this->getBanner($bannerId);

        $cost = $this->calculator->calculateCost($banner->limit);

        $banner->order($cost);

        return $banner;
    }

    /**
     * Удалить баннер как админ, без проверки
     *
     * @param integer $bannerId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function removeByAdmin(int $bannerId)
    {
        $banner = $this->getBanner($bannerId);

        if (!Storage::disk('public')->delete($banner->file)) {
            throw new \DomainException('Unable to delete old image banner!');
        }

        $banner->remove();
    }

    /**
     * Редактировать баннер как владелец
     *
     * @param integer     $bannerId
     * @param EditRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function editByOwner($bannerId, EditRequest $request)
    {
        $banner = $this->getBanner($bannerId);

        if (!$banner->canBeChanged()) {
            throw new \DomainException('Unable to edit banner!');
        }

        // -- Удалим старое изображение баннера, если получено новое
        if ($request->file('file')) {
            if (!Storage::disk('public')->delete($banner->file)) {
                throw new \DomainException('Unable to delete old image banner!');
            }
        }

        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'file' => $request->file('file') ? $request->file('file')->store('banners', 'public') : $banner->file,
        ]);
    }

    /**
     * Отправка баннера на модерацию
     *
     * @param integer $bannerId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function sendToModeration(int $bannerId)
    {
        $banner = $this->getBanner($bannerId);

        $banner->sendToModeration();
    }

    /**
     * Отмена модерации
     *
     * @param integer $bannerId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function cancelModeration(int $bannerId)
    {
        $banner = $this->getBanner($bannerId);

        $banner->cancelModeration();
    }

    /**
     * Обрабатываем клик на баннере
     *
     * @param Banner $banner
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function click(Banner $banner)
    {
        $banner->click();
    }

    /**
     * Получим случайный баннер для отображения
     *
     * @param integer $categoryId
     * @param integer $regionId
     * @param string $format
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getRandomForView($categoryId, $regionId, $format)
    {
        $response = $this->client->search([
            'index' => 'banners',
            'body' => [
                '_source' => ['id'],
                'size' => 5,
                /*'sort' => [
                    '_script' => [
                        'script' => 'Math.random() * 200000'
                    ],
                    'type' => 'number',
                    'params' => new \stdClass(),
                    'order' => 'asc'
                ],*/
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['status' => Banner::STATUS_ACTIVE]],
                            ['term' => ['format' => $format]],
                            ['term' => ['categories' => $categoryId ?: 0]],
                            ['term' => ['regions' => $regionId ? $regionId : 0]],
                        ],
                    ],
                ],
            ],
        ]);

        if (!$ids = array_column($response['hits']['hits'], '_id')) {
            return null;
        }

        $banner = Banner::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderByRaw('array_position(ARRAY['.implode(',', $ids).']::int[], id::int)')
            ->first()
        ;

        if (!$banner) {
            return null;
        }

        $banner->view();

        return $banner;
    }

    /**
     * Получим баннер по id
     *
     * @param integer $id
     *
     * @return Banner
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function getBanner($id): Banner
    {
        return Banner::findOrFail($id);
    }
}