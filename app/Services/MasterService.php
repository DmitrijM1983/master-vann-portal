<?php

namespace App\Services;

use App\Http\Requests\UpdateMasterInfoRequest;
use App\Models\Answer;
use App\Models\City;
use App\Models\Feedback;
use App\Models\JobImage;
use App\Models\MasterInfo;
use App\Models\Message;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterService implements IMasterService
{
    public function searchData(): array
    {
        return [
            City::pluck('name')->toArray(),
            Service::pluck('name')->toArray()
        ];
    }

    public function searchResult(Request $request): Collection
    {
        $cityId = City::where('name', $request->city)->value('id');
        $serviceId = Service::where('name', $request->service)->value('id');

        $masters = User::whereHas('cities', function ($query) use ($cityId) {
            $query->where('city_id', $cityId)->where('role_id', 1);
        })->whereHas('services', function ($query) use ($serviceId) {
            $query->where('service_id', $serviceId);
        })->get();

        return $masters->sortByDesc(function ($master) {
            return optional($master->mastersInfo)->rating; // Возвращает rating или null
        });
    }

    public function getMasterInfo(User $user, string $sort): array
    {
        $serviceIds = ServiceUser::where('user_id', $user->id)->get('service_id')->toArray();

        $prices = [];
        foreach ($serviceIds as $services) {
            foreach ($services as $serviceId) {
                $prices[$serviceId] = ServiceUser::where('user_id', $user->id)->where('service_id', $serviceId)->get('price');
            }
        }

        $servicePrice = [];
        foreach($prices as $key=>$collection){
            $servicePrice[$key] = $collection[0]->price;
        }

        $serviceNames = $user->services()->pluck('name')->toArray();

        $images = JobImage::where('user_id', $user->id)->get();

        $feedbacks = $this->sortFeedbacks($user->id, $sort);
        foreach ($feedbacks as $feedback) {
            $feedback['images'] = json_decode($feedback['images']);
        }

        $feedbacksCount = Feedback::where('master_id', $user->id)->get();
        $countOfFiveStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === 5)->count();
        $countOfFourStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === 4)->count();
        $countOfThreeStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === 3)->count();
        $countOfTwoStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === 2)->count();
        $countOfOneStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === 1)->count();
        $countOfWithoutStarFeedbacks = $feedbacksCount->filter(static fn (Feedback $feedback) => $feedback->grade === null)->count();

        return [
            $images,
            array_combine($serviceNames, $servicePrice),
            $feedbacks,
            $countOfFiveStarFeedbacks,
            $countOfFourStarFeedbacks,
            $countOfThreeStarFeedbacks,
            $countOfTwoStarFeedbacks,
            $countOfOneStarFeedbacks,
            $countOfWithoutStarFeedbacks
        ];
    }

    public function sortFeedbacks(int $userId, string $sort): \Illuminate\Support\Collection|Collection
    {
        switch ($sort) {
            case 'newest':
                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                return $feedbacksWithGrade->merge($feedbacksWithoutGrade)
                    ->values();

            case 'oldest':
                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->orderBy('created_at', 'asc')
                    ->get();

                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderBy('created_at', 'asc')
                    ->get();

                return $feedbacksWithGrade->merge($feedbacksWithoutGrade)
                    ->values();

            case 'with-images':
                $feedbacksWithImages = Feedback::where('master_id', $userId)
                    ->whereNotNull('images')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->whereNull('images')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                return $feedbacksWithImages->merge($feedbacksWithGrade)
                    ->merge($feedbacksWithoutGrade)
                    ->values();

            case 'good':
                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->orderByDesc('grade')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                return $feedbacksWithGrade->merge($feedbacksWithoutGrade)
                    ->values();

            case 'bad':
                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->orderBy('grade', 'asc')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                return $feedbacksWithGrade->merge($feedbacksWithoutGrade)
                    ->values();

            case 'without-grade':
                $feedbacksWithoutGrade = Feedback::where('master_id', $userId)
                    ->whereNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                $feedbacksWithGrade = Feedback::where('master_id', $userId)
                    ->whereNotNull('grade')
                    ->orderByDesc('created_at')
                    ->get();

                return $feedbacksWithoutGrade->merge($feedbacksWithGrade)
                    ->values();

            default:
                return collect();
        }
    }

    public function updateMasterInfo(array $data, MasterInfo $master, UpdateMasterInfoRequest $request): MasterInfo
    {
        if ($request->has('master_photo')) {
            $master_photo = $master->master_photo;

            $data['master_photo'] = Storage::putFile($master->user_id . '/master_photo', $request->file('master_photo'), 'public');

            if ($master_photo) {
                Storage::delete($master_photo);
            }
        }

        $master->update($data);

        return $master;
    }

    public function setServices(int $id, Request $request): void
    {
        $services = [
            1 => 'enamel',
            2 => 'acrylic',
            3 => 'liner'
        ];

        foreach ($services as $serviceId => $serviceKey) {
            if ($request->has($serviceKey) && $request->has('price-' . $serviceKey)) {
                $price = $request->input('price-' . $serviceKey);
                $serviceUser = ServiceUser::where('service_id', $serviceId)->where('user_id', $id)->first();

                if ($serviceUser) {
                    // Обновляем цену, если услуга уже существует
                    $serviceUser->update(['price' => $price]);
                } else {
                    // Создаем новую запись, если услуга не существует
                    ServiceUser::create(['service_id' => $serviceId, 'user_id' => $id, 'price' => $price]);
                }
            } else {
                // Если услуга не выбрана, удаляем её
                ServiceUser::where('service_id', $serviceId)->where('user_id', $id)->delete();
            }
        }
    }

    public function getMessagesInfo(int $id): array
    {
        $messages = Message::where('user_from', $id)->get();

        $noReadAnswerCount = 0;
        $noReadAnswer = [];
        foreach ($messages as $message) {
            $answer = Answer::where('message_id', $message->id)->where('is_read', false)->first();
            if ($answer) {
                $noReadAnswerCount += 1;
                $noReadAnswer[] = $answer;
            }
        }

        return [
            'messages' => $messages,
            'noReadAnswerCount' => $noReadAnswerCount,
            'noReadAnswer' => $noReadAnswer
        ];
    }
}
