<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\Advert;
use App\Models\User;

/**
 * Сервис упроавления избранным
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class FavoriteService
{
    /**
     * Добавить в избранное
     *
     * @param integer $userId
     * @param integer $advertId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function add($userId, $advertId): void
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->addToFavorites($advert->id);
    }

    /**
     * Удалить из избранного
     *
     * @param integer $userId
     * @param integer $advertId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function remove($userId, $advertId)
    {
        $user = $this->getUser($userId);
        $advert = $this->getAdvert($advertId);

        $user->removeFromFavorites($advert->id);
    }

    /**
     * @param $userId
     *
     * @return Advert[]
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getUserFavorites($userId)
    {
        $user = $this->getUser($userId);

        /** @var Advert[] $adverts */
        $adverts = $user->favorites()->orderByDesc('id')->paginate(20);

        return $adverts;
    }

    /**
     * Получить объект пользователя
     *
     * @param integer $userId
     *
     * @return User
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function getUser($userId): User
    {
        return User::findOrFail($userId);
    }

    /**
     * Получить объект объявления
     *
     * @param integer $advertId
     *
     * @return Advert
     * @author Виталий Москвин <foreach@mail.ru>
     */
    private function getAdvert($advertId): Advert
    {
        return Advert::findOrFail($advertId);
    }
}
