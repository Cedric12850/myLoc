<?php 

namespace App\Service;

use App\Repository\CategoryRepository;

class GetCategories 
{
    private $repo;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->repo = $categoryRepository;
    }

    public function getAllCategories()
    {
        $categories = $this->repo->findAll();
        return $categories;
    } 
}