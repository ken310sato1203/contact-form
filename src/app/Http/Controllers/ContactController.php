<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Models\Category;

class ContactController extends Controller
{

    private $gender_list = ['男性', '女性', 'その他'];

    public function contact()
    {
        $categories = Category::all();
        $genders = $this->gender_list;

        return view('contact', compact('categories', 'genders'));
    }

    public function confirm(ContactRequest $request)
    {
        $contact = $request->only([
            'last_name',
            'first_name',
            'gender',
            'email',
            'tel1',
            'tel2',
            'tel3',
            'address',
            'building',
            'category_id',
            'detail'
        ]);
        $tel = $request->tel1 . $request->tel2 . $request->tel3;
        $name = $request->last_name . ' ' . $request->first_name;
        $gender_content = $this->gender_list[$request->gender - 1];
        $category = Category::CategorySearch($request->category_id)->first();

        return view('confirm', compact('contact', 'tel', 'name', 'gender_content', 'category'));
    }

    public function store(Request $request)
    {

        if ($request->has("back")) {
            return redirect('/')->withInput();
        }

        $contact = $request->only([
            'category_id',
            'first_name',
            'last_name',
            'gender',
            'email',
            'tel',
            'address',
            'building',
            'detail'
        ]);
        Contact::create($contact);

        return view('thanks');
    }

    public function admin(Request $request)
    {
        $contacts = Contact::all();
        $categories = Category::all();
        $genders = $this->gender_list;

        return view('admin', compact('contacts', 'categories', 'genders'));
    }

    public function search(Request $request)
    {
        $contacts = Contact::with('category')
            ->keywordSearch($request->keyword)
            ->genderSearch($request->gender)
            ->categorySearch($request->category_id)
            ->dateSearch($request->date)
            ->get();
        $categories = Category::all();
        $genders = $this->gender_list;

        return view('admin', compact('contacts', 'categories', 'genders'));
    }
}
