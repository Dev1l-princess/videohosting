<?php

class RecommendationSystem
{
    private $con;
    private $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }
    public function getRandomVideos()
    {
        // Получаем случайные видео
        $query = $this->con->prepare("SELECT * FROM videos ORDER BY rand() DESC LIMIT 30");
        $query->execute();
        return $query;
    }
    public function getVideosBySimilarUsers() {
        // Получаем ID авторизованного пользователя
        $userId = $this->userLoggedInObj->getId();

        // Получаем список ID десяти последних просмотреных видео
        $query = $this->con->prepare("SELECT videoId FROM watchHistory WHERE userId  = :userId ORDER BY viewDate DESC LIMIT 10");
        $query->bindValue(':userId', $userId);
        $query->execute();
        $videoIds = $query->fetchAll(PDO::FETCH_COLUMN);

        if ($videoIds) {
            // Если есть просмотренные видео, получаем список пользователей, похожих на текущего пользователя, основываясь на их истории просмотров
            $query = $this->con->prepare("SELECT DISTINCT userId FROM watchHistory WHERE videoId IN (" . implode(',', array_fill(0, count($videoIds), '?')) . ")");
            $query->execute($videoIds);
            $similarUserIds = $query->fetchAll(PDO::FETCH_COLUMN);

            if ($similarUserIds){
                // Если есть похожие пользователи, получаем список всех видео, которые просмотрели пользователи, похожие на текущего пользователя
                $query = $this->con->prepare("SELECT DISTINCT videoId FROM watchHistory WHERE userId IN (" . implode(',', array_fill(0, count($similarUserIds), '?')) . ")");
                $query->execute($similarUserIds);
                $similarVideoIds = $query->fetchAll(PDO::FETCH_COLUMN);

                // Получаем среднее время длительности просмотренных видео
                $query = $this->con->prepare("SELECT AVG(duration) AS avg_duration FROM videos WHERE id IN (" . implode(',', array_fill(0, count($videoIds), '?')) . ")");
                $query->execute($videoIds);
                $avgDuration = $query->fetchColumn();

                //Вычитаем из массива рекомендуемых видео просмотренные
                $recommendedVideoIds = array_diff($similarVideoIds, $videoIds);

                if ($recommendedVideoIds){
                    // Если остались непросмотренные видео, получаем список видео, которые смотрели похожие пользователи, имеют одну из просмотренных категорий и похожую продолжительность
                    $query = $this->con->prepare("SELECT id FROM videos WHERE id IN (" . implode(',', array_fill(0, count($recommendedVideoIds), '?')) . ") AND duration BETWEEN ? AND ?");
                    $params = array_merge($recommendedVideoIds, [$avgDuration - 30, $avgDuration + 30]);
                    $query->execute($params);
                }
            }
        }
        return $query;
    }

    public function getVideosByWatchHistory() {
        // Получаем ID авторизованного пользователя
        $userId = $this->userLoggedInObj->getId();

        // Получаем список ID десяти последних просмотреных видео
        $query = $this->con->prepare("SELECT videoId FROM watchHistory WHERE userId  = :userId ORDER BY viewDate DESC LIMIT 10");
        $query->bindValue(':userId', $userId);
        $query->execute();
        $videoIDs = $query->fetchAll(PDO::FETCH_COLUMN);

        if($videoIDs){
            // Если есть просмотренные видео, получаем список всех категорий этих видео
            $query = $this->con->prepare("SELECT category FROM videos WHERE id IN (" . str_repeat('?,', count($videoIDs)-1) . "?)");
            $query->execute($videoIDs);
            $categories = $query->fetchAll(PDO::FETCH_COLUMN);

            // Получаем среднее время длительности просмотренных видео
            $query = $this->con->prepare("SELECT AVG(duration) AS avg_duration FROM videos WHERE id IN (" . str_repeat('?,', count($videoIDs)-1) . "?)");
            $query->execute($videoIDs);
            $avgDuration = $query->fetch(PDO::FETCH_ASSOC)['avg_duration'];

            // Получаем список видео, которые имеют одну из категорий и похожую продолжительность
            $query = $this->con->prepare("SELECT id FROM videos WHERE category IN (" . implode(',', array_fill(0, count($categories), '?')) . ") AND duration BETWEEN ? AND ? AND id NOT IN (" . implode(',', array_fill(0, count($videoIDs), '?')) . ")");
            $params = array_merge($categories, [$avgDuration - 30, $avgDuration + 30], $videoIDs);
            $query->execute($params);
        }
        return $query;
    }

    public function getRecommendedVideos() {
        // Проверяем, авторизован ли пользователь
        if ($this->userLoggedInObj->getId()){

            // Получаем идентификаторы рекомендуемых видео от похожих пользователей и по истории просмотров текущего пользователя
            $videoIdsBySimilarUsers = $this->getVideosBySimilarUsers()->fetchAll(PDO::FETCH_COLUMN);
            $videoIdsByWatchHistory = $this->getVideosByWatchHistory()->fetchAll(PDO::FETCH_COLUMN);

            // Если есть рекомендованные видео, объединяем их идентификаторы и удаляем дубликаты
            if ($videoIdsBySimilarUsers || $videoIdsByWatchHistory) {
                $recommendedVideosIds = array_unique(array_merge($videoIdsBySimilarUsers, $videoIdsByWatchHistory), SORT_REGULAR);

                // Получаем рекомендованным видео из базы данных
                $recommendedVideos = $this->con->prepare("SELECT * FROM videos WHERE id IN (" . str_repeat('?,', count($recommendedVideosIds)-1) . "?)");
                $recommendedVideos->execute($recommendedVideosIds);
            }

            // Если нет рекомендованных видео, выбираем случайные видео
            else {
                $recommendedVideos = $this->getRandomVideos();
            }
        }

        // Если пользователь не авторизован, выбираем случайные видео
        else {
            $recommendedVideos = $this->getRandomVideos();
        }
        return $recommendedVideos;
    }
}