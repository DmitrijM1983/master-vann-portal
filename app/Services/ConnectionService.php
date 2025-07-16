<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\MasterInfo;
use App\Models\Order;
use App\Models\Message;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ConnectionService implements IConnectionService
{
    public function checkFeedback(array $feedbackData): RedirectResponse
    {
        if (array_key_exists('user_id', $feedbackData)) {
            $feedback = Feedback::where('user_id', $feedbackData['user_id'])
                ->where('master_id', $feedbackData['master_id'])
                ->first();
            if ($feedback) {
                return redirect()
                    ->intended('/master_card/' . $feedbackData['master_id'])
                    ->with('error', 'Вы уже оставляли отзыв ранее.');
            }
                if (array_key_exists('service_provided', $feedbackData) && $feedbackData['service_provided'] === '1') {
                    $connect = $this->checkConnect($feedbackData['user_id'], $feedbackData['master_id']);
                }
                if (isset($connect) && $connect === true) {
                    if (array_key_exists('service_id', $feedbackData) && $feedbackData['grade'] != null) {
                        if (array_key_exists('images', $feedbackData)) {
                            $imagePaths = [];

                            foreach ($feedbackData['images'] as $image) {
                                $path = Storage::putFile($feedbackData['master_id'] . '/' . 'feedbacks' . '/', $image, 'public');

                                $imagePaths[] = $path;
                            }
                            $feedbackData['images'] = json_encode($imagePaths);
                        }

                        Feedback::create($feedbackData);
                        $this->setNewRating($feedbackData['master_id']);

                        return redirect()
                            ->intended('/master_card/' . $feedbackData['master_id'])
                            ->with('success', 'Отзыв успешно добавлен.');
                    }

                        return redirect()
                            ->intended('/master_card/' . $feedbackData['master_id'])
                            ->with('error', 'Если услуга оказана мастером выберите какой услугой вы воспользовались и оцените её качество.');

                }

                    return redirect()
                        ->intended('/master_card/' . $feedbackData['master_id'])
                        ->with('error', 'Отзыв отклонен.');
            }

        $feedbackData['service_provided'] = '0';
        Feedback::create($feedbackData);

        return redirect()
            ->intended('/master_card/' . $feedbackData['master_id'])
            ->with('success', 'Отзыв успешно добавлен.');
    }

    public function checkConnect(int $userId, int $masterId): bool
    {
        $message = Message::where('user_from', $userId)->where('user_to', $masterId)->first();
        $order = Order::where('user_from', $userId)->where('user_to', $masterId)->first();
        if ($message || $order) {
            return true;
        }

        return false;
    }

    public function setNewRating(int $id): void
    {
        $totalGrades = 0;
        $grades = Feedback::where('master_id', $id)->get();
        foreach ($grades as $grade) {
            $totalGrades += $grade->grade;
        }

        $masterInfo = MasterInfo::where('user_id', $id)->first();
        $masterInfo->update(['rating' => $totalGrades/count($grades)]);
    }

    public function createSupportTicket(array $support): void
    {
        if (array_key_exists('user_id', $support)) {
            $user = User::find($support['user_id']);
            $support['email'] = $user->email;
        }

        if (array_key_exists('photo', $support)) {
            if (isset($user)) {
                $path = Storage::putFile($user->id . '/support/', $support['photo'], 'public');
            } else {
                $path = Storage::putFile('general/support/', $support['photo'], 'public');
            }
            $support['photo'] = $path;
        }

        Support::create($support);
    }
}
