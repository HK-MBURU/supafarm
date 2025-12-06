<?php
namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Just load active categories - no need to specify columns
        $categories = Category::where('is_active', 1)->get();

        // Fetch the about section data - no need to specify columns
        $about = About::where('published_at', '<=', now())
            ->latest('published_at')
            ->first();

        return view('home', compact('categories', 'about'));
    }

    public function loadSection($section)
    {
        // Validate section name
        $allowedSections = ['popular-products', 'latest-news', 'gallery-scroll', 'about', 'seo'];

        if (!in_array($section, $allowedSections)) {
            abort(404);
        }

        // Convert section name to view partial name
        $viewName = 'partials.' . $section;

        // Load specific data for each section
        switch($section) {
            case 'about':
                $about = About::where('published_at', '<=', now())
                    ->latest('published_at')
                    ->first();
                return view($viewName, compact('about'));

            default:
                return view($viewName);
        }
    }

    public function products()
    {
        return view('products');
    }

    public function productCategory($category)
    {
        return view('product-category', ['category' => $category]);
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function cart()
    {
        return view('cart');
    }
}
