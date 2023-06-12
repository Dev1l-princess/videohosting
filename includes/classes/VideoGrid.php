<?php
	class VideoGrid{
		private $con, $userLoggedInObj;
		private $largeMode = false;
		private $gridClass = "videoGrid";

		public function __construct($con, $userLoggedInObj){
			$this->con = $con;
			$this->userLoggedInObj = $userLoggedInObj;
		}

		public function create($videos, $title, $showFilter){

			if($videos == null){
				$gridItems = $this->generateItems($showFilter);
			}else{
				$gridItems = $this->generateItemsFromVideos($videos);
			}

			$header = "";

			if($title != null){
				$header = $this->createGridHeader($title, $showFilter);
			}

			return "$header
					<div class='$this->gridClass'>
						$gridItems
					</div>";
		}

		public function generateItems($showFilter) {
            $recommendations = new RecommendationSystem($this->con, $this->userLoggedInObj);


            if (isset($_GET['category'])) {
                $category = $_GET['category'];
                $query = $this->con->prepare("SELECT * FROM videos WHERE category = $category ORDER BY rand() DESC LIMIT 30");
                $query->execute();
            } else {
                $query = $recommendations->getRecommendedVideos();
            }
			$elementHtml = "";
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$video = new Video($this->con, $row, $this->userLoggedInObj);
				$item = new VideoGridItem($video, $this->largeMode);
				$elementHtml .= $item->create();
			}

			return $elementHtml;
		}

		public function generateItemsFromVideos($videos){
			$elementHtml = "";

			foreach ($videos as $video){
				$item = new VideoGridItem($video, $this->largeMode);
				$elementHtml .= $item->create();
			}
			return $elementHtml;
		}

		public function createGridHeader($title, $showFilter){
			$filter = "";
            $headerClass = "";

			if($showFilter){
				$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				
				$urlArray = parse_url($link);
				$query = $urlArray["query"];
				parse_str($query, $params);
				unset($params["orderBy"]);
				$newQuery = http_build_query($params);
				$newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;
				$filter = "<div class='right'>
							    <span>Сортировать по:</span>
								<a href='$newUrl&orderBy=uploadDate'>Дате загрузки</a>
								<a href='$newUrl&orderBy=views'>Просмотрам</a>
							</div>";
			}
            elseif ($title == "Рекомендованные") {
                $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                $query = $this->con->prepare("SELECT * FROM categories");
                $query->execute();
                $categories = $query->fetchAll(PDO::FETCH_ASSOC);

                $urlArray = parse_url($link);
                $query = $urlArray["query"];
                parse_str($query, $params);
                unset($params["category"]);
                $newQuery = http_build_query($params);
                $newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;
                $filter = "<div class='videoGridSubtitle'>
                                <a href='$newUrl'>Все</a>";
                foreach ($categories as $category) {
                    $filter .= "<a href='$newUrl&category=" . $category['id'] . "'>" . $category['name'] . "</a>" ;
                }
                $filter .=	"</div>";
                $headerClass = "index";
            }

			return "<div class='videoGridHeader $headerClass'>
						<div class='left'>
							$title
						</div>
						$filter
					</div>";
		}

        public function createForIndex($videos, $title, $showFilter){
            $this->gridClass .= " index";
            return $this->create($videos, $title, $showFilter);
        }

		public function createLarge($videos, $title, $showFilter){
			$this->gridClass .= " large";
			$this->largeMode = true;
			return $this->create($videos, $title, $showFilter);
		}

	}
?>