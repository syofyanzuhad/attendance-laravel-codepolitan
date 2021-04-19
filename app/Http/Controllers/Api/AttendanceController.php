<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{

    use ImageStorage;
    /**
     * Store presence status
     * @param Request $request
     * @return JsonResponse|void
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            'type' => ['in:in,out', 'required'],
            'photo' => ['required', 'image']
        ]);

        $photo = $request->file('photo');
        $attendanceType = $request->type;
        $userAttendanceToday = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        // is presence type equal with 'in' ?
        if ($attendanceType == 'in') {
            // is $userPresenceToday not found?
            if (! $userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => false
                        ]
                    );

                $attendance->detail()->create(
                    [
                        'type' => 'in',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                $response =  response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            } else {
                $response =  response()->json(
                    [
                        'message' => 'User has been checked in',
                    ],
                    Response::HTTP_OK
                );
            }

            return $response;

            // else show user has been checked in
        }

        if ($attendanceType == 'out') {
            if ($userAttendanceToday) {

                if ($userAttendanceToday->status) {
                    return response()->json(
                        [
                            'message' => 'User has been checked out',
                        ],
                        Response::HTTP_OK
                    );
                }

                $userAttendanceToday->update(
                    [
                        'status' => true
                    ]
                );

                $userAttendanceToday->detail()->create(
                    [
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                $response = response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            } else {
                $response = response()->json(
                    [
                        'message' => 'Please do check in first',
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            return $response;

        }
    }

}
