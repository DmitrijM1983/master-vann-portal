<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Requests\UpdateMasterInfoRequest;
use App\Models\City;
use App\Models\CityUser;
use App\Models\Feedback;
use App\Models\JobImage;
use App\Models\MasterInfo;
use App\Models\Message;
use App\Models\Order;
use App\Models\Report;
use App\Models\ServiceUser;
use App\Models\Support;
use App\Services\IMasterService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MasterController extends Controller
{
    public function __construct(private readonly IMasterService $masterService)
    {}

    public function searchForm(): View
    {
        $data = $this->masterService->searchData();

        if (Auth::check()) {
            $messagesData = $this->masterService->getMessagesInfo(Auth::user()->id);
            $orders = Order::where('user_from', Auth::user()->id)->orderByDesc('created_at')->get();
            $supports = Support::where('user_id', Auth::user()->id)->get();

            return view('master.search', [
                'cities' => $data[0],
                'services' => $data[1],
                'messages' => $messagesData['messages'],
                'noReadAnswerCount' => $messagesData['noReadAnswerCount'],
                'noReadAnswer' => $messagesData['noReadAnswer'],
                'orders' => $orders,
                'supports' => $supports
            ]);
        }

        return view('master.search', ['cities' => $data[0], 'services' => $data[1]]);
    }

    public function search(Request $request): View
    {
        $masters = $this->masterService->searchResult($request);

        if (Auth::check()) {
            $messagesData = $this->masterService->getMessagesInfo(Auth::user()->id);
            $orders = Order::where('user_from', Auth::user()->id)->orderByDesc('created_at')->get();
            $supports = Support::where('user_id', Auth::user()->id)->get();

            return view('master.search-result', [
                'masters' => $masters,
                'messages' => $messagesData['messages'],
                'noReadAnswerCount' => $messagesData['noReadAnswerCount'],
                'noReadAnswer' => $messagesData['noReadAnswer'],
                'orders' => $orders,
                'supports' => $supports
            ]);
        }

        return view('master.search-result', ['masters' => $masters]);
    }

    public function masterCard(User $user, Request $request): View
    {
        $sort = $request->input('sort', 'newest');
        $data = $this->masterService->getMasterInfo($user, $sort);

        if (Auth::check()) {
            $messagesData = $this->masterService->getMessagesInfo(Auth::user()->id);
            $orders = Order::where('user_from', Auth::user()->id)->orderByDesc('created_at')->get();
            $supports = Support::where('user_id', Auth::user()->id)->get();

            return view('master.master_card', [
                'master' => $user,
                'images' => $data[0],
                'servicePrices' => $data[1],
                'feedbacks' => $data[2],
                'countOfFiveStarFeedbacks' => $data[3],
                'countOfFourStarFeedbacks' => $data[4],
                'countOfThreeStarFeedbacks' => $data[5],
                'countOfTwoStarFeedbacks' => $data[6],
                'countOfOneStarFeedbacks' => $data[7],
                'countOfWithoutStarFeedbacks' => $data[8],
                'messages' => $messagesData['messages'],
                'noReadAnswerCount' => $messagesData['noReadAnswerCount'],
                'noReadAnswer' => $messagesData['noReadAnswer'],
                'orders' => $orders,
                'supports' => $supports
            ]);
        }

        return view('master.master_card', [
            'master' => $user,
            'images' => $data[0],
            'servicePrices' => $data[1],
            'feedbacks' => $data[2],
            'countOfFiveStarFeedbacks' => $data[3],
            'countOfFourStarFeedbacks' => $data[4],
            'countOfThreeStarFeedbacks' => $data[5],
            'countOfTwoStarFeedbacks' => $data[6],
            'countOfOneStarFeedbacks' => $data[7],
            'countOfWithoutStarFeedbacks' => $data[8]
        ]);
    }

    public function masterAccount(int $userId): View
    {
        $user = User::find($userId);
        $cities =  City::pluck('name')->toArray();
        $cityUser = CityUser::where('user_id', $userId)->get();
        $servicesUser = ServiceUser::where('user_id', $userId)->get();
        $messages = Message::where('user_to', $userId)->orderByDesc('created_at')->get();
        $noReadMessages = Message::where('user_to', $userId)->where('is_read', false)->get();
        $noReadMessagesCount = $noReadMessages->count();
        $jobImages = JobImage::where('user_id', $userId)->get();
        $orders = Order::where('user_to', $userId)->orderByDesc('created_at')->get();
        $feedbacks = Feedback::where('master_id', $userId)->whereNotNull('user_id')->get();
        $noReadFeedbacks = Feedback::where('master_id', $userId)->whereNotNull('user_id')->where('is_read', false)->get();
        $noReadFeedbacks = $noReadFeedbacks->count();
        $supports = Support::where('user_id', $userId)->get();
        $reports = Report::where('user_id', $userId)->get();

        return view('master.master_account', [
            'user' => $user,
            'cities' => $cities,
            'cityUser' => $cityUser,
            'servicesUser' => $servicesUser,
            'messages' => $messages,
            'noReadMessages' => $noReadMessages,
            'noReadMessagesCount' => $noReadMessagesCount,
            'jobImages' => $jobImages,
            'orders' => $orders,
            'feedbacks' => $feedbacks,
            'noReadFeedbacks' => $noReadFeedbacks,
            'supports' => $supports,
            'reports' => $reports
        ]);
    }

    public function updateMasterInfo(int $id, UpdateMasterInfoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $master = MasterInfo::where('user_id', $id)->first();
        if (!$master) {
            $master = MasterInfo::create(['user_id' => $id]);
        }
        $this->masterService->updateMasterInfo($data, $master, $request);

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function setCity(int $id, Request $request): RedirectResponse
    {
        $city = City::where('name', $request->city)->first();

        if ($city) {
            CityUser::create(['city_id' => $city->id, 'user_id' => $id]);
        }

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function destroyCity(int $id, Request $request): RedirectResponse
    {
        $city = City::where('name', $request->city)->first();

        if ($city) {
            CityUser::where('city_id',$city->id)->where('user_id',$id)->delete();
        }

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function setServices(int $id, Request $request): RedirectResponse
    {
        $this->masterService->setServices($id, $request);

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function setJobImages(int $id, Request $request): RedirectResponse
    {
        $imageArr = $request->validate([
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000',
            'title' => 'sometimes|max:255',
            'description' => 'sometimes|max:1000'
        ]);

        if (isset($imageArr['image'])) {
            $path = Storage::putFile($id . 'masters/' . '/job_images', $request->file('image'), 'public');

            JobImage::create([
                'user_id' => $id,
                'image' => $path,
                'title' => $imageArr['title'] ?? null,
                'description' => $imageArr['description'] ?? null
            ]);
        }

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function editJobImage(int $id, Request $request): RedirectResponse
    {
        $imageArr = $request->validate([
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000',
            'title' => 'sometimes|max:255',
            'description' => 'sometimes|max:1000'
        ]);

        $jobImage = JobImage::find($request->input('image_id'));

        if (isset($imageArr['image'])) {
            Storage::delete($jobImage->image);
            $path = Storage::putFile('masters/' . $id . '/job_images', $request->file('image'), 'public');

            $imageArr['image'] = $path;
        }
        $jobImage->update($imageArr);

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function deleteJobImage(int $id, int $imageId): RedirectResponse
    {
        $jobImage = JobImage::find($imageId);

        if ($jobImage) {
            Storage::delete($jobImage->image);
            $jobImage->delete();
        }

        return redirect()->intended('profile/' . $id)->with('success', 'Изменения успешно сохранены!');
    }

    public function saveReport(ReportRequest $request)
    {
        $report = $request->validated();

        if ($report) {
            Report::create($report);

            return redirect()->intended('profile/' . $report['user_id'])->with('success', 'Отчёт успешно добавлен!');
        }

        return redirect()->intended('profile/' . $report['user_id'])->with('error', 'Проверьте правильность заполнения отчёта!');
    }
}
