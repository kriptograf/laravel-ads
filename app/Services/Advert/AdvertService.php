<?php

declare(strict_types=1);

namespace App\Services\Advert;

use App\Http\Requests\Cabinet\Advert\CreateRequest;
use App\Http\Requests\Cabinet\Advert\PhotoRequest;
use App\Models\Advert;
use App\Models\Category;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
     * @return mixed
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
     * @param integer                                             $id
     * @param \App\Http\Requests\Cabinet\Advert\PhotoRequest $request
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
     * @param integer $id
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function sendToModeration($id)
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    /**
     * Получить объявление
     *
     * @param integer $id Идентификатор объявления
     *
     * @return Advert|null
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function getAdvert($id)
    {
        return Advert::findOrFail($id);
    }
}