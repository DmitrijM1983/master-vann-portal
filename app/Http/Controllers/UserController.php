<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\MasterInfo;
use App\Models\Order;
use App\Models\Support;
use App\Models\User;
use App\Services\IMasterService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class UserController extends Controller
{
    use Notifiable;

    public function __construct(private readonly IMasterService $masterService)
    {}

    public function index(): View
    {
        if (Auth::check()) {
            $messagesData = $this->masterService->getMessagesInfo(Auth::user()->id);
            $orders = Order::where('user_from', Auth::user()->id)->orderByDesc('created_at')->get();
            $supports = Support::where('user_id', Auth::user()->id)->get();

            return view('index', [
                'messages' => $messagesData['messages'],
                'noReadAnswerCount' => $messagesData['noReadAnswerCount'],
                'noReadAnswer' => $messagesData['noReadAnswer'],
                'orders' => $orders,
                'supports' => $supports
            ]);
        }

        return view('index');
    }

    public function updateProfile(int $id, UpdateProfileRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
        ]);

        User::where('id', $id)->update($data);

        return redirect()->route('profile', ['id' => $id]);
    }
}
