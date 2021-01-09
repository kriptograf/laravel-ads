<?php

declare(strict_types=1);

namespace App\Services\Advert;

use App\Http\Requests\Cabinet\Advert\AttributesRequest;
use App\Http\Requests\Cabinet\Advert\CreateRequest;
use App\Http\Requests\Cabinet\Advert\PhotoRequest;
use App\Http\Requests\Cabinet\Advert\RejectRequest;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Сервис управления объявлениями
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class AdvertService
{
    /**
     * Создание объявления
     *
     * @param int           $userId
     * @param int           $categoryId
     * @param int           $regionId
     * @param CreateRequest $request
     *
     * @return Advert
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function create(int $userId, int $categoryId, int $regionId, CreateRequest $request): Advert
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);
        /** @var Region $region */
        $region = Region::findOrFail($regionId);

        return DB::transaction(function () use ($request, $user, $category, $region) {
            /** @var Advert $advert Создадим экземпляр объявления */
            $advert = Advert::make([
                'title' => $request['title'],
                'address' => $request['address'],
                'content' => $request['content'],
                'price' => $request['price'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            // -- Ассоциируем его с пользователем через связь user()
            $advert->user()->associate($user);
            // -- Ассоциируем его с пользователем через связь category()
            $advert->category()->associate($category);
            // -- Ассоциируем его с пользователем через связь region()
            $advert->region()->associate($region);

            $advert->saveOrFail();

            // -- Сохраняем динамические атрибуты объявления
            foreach ($category->getAllAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;

                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            return $advert;
        });
    }

    /**
     * Добавить фото
     *
     * @param integer      $id      Идентификатор объявления
     * @param PhotoRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function addPhotos($id, PhotoRequest $request)
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts'),
                ]);
            }
        });
    }

    /**
     * Отправить объявление на модерацию
     *
     * @param integer $id Идентификатор объявления
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function sendToModeration($id)
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    /**
     * Модерация объявления
     *
     * @param integer $id Идентификатор объявления
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function moderate($id)
    {
        $advert = $this->getAdvert($id);

        if (null === $advert) {
            throw new \DomainException(__('Advert not found!'));
        }

        $advert->moderate(Carbon::now());
    }

    /**
     * Закрываем истекшее объявление
     *
     * @param integer $id
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function close($id)
    {
        $advert = $this->getAdvert($id);

        if (null === $advert) {
            throw new \DomainException(__('Advert not found!'));
        }

        $advert->close();
    }

    /**
     * Отказ в публикации объявления
     *
     * @param integer       $id      Идентификатор объявления
     * @param RejectRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function reject($id, RejectRequest $request)
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }

    /**
     * Редактируем динамические атрибуты объявления
     *
     * @param integer           $id      Идентификатор объявления
     * @param AttributesRequest $request
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function editAttributes($id, AttributesRequest $request)
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            // -- Удаляем старые значения
            $advert->values()->delete();

            // -- Сохраняем динамические атрибуты объявления
            foreach ($advert->category->getAllAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;

                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }
        });
    }

    /**
     * Удаление объявления
     *
     * @param integer $id Идентификатор объявления
     *
     * @throws \Exception
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function remove($id)
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }

    /**
     * Получить объявление
     *
     * @param integer $id Идентификатор объявления
     *
     * @return Advert|null
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function getAdvert($id): ?Advert
    {
        return Advert::findOrFail($id);
    }
}