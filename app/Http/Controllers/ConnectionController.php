<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Feedback;
use App\Models\MasterInfo;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use App\Services\IConnectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ConnectionController extends Controller
{
    public function __construct(private readonly IConnectionService $connectionService)
    {
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $message = $request->validate([
                'user_to' => 'required',
                'user_from' => 'required',
                'content' => 'sometimes|max:2000',
                'images' => 'nullable|array',
            ]
        );

        $imagePaths = [];

        if (array_key_exists('images', $message)) {
            foreach ($message['images'] as $image) {
                $path = Storage::putFile($message['user_to'] . '/messages', $image, 'public');
                $imagePaths[] = $path;
            }
            $message['images'] = json_encode($imagePaths);
        }

        Message::create($message);

        return redirect()->intended('/master_card/' . $message['user_to'])->with('success', 'Сообщение успешно отправлено.');
    }

    public function sendAnswer(Request $request): RedirectResponse
    {
        $answer = $request->validate([
                'user_to' => 'required',
                'message_id' => 'required',
                'content' => 'sometimes|max:2000',
                'images' => 'nullable|array',
            ]
        );

        $imagePaths = [];

        if (array_key_exists('images', $answer)) {
            foreach ($answer['images'] as $image) {
                $path = Storage::putFile($answer['user_to'] . '/messages', $image, 'public');
                $imagePaths[] = $path;
            }
            $answer['images'] = json_encode($imagePaths);
        }

        Answer::create($answer);

        return redirect()->intended('/profile/' . $answer['user_to'])->with('success', 'Сообщение успешно отправлено.');
    }

    public function markMessagesAsRead(Request $request): void
    {
        $messages = $request->input('messages');

        Message::whereIn('id', $messages)->update(['is_read' => true]);
    }

    public function markAnswerAsRead(Request $request): void
    {
        $answers = $request->input('answers');

        Answer::whereIn('id', $answers)->update(['is_read' => true]);
    }

    public function makeOrder(Request $request): RedirectResponse
    {
        $orderData = $request->validate([
            'user_to' => 'required',
            'user_from' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'content' => 'sometimes|max:2000'
        ]);

        $order = Order::where('user_to', $orderData['user_to'])->where('user_from', $orderData['user_from'])->first();

        if ($order) {

            return redirect()->intended('/master_card/' . $orderData['user_to'])->with('error', 'Вы уже отправили заявку ранее.');
        }

        Order::create($orderData);
        $msg = $this->msg($orderData['user_to']);

        if ($msg) {

            return redirect()->intended('/master_card/' . $orderData['user_to'])->with('success', 'Заявка успешно отправлена.');
        }

        return redirect()->intended('/master_card/' . $orderData['user_to'])->with('error', 'Заявка не отправлена. Попробуйте позже.');
    }

    public function msg($master_id): bool
    {
        return true;
        $user = User::find($master_id);

        $response = Http::get('https://sms.ru/sms/send', [
            'api_id' => env('SMSRU_API_KEY'),
            'to'     => $user->phone,
            'msg'    => 'Новый заказ на реставрацию ванны. Подробности в почте.',
            'json'   => 1
        ]);

        if ($response->json('status') === 'OK') {

            return true;
        }

        return false;
    }

    public function sendFeedback(Request $request): RedirectResponse
    {
        $feedbackData = $request->validate([
            'master_id' => 'sometimes',
            'user_id' => 'sometimes',
            'service_id' => 'sometimes',
            'grade' => 'sometimes',
            'content' => 'string|required|max:2000',
            'service_provided' => 'sometimes',
            'images' => 'nullable|array'
        ]);

        return $this->connectionService->checkFeedback($feedbackData);
    }

    public function sendAnswerFeedback(Request $request)
    {
        $answer = $request->validate([
            'answer' => 'required|max:255'
        ]);

        $feedback = Feedback::find($request->input('id'));

        if ($feedback->answer === null) {
            $feedback->update(['answer' => $answer['answer']]);
        }

        return redirect()->intended('profile/' . $feedback->master_id)->with('success', 'Ответ сохранен.');
    }

    public function markFeedbacksAsRead(Request $request): void
    {
        $feedbacks = $request->input('feedbacks');

        Feedback::whereIn('id', $feedbacks)->update(['is_read' => true]);
    }

    public function contactSupport(Request $request): RedirectResponse
    {
        $support = $request->validate([
            'content' => 'required|string|max:2000',
            'email' => 'sometimes|email',
            'photo' => 'sometimes|file|max:5000',
            'user_id' => 'sometimes'
        ]);

        $this->connectionService->createSupportTicket($support);

        if (array_key_exists('user_id', $support)) {
            $user = User::find($support['user_id']);

            if ($user->role == '1') {
                return redirect()->intended('profile/' . $user->id)->with('success', 'Обращение создано.');
            }
        }

        return redirect()->intended()->with('success', 'Обращение создано.');
    }
}
