<?php

namespace Sloop\Service;

use Psr\Container\ContainerInterface;
use Illuminate\Database\QueryException;
use Sloop\Model\Article;
use Sloop\Model\URL;

class ArticleService extends AbstractService
{
    protected $appContainer;

    public function __construct(ContainerInterface $container)
    {
        $this->appContainer = $container;
    }

    public function createArticle($articleArray)
    {
        if (isset($articleArray['articleId'])) {
            return $this->update($articleArray);
        } else {
            return $this->create($articleArray);
        }

    }

    public function getArticle($id)
    {
        $article = Article::with([
            'url' => function ($query) {
                $query->where('active', 1);
            }
        ])->find($id);
        return $article;
    }

    public function getAll()
    {
        $articles = Article::with([
            'url' => function ($query) {
                $query->where('active', 1);
            }
        ])->get();
        return $articles->sortByDesc(function ($article) {
            return $article->created_at;
        });
    }

    public function getAllPublished()
    {
        $articles = Article::with([
            'url' => function ($query) {
                $query->where('active', 1);
            }
        ])->get();
        $filtered = $articles->filter(function ($value, $key) {
            return !!$value['published'];
        });
        return $filtered->sortByDesc(function ($article) {
            return $article->created_at;
        });
    }

    public function getLatestArticle()
    {
        return $this->getAll()->first();
    }

    protected function create($articleArray)
    {
        $article = new Article();
        $article->published = array_key_exists('published', $articleArray) ? true : false;
        $result = $this->saveArticle($article, $articleArray);
        if ($result !== true) {
            return $articleArray;
        }

        $urlResult = $this->createUrl($article, $articleArray['path']);
        if ($urlResult !== true) {
            return $articleArray;
        }
        return $result && $urlResult;
    }

    protected function update($articleArray)
    {
        $article = Article::find($articleArray['articleId']);
        $article->published = array_key_exists('published', $articleArray) ? true : false;
        $result = $this->saveArticle($article, $articleArray);
        if ($result !== true) {
            return $articleArray;
        }

        // handle URL update
        $urls = $article->url->all();
        $insert = $this->updateUrls($urls, $articleArray['path']);
        if ($insert) {
            $urlResult = $this->createUrl($article, $articleArray['path']);
            if ($urlResult !== true) {
                return $articleArray;
            }
        }

        return $result;
    }

    private function updateUrls($urls, $newUrl)
    {
        $insert = true;
        array_walk($urls, function ($url) use ($newUrl, &$insert) {
            if ($url->path == $newUrl) {
                $insert = false;
                $url->active = 1;
            } else {
                $url->active = 0;
            }

            try {
                $url->save();
            } catch (QueryException $e) {
                error_log($e);
            }
        });
        return $insert;
    }

    private function saveArticle(&$article, $articleArray)
    {
        $article->title = $articleArray['title'];
        $article->description = $articleArray['description'];
        $article->article_text = $articleArray['text'];
        $article->issue_id = $articleArray['zineIssueId'];
        $result = null;
        try {
            $result = $article->save();
        } catch (QueryException $e) {
            error_log($e);
        }
        return $result;
    }

    private function createUrl($article, $urlPath)
    {
        $url = new URL();
        $url->path = $urlPath;
        $url->active = 1;
        $result = null;
        try {
            $result = $article->url()->save($url);
        } catch (QueryException $e) {
            error_log($e);
        }
        return $result;
    }
}