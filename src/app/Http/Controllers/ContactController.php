<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    // 性別の種類
    private $gender_list = ['男性', '女性', 'その他'];

    // お問い合わせデータの入力
    public function contact()
    {
        $categories = Category::all();
        $genders = $this->gender_list;

        return view('contact', compact('categories', 'genders'));
    }

    // お問い合わせデータの登録
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

    // お問い合わせデータの登録時の確認
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

    // お問い合わせデータの検索
    public function search(Request $request)
    {
        $contacts = Contact::with('category')
            ->keywordSearch($request->keyword)
            ->genderSearch($request->gender)
            ->categorySearch($request->category_id)
            ->dateSearch($request->date)
            ->paginate(7);
        $categories = Category::all();
        $genders = $this->gender_list;

        if (count($contacts) == 0) {
            session()->flash('message', '検索結果は0件でした');
        } else {
            session()->flash('message', '');
        }

        return view('admin', compact('contacts', 'categories', 'genders'));
    }

    // お問い合わせデータの削除
    public function delete(Request $request)
    {
        Contact::find($request->id)->delete();
        return redirect(
            '/admin?keyword=' . $request->keyword
                . '&gender=' . $request->gender
                . '&category_id=' . $request->category_id
                . '&date=' . $request->date
        )
            ->with('message', 'お問い合わせを削除しました');
    }

    // お問い合わせデータのCSVダウンロード
    public function download(request $request)
    {
        $fileName = "お問い合わせデータ.csv";

        $csvHeader = [
            'contacts.id',
            'contacts.last_name',
            'contacts.first_name',
            'contacts.gender',
            'contacts.email',
            'contacts.tel',
            'contacts.address',
            'contacts.building',
            'categories.content',
            'contacts.detail',
            'contacts.created_at',
            'contacts.updated_at'
        ];

        $csvData = Contact::select($csvHeader)
            ->selectRaw('CASE contacts.gender WHEN 1 THEN "男性" WHEN 2 THEN "女性"  WHEN 3 THEN "その他" END as gender')
            ->join('categories', 'contacts.category_id', '=', 'categories.id')
            ->keywordSearch($request->keyword)
            ->genderSearch($request->gender)
            ->categorySearch($request->category_id)
            ->dateSearch($request->date)
            ->get()->toArray();

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            mb_convert_variables('SJIS', 'UTF-8', $csvHeader);
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                mb_convert_variables('SJIS', 'UTF-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ]);

        return $response;
    }
}
